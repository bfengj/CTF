package main

import (
	"bytes"
	"crypto/md5"
	"encoding/json"
	"fmt"
	"io"
	"io/ioutil"
	"log"
	"math/rand"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"strings"
)

var SecretKey = ""

type TokenResult struct {
	Success string `json:"success"`
	Failed string `json:"failed"`
}


const letterBytes = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
func RandStringBytes(n int) string {
	b := make([]byte, n)
	for i := range b {
		b[i] = letterBytes[rand.Intn(len(letterBytes))]
	}
	return string(b)
}

func getToken(w http.ResponseWriter, r *http.Request) {
	values := r.URL.Query()
	fromHostList := strings.Split(r.RemoteAddr, ":")
	fromHost := ""
	if len(fromHostList) == 2 {
		fromHost = fromHostList[0]
	}
	r.Header.Set("Fromhost", fromHost)
	command := exec.Command("curl", "-H", "Fromhost: "+fromHost, "127.0.0.1:9091")
	for k, _ := range values {
		command.Env = append(command.Env, fmt.Sprintf("%s=%s", k, values.Get(k)))

	}
	outinfo := bytes.Buffer{}
	outerr := bytes.Buffer{}
	command.Stdout = &outinfo
	command.Stderr = &outerr
	err := command.Start()
	//res := "ERROR"
	if err != nil {
		fmt.Println(err.Error())
	}
	res := TokenResult{}
	if err = command.Wait(); err != nil {
		res.Failed = outerr.String()
	}

	res.Success = outinfo.String()

	msg, _ := json.Marshal(res)
	w.Write(msg)

}

type ListFileResult struct {
	Files []string `json:"files"`
}

// 查看当前 token 下的文件
func listFile(w http.ResponseWriter, r *http.Request) {

	values := r.URL.Query()
	token := values.Get("token")
	fromHostList := strings.Split(r.RemoteAddr, ":")
	fromHost := ""
	if len(fromHostList) == 2 {
		fromHost = fromHostList[0]
	}
	// 验证token
	if token != "" && checkToken(token, fromHost) {
		dir := filepath.Join("uploads",token)
		files, err := ioutil.ReadDir(dir)
		if err == nil {
			var fs []string
			for _, f := range files {
				fs = append(fs, f.Name())
			}

			msg, _ := json.Marshal(ListFileResult{Files: fs})
			w.Write(msg)

		}

	}

}

type UploadFileResult struct {
	Code string `json:"code"`
}

func uploadFile(w http.ResponseWriter, r *http.Request) {

	if r.Method == "GET" {
		fmt.Fprintf(w, "get")
	} else {
		values := r.URL.Query()
		token := values.Get("token")
		fromHostList := strings.Split(r.RemoteAddr, ":")
		fromHost := ""
		if len(fromHostList) == 2 {
			fromHost = fromHostList[0]
		}
		//验证token
		if token != "" && checkToken(token, fromHost) {
			dir := filepath.Join("uploads",token)
			if _, err := os.Stat(dir); err != nil {
				os.MkdirAll(dir, 0766)
			}

			files, err := ioutil.ReadDir(dir)
			if len(files) > 5 {
				command := exec.Command("curl", "127.0.0.1:9091/manage")
				command.Start()

			}

			r.ParseMultipartForm(32 << 20)
			file, _, err := r.FormFile("file")
			if err != nil {
				msg, _ := json.Marshal(UploadFileResult{Code: err.Error()})
				w.Write(msg)
				return
			}
			defer file.Close()
			fileName := RandStringBytes(5)
			f, err := os.OpenFile(filepath.Join(dir, fileName), os.O_WRONLY|os.O_CREATE, 0666)
			if err != nil {
				fmt.Println(err)
				return
			}
			defer f.Close()
			io.Copy(f, file)
			msg, _ := json.Marshal(UploadFileResult{Code: fileName})
			w.Write(msg)
		} else {
			msg, _ := json.Marshal(UploadFileResult{Code: "ERROR TOKEN"})
			w.Write(msg)
		}

	}
}

func checkToken(token, ip string) bool {
	data := []byte(SecretKey + ip)
	has := md5.Sum(data)
	md5str := fmt.Sprintf("%x", has)
	return md5str == token
}

func IndexHandler (w http.ResponseWriter, r *http.Request) {
	http.ServeFile(w, r,"dist/index.html")
}

func main() {
	file, err := os.Open("secret/key")
	if err != nil {
		panic(err)
	}
	defer file.Close()
	content, err := ioutil.ReadAll(file)
	SecretKey = string(content)
	http.HandleFunc("/", IndexHandler)
	fs := http.FileServer(http.Dir("dist/static"))
	http.Handle("/static/", http.StripPrefix("/static/", fs))
	http.HandleFunc("/token", getToken)
	http.HandleFunc("/upload", uploadFile)
	http.HandleFunc("/list", listFile)
	log.Print("start listen 9090")
	err = http.ListenAndServe(":9090", nil)
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}
}
