/**
 * @Author: Mockingjay
 * @Description: Cros
 * @File:  CrosHandler
 * @Version: 1.0.0
 * @Date: 2021/10/19 09:39
 */
package handler
 
import (
	"os"
	"github.com/gin-gonic/gin"
	"github.com/gin-contrib/sessions"
)


func LoginCheckMiddleWare() gin.HandlerFunc {
    return func(c *gin.Context) {
        session := sessions.Default(c)

        if session.Get("uname") == nil {
            c.Header("Content-Type", "text/html; charset=utf-8")
            c.String(200, "<script>alert('You have not logged in yet');window.location.href='/auth'</script>")
            return
        }

        c.Next()
    }
}


func AdminCheckMiddleWare() gin.HandlerFunc {    // You can't be administrator
    return func(c *gin.Context) {
        session := sessions.Default(c)

        if session.Get("uname") == nil {
            c.Header("Content-Type", "text/html; charset=utf-8")
            c.String(200, "<script>alert('You have not logged in yet');window.location.href='/auth'</script>")
            return
        }

        if session.Get("uname").(string) != os.Getenv("ADMIN_USER") {
            c.Header("Content-Type", "text/html; charset=utf-8")
            c.String(200, "<script>alert('You are not admin, and you can not be admin either!');window.location.href='/auth'</script>")
            return
        }

        c.Next()
    }
}



func IPCheckMiddleWare() gin.HandlerFunc {
    return func(c *gin.Context) {
        if c.Request.RemoteAddr[:9] != "127.0.0.1" && c.Request.RemoteAddr[:9] != "localhost" {
            c.JSON(403, gin.H{"msg": "I'm sorry, your IP is forbidden"})
            return
        }

        c.Next()
    }
}


func CrosHandler() gin.HandlerFunc {
	return func(context *gin.Context) {
		method := context.Request.Method
		context.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		context.Header("Access-Control-Allow-Origin", "*")
		context.Header("Access-Control-Allow-Methods", "POST, GET, OPTIONS, PUT, DELETE,UPDATE")
		context.Header("Access-Control-Allow-Headers", "Authorization, Content-Length, X-CSRF-Token, Token,session,X_Requested_With,Accept, Origin, Host, Connection, Accept-Encoding, Accept-Language,DNT, X-CustomHeader, Keep-Alive, User-Agent, X-Requested-With, If-Modified-Since, Cache-Control, Content-Type, Pragma,token,openid,opentoken")
		context.Header("Access-Control-Expose-Headers", "Content-Length, Access-Control-Allow-Origin, Access-Control-Allow-Headers,Cache-Control,Content-Language,Content-Type,Expires,Last-Modified,Pragma,FooBar")
		context.Header("Access-Control-Max-Age", "172800")
		context.Header("Access-Control-Allow-Credentials", "false")
		context.Set("content-type", "application/json")
 
		if method == "OPTIONS" {
			context.AbortWithStatus(204)
			return
		}
 
		context.Next()
	}
}
