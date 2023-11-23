# GIN

获取get参数：

```go
	username := c.Query("username")
	password := c.Query("password")
```

post参数：

```go
	username := c.PostForm("username")//x-www-form-urlencoded或from-data格式的参数
```

上传文件：

```go
func upload(c *gin.Context) {
	file, err := c.FormFile("file")
	if err != nil {
		c.String(500, "上传图片出错")
	}
	// c.JSON(200, gin.H{"message": file.Header.Context})
	c.SaveUploadedFile(file, "upload/"+file.Filename)
	c.String(http.StatusOK, file.Filename)
}
```

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="http://localhost:8000/uploadFile" method="post" enctype="multipart/form-data">
    上传文件:<input type="file" name="file" >
    <input type="submit" value="提交">
</form>
</body>
</html>
```

上传多个文件：

```go
      form, err := c.MultipartForm()
      if err != nil {
         c.String(http.StatusBadRequest, fmt.Sprintf("get err %s", err.Error()))
      }
      // 获取所有图片
      files := form.File["files"]
      // 遍历所有图片
      for _, file := range files {
         // 逐个存
         if err := c.SaveUploadedFile(file, file.Filename); err != nil {
            c.String(http.StatusBadRequest, fmt.Sprintf("upload err %s", err.Error()))
            return
         }
      }
      c.String(200, fmt.Sprintf("upload ok %d files", len(files)))
```



路由组管理：

```go
	// 1.创建路由
	r := gin.Default()
	r.Static("/", "./static")
	// 2.绑定路由规则，执行的函数
	// gin.Context，封装了request和response

	userRouteGroup := r.Group("/user")
	{
		userRouteGroup.POST("/login", login)
	}
	adminRouteGroup := r.Group("/admin")
	{
		adminRouteGroup.POST("/login", login)
	}
```

分别对应路由`/user/login`和`/admin/login`。



404页面设置：

```go
	r.NoRoute(func(c *gin.Context) {
		c.String(http.StatusNotFound, "404 not found")
	})
```

