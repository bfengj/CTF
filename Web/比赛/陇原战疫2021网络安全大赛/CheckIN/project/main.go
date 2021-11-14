package main

import (
    "fmt"
    "io"
    "time"
    "bytes"
    "regexp"
    "os/exec"
    "plugin"
    "gopkg.in/mgo.v2"
    "gopkg.in/mgo.v2/bson"
    "github.com/gin-contrib/sessions"
    "github.com/gin-gonic/gin"
    "github.com/gin-contrib/sessions/cookie"
    "github.com/gin-contrib/multitemplate"
    "net/http"
)


type Url struct {
    Url string `json:"url" binding:"required"`
}

type User struct {
    Username string
    Password string
}

const MOGODB_URI = "127.0.0.1:27017"


func MiddleWare() gin.HandlerFunc {
    return func(c *gin.Context) {
        session := sessions.Default(c)

        if session.Get("username") == nil || session.Get("password") != os.Getenv("ADMIN_PASS") {
            c.Header("Content-Type", "text/html; charset=utf-8")
            c.String(200, "<script>alert('You are not admin!');window.location.href='/login'</script>")
            return
        }

        c.Next()
    }
}


func loginController(c *gin.Context) {

    session := sessions.Default(c)
    if session.Get("username") != nil {
        c.Redirect(http.StatusFound, "/home")
        return
    }
    
    username := c.PostForm("username")
    password := c.PostForm("password")

    if username == "" || password == "" {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('The username or password is empty');window.location.href='/login'</script>")
        return
    }

    conn, err := mgo.Dial(MOGODB_URI)
    if err != nil {
        panic(err)
    }

    defer conn.Close()
    conn.SetMode(mgo.Monotonic, true)

    db_table := conn.DB("ctf").C("users")
    result := User{}
    err = db_table.Find(bson.M{"$where":"function() {if(this.username == '"+username+"' && this.password == '"+password+"') {return true;}}"}).One(&result)

    if result.Username == "" {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('Login Failed!');window.location.href='/login'</script>")
        return
    }

    if username == result.Username || password == result.Password {
        session.Set("username", username)
        session.Set("password", password)
        session.Save()
        c.Redirect(http.StatusFound, "/home")
        return
    } else {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('Pretend you logged in successfully');window.location.href='/login'</script>")
        return
    }
}



func proxyController(c *gin.Context) {
    
    var url Url
    if err := c.ShouldBindJSON(&url); err != nil {
        c.JSON(500, gin.H{"msg": err})
        return
    }
    
    re := regexp.MustCompile("127.0.0.1|0.0.0.0|06433|0x|0177|localhost|ffff")
    if re.MatchString(url.Url) {
        c.JSON(403, gin.H{"msg": "Url Forbidden"})
        return
    }
    
    client := &http.Client{Timeout: 2 * time.Second}

    resp, err := client.Get(url.Url)
    if err != nil {
        c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
        return
    }
    defer resp.Body.Close()
    var buffer [512]byte
    result := bytes.NewBuffer(nil)
    for {
        n, err := resp.Body.Read(buffer[0:])
        result.Write(buffer[0:n])
        if err != nil && err == io.EOF {

            break
        } else if err != nil {
            c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
            return
        }
    }
    c.JSON(http.StatusOK, gin.H{"data": result.String()})
}



func getController(c *gin.Context) {



    cmd := exec.Command("/bin/wget", c.QueryArray("argv")[1:]...)
    err := cmd.Run()
    if err != nil {
        fmt.Println("error: ", err)
    }
    
    c.String(http.StatusOK, "Nothing")
}




func createMyRender() multitemplate.Renderer {
    r := multitemplate.NewRenderer()
    r.AddFromFiles("login", "templates/layouts/base.tmpl", "templates/layouts/login.tmpl")
    r.AddFromFiles("home", "templates/layouts/home.tmpl", "templates/layouts/home.tmpl")
    return r
}


func main() {
    router := gin.Default()
    router.Static("/static", "./static")

    p, err := plugin.Open("sess_init.so")
    if err != nil {
        panic(err)
    }

    f, err := p.Lookup("Sessinit")
    if err != nil {
        panic(err)
    }
    key := f.(func() string)()

    storage := cookie.NewStore([]byte(key))
    router.Use(sessions.Sessions("mysession", storage))
    router.HTMLRender = createMyRender()
    router.MaxMultipartMemory = 8 << 20

    router.GET("/", func(c *gin.Context) {
        session := sessions.Default(c)
        if session.Get("username") != nil {
            c.Redirect(http.StatusFound, "/home")  
            return
        } else {
            c.Redirect(http.StatusFound, "/login")  
            return
        }
    })

    router.GET("/login", func(c *gin.Context) {
        session := sessions.Default(c)
        if session.Get("username") != nil {
            c.Redirect(http.StatusFound, "/home")  
            return
        }
        c.HTML(200, "login", gin.H{
            "title": "CheckIn",
        })
    })

    router.GET("/home", MiddleWare(), func(c *gin.Context) {
        c.HTML(200, "home", gin.H{
            "title": "CheckIn",
        })
    })

    router.POST("/proxy", MiddleWare(), proxyController)
    router.GET("/wget", getController)
    router.POST("/login", loginController)

    _ = router.Run("0.0.0.0:8080") // listen and serve on 0.0.0.0:8080
}