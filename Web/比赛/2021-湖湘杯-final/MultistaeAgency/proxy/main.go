package main

import (
	"github.com/elazarl/goproxy"
	"io/ioutil"
	"log"
	"net/http"
	"os"
)




func main() {
	file, err := os.Open("secret/key")
	if err != nil {
		panic(err)
	}
	defer file.Close()
	content, err := ioutil.ReadAll(file)
	SecretKey := string(content)
	proxy := goproxy.NewProxyHttpServer()
	proxy.Verbose = true
	proxy.OnRequest().DoFunc(
		func(r *http.Request,ctx *goproxy.ProxyCtx)(*http.Request,*http.Response) {
			r.Header.Set("Secretkey",SecretKey)
			return r,nil
		})
	log.Print("start listen 8080")
	log.Fatal(http.ListenAndServe(":8080", proxy))
}