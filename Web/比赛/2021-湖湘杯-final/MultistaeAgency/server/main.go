package main

import (
	"bytes"
	"crypto/md5"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"os"
	"os/exec"
	"unicode"
)

// 检查来源ip为本地才继续执行

var SecretKey = ""

func getToken(w http.ResponseWriter, r *http.Request) {
	header := r.Header
	token := "error"
	var sks []string = header["Secretkey"]
	sk := ""
	if len(sks) == 1 {
		sk = sks[0]
	}
	var fromHosts []string = header["Fromhost"]
	fromHost := ""
	if len(fromHosts) == 1 {
		fromHost = fromHosts[0]
	}
	if fromHost != "" && sk != "" && sk == SecretKey {
		data := []byte(sk + fromHost)
		has := md5.Sum(data)
		token = fmt.Sprintf("%x", has)
	}
	fmt.Fprintf(w, token)
}

func manage(w http.ResponseWriter, r *http.Request) {
	values := r.URL.Query()
	m := values.Get("m")
	if !waf(m) {
		fmt.Fprintf(w, "waf!")
		return
	}
	cmd := fmt.Sprintf("rm -rf uploads/%s", m)
	fmt.Println(cmd)
	command := exec.Command("bash", "-c", cmd)
	outinfo := bytes.Buffer{}
	outerr := bytes.Buffer{}
	command.Stdout = &outinfo
	command.Stderr = &outerr
	err := command.Start()
	res := "ERROR"
	if err != nil {
		fmt.Println(err.Error())
	}
	if err = command.Wait(); err != nil {
		res = outerr.String()
	} else {
		res = outinfo.String()

	}
	fmt.Fprintf(w, res)
}

func waf(c string) bool {
	var t int32
	t = 0
	blacklist := []string{".", "*", "?"}
	for _, s := range c {
		for _, b := range blacklist {
			if b == string(s) {
				return false
			}
		}
		if unicode.IsLetter(s) {
			if t == s {
				continue
			}
			if t == 0 {
				t = s
			} else {
				return false
			}
		}
	}

	return true
}

func main() {
	file, err := os.Open("secret/key")
	if err != nil {
		panic(err)
	}
	defer file.Close()
	content, err := ioutil.ReadAll(file)
	SecretKey = string(content)
	http.HandleFunc("/", getToken)     //设置访问的路由
	http.HandleFunc("/manage", manage) //设置访问的路由
	log.Print("start listen 9091")
	err = http.ListenAndServe(":9091", nil) //设置监听的端口
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}
}
