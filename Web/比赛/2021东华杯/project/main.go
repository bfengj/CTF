package main

import (
    "./handler"
    "fmt"
    "github.com/SebastiaanKlippert/go-wkhtmltopdf"
    "github.com/gin-contrib/multitemplate"
    "github.com/gin-contrib/sessions"
    "github.com/gin-contrib/sessions/redis"
    "github.com/gin-gonic/gin"
    "gopkg.in/mgo.v2"
    "gopkg.in/mgo.v2/bson"
    "net/http"
    "os"
    "os/exec"
    "path/filepath"
    "regexp"
    "strings"
)


type Book struct {
    Title string `form:"title" binding:"required"`
    Author string `form:"author" binding:"required"`
    Description string `form:"description" binding:"required"`
}

type Filename struct {
    Filename string `form:"filename" binding:"required"`
}

type User struct {
    Username string `form:"username" binding:"required"`
    Password string `form:"password" binding:"required"`
}

var Conn *mgo.Session
var err error
const MOGODB_URI = "127.0.0.1:27017"

func init() {
    Conn, err = mgo.Dial(MOGODB_URI)
    if err != nil {
        panic(err)
    }

    //defer Conn.Close()
    Conn.SetMode(mgo.Monotonic, true)
}



func makepdf(title, author, description, covers string) string {
    // Create new PDF generator
    pdfg, err := wkhtmltopdf.NewPDFGenerator()
    if err != nil {
        fmt.Println(err)
    }
    
    template := "<!DOCTYPE html>" +
                "<html lang='en'>" +
                "<head>" +
                "<meta charset='UTF-8'>" +
                "<meta name='viewport' content='width=device-width, initial-scale=1.0'>" +
                "<title>Library</title>" +
                "</head>" +
                "<body>" +
                "<center>" + 
                "<h2>Book description</h2>" +
                "<table border='1' cellpadding='10'>" +
                "<tr>" +
                "<th>Book Title</th>" +
                "<td>" + title + "</td>" +
                "</tr>" +
                "<tr>" +
                "<th>Author</th>" +
                "<td>" + author + "</td>" +
                "</tr>" +
                "<tr>" +
                "<th> Content Abstract </th>" +
                "<td>" + description + "</td>" +
                "</tr>" +
                "</table>" +
                "<br><img src='covers/" + covers + "' height='300'>" +
                "</center>" +
                "</body>" +
                "</html>"
    
    pdfg.AddPage(wkhtmltopdf.NewPageReader(strings.NewReader(template)))

    err = pdfg.Create()
    if err != nil {
        fmt.Println(err)
    }

    err = pdfg.WriteFile("./upload/pdf/" + title + ".pdf")
    if err != nil {
        fmt.Println(err)
    }

    return template
}



func LoginController(c *gin.Context) {

    session := sessions.Default(c)
    if session.Get("uname") != nil {
        c.Redirect(http.StatusFound, "/search")
        return
    }
    
    var user User
    
    err := c.ShouldBind(&user)
    if err != nil {
        c.JSON(500, gin.H{"msg": err})
        return
    }

    if user.Username == "" || user.Password == "" {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('The username or password is empty');window.location.href='/auth'</script>")
        return
    }

    db_table := Conn.DB("ctf").C("users")
    result := User{}
    err = db_table.Find(bson.M{"$where":"function() {if(this.username == '"+user.Username+"' && this.password == '"+user.Password+"') {return true;}}"}).One(&result)

    if result.Username == "" {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('Login Failed!');window.location.href='/auth'</script>")
        return
    }

    if user.Username == result.Username || user.Password == result.Password {
        session.Set("uname", user.Username)
        session.Save()
        c.Redirect(http.StatusFound, "/search")
        return
    } else {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('Are You Kidding Me?');window.location.href='/auth'</script>")
        return
    }
}



func RegisterController(c *gin.Context) {

    session := sessions.Default(c)
    if session.Get("uname") != nil {
        c.Redirect(http.StatusFound, "/search")
        return
    }
    
    var user User

    if err := c.ShouldBind(&user); err != nil {
        c.JSON(500, gin.H{"msg": err})
        return
    }

    if user.Username == "" || user.Password == "" {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('The username or password is empty');window.location.href='/auth'</script>")
        return
    }

    db_table := Conn.DB("ctf").C("users")
    result := User{}
    err := db_table.Find(bson.M{"username": user.Username}).One(&result)

    if result.Username != "" {
        c.Header("Content-Type", "text/html; charset=utf-8")
        c.String(200, "<script>alert('The user already exists');window.location.href='/auth'</script>")
        return
    }

    err = db_table.Insert(&user)

    if err != nil {
        c.String(http.StatusBadRequest, fmt.Sprintf("Signup err: %s", err.Error()))
        return
    }

    c.Header("Content-Type", "text/html; charset=utf-8")
    c.String(200, "<script>alert('User registration succeeded');window.location.href='/auth'</script>")
    return
}



func BookHandleController(c *gin.Context) {

    var book Book

    if err := c.ShouldBind(&book); err != nil {
        c.JSON(500, gin.H{"msg": err})
        return
    }

    file, err := c.FormFile("covers")
    if err != nil {
        c.String(http.StatusBadRequest, fmt.Sprintf("Get form err: %s", err.Error()))
        return
    }

    filename := filepath.Base(file.Filename)
    if err := c.SaveUploadedFile(file, "/tmp/covers/" + filename); err != nil {
        c.String(http.StatusBadRequest, fmt.Sprintf("Upload covers err: %s", err.Error()))
        return
    }

    makepdf(book.Title, book.Author, book.Description, filename)

    db_table := Conn.DB("ctf").C("books")
    err = db_table.Insert(&book)

    if err != nil {
        c.String(http.StatusBadRequest, fmt.Sprintf("Signup err: %s", err.Error()))
        return
    }

    c.Header("Content-Type", "text/html; charset=utf-8")
    c.String(200, "<script>alert('Book uploaded successfully');window.location.href='/submit'</script>")
}



func BookSearchController(c *gin.Context) {

    booktitle := c.Query("booktitle")

    if booktitle == "" {
        c.HTML(200, "search", gin.H{
            "title": "-",
            "author": "-",
            "description": "-",
        })
    } else {
        db_table := Conn.DB("ctf").C("books")
        result := Book{}
    
        err := db_table.Find(bson.M{"title": booktitle}).One(&result)
        if err != nil {
            c.Header("Content-Type", "text/html; charset=utf-8")
            c.String(200, "<script>alert('No such book');window.location.href='/search'</script>")
            return
        }    

        if result.Title != "" && result.Author != "" && result.Description != "" {
            c.HTML(200, "search", gin.H{
                "title": result.Title,
                "author": result.Author,
                "description": result.Description,
            })
            return
        } else {
            c.HTML(200, "search", gin.H{
                "title": "-",
                "author": "-",
                "description": "-",
            })
            return
        }
    }
}



func DownloadController(c *gin.Context) {
    
    var filename Filename
    if err := c.ShouldBindJSON(&filename); err != nil {
        c.JSON(500, gin.H{"msg": err})
        return
    }
    
    re := regexp.MustCompile(`./|../|flag`)
    if re.MatchString(filename.Filename) {
        c.JSON(403, gin.H{"msg": "Files Forbidden"})
        return
    }
    
    _, errByOpenFile := os.Open("./upload/pdf/" + filename.Filename)

    if errByOpenFile != nil {
        c.JSON(http.StatusOK, gin.H{
            "success": false,
            "message": "Failed",
            "error":   "Resources do not exist",
        })
        c.Redirect(http.StatusFound, "/404")
        return
    }
    c.Header("Content-Type", "application/octet-stream")
    c.Header("Content-Disposition", "attachment; filename="+filename.Filename)
    c.Header("Content-Transfer-Encoding", "binary")
    c.File("./upload/pdf/" + filename.Filename)
    return
}



func DeleteController(c *gin.Context) {    // The function is temporarily inaccessible

    var filename Filename
    if err := c.ShouldBindJSON(&filename); err != nil {
        c.JSON(500, gin.H{"msg": err})
        return
    }
    
    cmd := exec.Command("/bin/bash", "-c", "rm ./upload/pdf/" + filename.Filename)
    if err := cmd.Run(); err != nil {
        fmt.Println(err)
        return
    }

    c.String(http.StatusOK, fmt.Sprintf("File Deleted Successfully"))
}



func createMyRender() multitemplate.Renderer {
    r := multitemplate.NewRenderer()
    r.AddFromFiles("auth", "templates/layouts/base.tmpl", "templates/layouts/auth.tmpl")
    r.AddFromFiles("search", "templates/layouts/search.tmpl", "templates/layouts/search.tmpl")
    r.AddFromFiles("submit", "templates/layouts/submit.tmpl", "templates/layouts/submit.tmpl")
    return r
}


func main() {
    
    router := gin.Default()
    router.Static("/static", "./static")
    storage, _ := redis.NewStore(10, "tcp", "localhost:6379", os.Getenv("REDIS_PASS"), []byte(os.Getenv("SECRET_KEY")))
    router.Use(sessions.Sessions("mysession", storage))
    router.Use(handler.CrosHandler())
    router.HTMLRender = createMyRender()
    router.MaxMultipartMemory = 8 << 20

    router.GET("/", func(c *gin.Context) {
        session := sessions.Default(c)
        if session.Get("uname") != nil {
            c.Redirect(http.StatusFound, "/search")  
            return
        } else {
            c.Redirect(http.StatusFound, "/auth")  
            return
        }
    })

    router.GET("/auth", func(c *gin.Context) {
        session := sessions.Default(c)
        if session.Get("uname") != nil {
            c.Redirect(http.StatusFound, "/search")  
            return
        } else {
            c.HTML(200, "auth", gin.H{
                "title": "Library Member Login",
            })
        }
    })

    router.GET("/search", handler.LoginCheckMiddleWare(), BookSearchController)

    router.GET("/submit", handler.AdminCheckMiddleWare(), func(c *gin.Context) {
        c.HTML(200, "submit", gin.H{
            "title": "Library Books Upload",
        })
    })

    router.POST("/delete", handler.IPCheckMiddleWare(), DeleteController)


    router.POST("/signin", LoginController)
    router.POST("/signup", RegisterController)
    router.POST("/download", handler.LoginCheckMiddleWare(), DownloadController)
    router.POST("/submit", handler.AdminCheckMiddleWare(), BookHandleController)

    _ = router.Run("0.0.0.0:8888") // listen and serve on 0.0.0.0:8888
}
