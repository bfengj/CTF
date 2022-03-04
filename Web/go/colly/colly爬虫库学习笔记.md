# colly爬虫库学习笔记

## 前言

稍微的学习了一下Go语言的基础知识（错误处理和协程通道这些还没看），想着能不能做点东西，突然想到自己当时学了python之后就是专门为了写爬虫（虽然后来也咕了，只会一个request.get和post），所以也稍微的学习一下Go语言的爬虫（暂时不会太深入，更深入的东西等以后再慢慢学了）。

## 安装

因为用的是Goland，所以安装直接就让Goland来安装了。

## 学习笔记

### 简单例子

一个简单的例子：

```go
package main

import (
	"fmt"
	"github.com/gocolly/colly"
)

func main(){
	c := colly.NewCollector()
	c.OnRequest(func(request *colly.Request) {
		fmt.Println("Visiting",request.URL)
	})
	c.OnResponse(func(response *colly.Response) {
		fmt.Println(string(response.Body))
	})
	c.Visit("http://www.baidu.com")
}

```

看到这样的代码其实我的第一反应是这样的代码风格和node.js有点相像，传入的都是各种回调函数。



`Colly`主要实体就是`Collector`对象，通过`colly.NewCollector()`来创建。没有传入任何参数的话就是默认的配置（其实也足够用了）。

也可以传入选项：

```go
	c := colly.NewCollector(
		colly.AllowedDomains("www.baidu.com"),//限制对特定域的爬取
		colly.AllowURLRevisit(),//允许访问同一网站多次
		colly.Async(true),//允许爬虫异步完成
		)
```

这里只列出部分选项。



创建`Collector`之后，还可以对其加以限制：

```go
	c.Limit(&colly.LimitRule{
        DomainRegexp: `xz.aliyun.com`,
		Delay: 1 * time.Second,
	})
```

防止访问过快。

完整的属性如下：

```go
type LimitRule struct {
	// DomainRegexp is a regular expression to match against domains
	DomainRegexp string
	// DomainRegexp is a glob pattern to match against domains
	DomainGlob string
	// Delay is the duration to wait before creating a new request to the matching domains
	Delay time.Duration
	// RandomDelay is the extra randomized duration to wait added to Delay before creating a new request
	RandomDelay time.Duration
	// Parallelism is the number of the maximum allowed concurrent requests of the matching domains
	Parallelism    int
	waitChan       chan bool
	compiledRegexp *regexp.Regexp
	compiledGlob   glob.Glob
}
```



然后就可以对url进行访问了：

```go
var (
	url string = "http://www.baidu.com"
)


c.Visit(url)
```

### 各种On开头的回调

**1.OnRequest**
在请求之前调用

**2.OnError**
如果请求期间发生错误,则调用

**3.OnResponseHeaders**
在收到响应标头后调用

**4.OnResponse**
收到回复后调用

**5.OnHTML**
`OnResponse`如果收到的内容是HTML ,则在之后调用

**6.OnXML**
`OnHTML`如果接收到的内容是HTML或XML ,则在之后调用

**7.OnScraped**
`OnXML`回调后调用



还需要知道一下回调的顺序：

1. `OnRequest`
2. `OnResponse `
3. `OnHTML `
4. `OnScraped`

简单的用法就像上面的例子那样了。接下来再细的了解一下。

#### OnRequest

常用的可能是用来中止回调：

```go
	var numVisited int = 0
	c.OnRequest(func(req *colly.Request) {
		numVisited++
		if numVisited>10{
			req.Abort()//取消本次HTTP请求
		}
		fmt.Println("request!")
	})
```

还有就是设置HTTP请求头了：

```go
	c.OnRequest(func(req *colly.Request) {
		req.Headers.Set("Cookie","PHPSESSID=hhhhh")
	})
```



#### OnResponse

收到响应后调用，更多的可能就是对响应内容进行处理或者之后的处理了。

可以看到`colly.Response`结构如下：

```go
type Response struct {
	// StatusCode is the status code of the Response
	StatusCode int
	// Body is the content of the Response
	Body []byte
	// Ctx is a context between a Request and a Response
	Ctx *Context
	// Request is the Request object of the response
	Request *Request
	// Headers contains the Response's HTTP headers
	Headers *http.Header
}
```

可以得到响应状态码，响应体，上下文，请求还有响应头。



#### OnHTML

`OnHTML`可以说是很重要的一部分了，这涉及到了对HTML的处理。这个函数是这样声明的：

```go
func (c *Collector) OnHTML(goquerySelector string, f HTMLCallback)
```

接收2个参数，第一个是一个`goquery选择器`的字符串，第二个是相应的HTML回调函数。这个回调函数是这样的：

```go
type HTMLCallback func(*HTMLElement)
```

`HTMLElement`结构如下：

```go
type HTMLElement struct {
	// Name is the name of the tag
	Name       string
	Text       string
	attributes []html.Attribute
	// Request is the request object of the element's HTML document
	Request *Request
	// Response is the Response object of the element's HTML document
	Response *Response
	// DOM is the goquery parsed DOM object of the page. DOM is relative
	// to the current HTMLElement
	DOM *goquery.Selection
	// Index stores the position of the current element within all the elements matched by an OnHTML callback
	Index int
}
```



说白了就是根据第一个参数来收集符合的HTML元素，然后调用回调函数进行处理。比如这样：

```go
	c.OnHTML("a[href]", func(element *colly.HTMLElement) {
		link := element.Attr("href")
		//fmt.Println(link)
		fmt.Println(element.Request.AbsoluteURL(link))
	})
```

将所有含有`href`属性的a标签调用回调函数，函数中获取了这个元素的href属性，然后将其转换为绝对URL后打印。

还可以获取的就是上面`HTMLElement`结构体的东西了，比如文本：`fmt.Println(element.Text)`

```go
	c.OnHTML("title", func(element *colly.HTMLElement) {
		fmt.Println(element.Text)
	})
```



还可以获取它的子元素：

```go
fmt.Println(e.ChildText("p"))
```



还可以调用`Foreach`方法迭代满足query的所有子元素并调用回调函数：

```go
		element.ForEach("p", func(_ int, element *colly.HTMLElement) {
			if strings.Contains(element.Text,"feng"){
				fmt.Println("ok")
			}
		})
```

```go
func (h *HTMLElement) ForEach(goquerySelector string, callback func(int, *HTMLElement))
```



总的来说如下：

- `Attr(k string)`：返回当前元素的属性
- `ChildAttr(goquerySelector, attrName string)`：返回`goquerySelector`选择的第一个子元素的`attrName`属性；
- `ChildAttrs(goquerySelector, attrName string)`：返回`goquerySelector`选择的所有子元素的`attrName`属性，以`[]string`返回；
- `ChildText(goquerySelector string)`：拼接`goquerySelector`选择的子元素的文本内容并返回；
- `ChildTexts(goquerySelector string)`：返回`goquerySelector`选择的子元素的文本内容组成的切片，以`[]string`返回。
- `ForEach(goquerySelector string, callback func(int, *HTMLElement))`：对每个`goquerySelector`选择的子元素执行回调`callback`；
- `Unmarshal(v interface{})`：通过给结构体字段指定 goquerySelector 格式的 tag，可以将一个 `HTMLElement` 对象 `Unmarshal` 到一个结构体实例中。



其实感觉想熟练的爬取的话，肯定还需要再学一下`goquery`的，但是我太烂了。。。就需要用的时候取查好了。



## 练习1

暂时是打算学习到这里，以后再进行更加深入的学习，但是总不能不做点东西就直接结束学习了，想了一下打算自己练习一下，爬取先知社区中所有文章的标题及其链接。



首先尝试访问了一下，发现直接error了，不知道咋回事，panic出来发现`unexpected EOF`，然后不知道为啥，尝试加上Cookie果然OK了。

接着就是想办法查找到每一篇文章的标题和链接了，发现都是这部分：

```html
<p class="topic-summary">
    <a class="topic-title" href="/t/10677">
        快速探测目标防火墙出网端口的工具化实现</a>
</p>
```



所以找到class为`topic-title`的标签即可了。去查一下`goquery`的class查找，原来是前面加上一个点即可。再加上一下速率的限制即可。总的来说爬先知文章的标题和链接还是比较简单的。



```go
package main

import (
	"fmt"
	"github.com/gocolly/colly"
	"strconv"
	"strings"
	"time"
)

type Article struct {
	title string
	url string
}

var (
	url string = "https://xz.aliyun.com/?page="
	articles []*Article
	finished bool = false
)

func main(){
	c := colly.NewCollector()
	c.Limit(&colly.LimitRule{
		DomainRegexp: `xz.aliyun.com`,
		Delay:  1*time.Second,
	})
	c.OnRequest(func(request *colly.Request) {
		fmt.Println(time.Now())
		request.Headers.Set("Cookie","")

	})

	c.OnError(func(response *colly.Response, err error) {
		if strings.Contains(err.Error(),"Not Found") {
			finished = true
		}else {
			panic(err.Error())
		}
	})
	c.OnResponse(func(resp *colly.Response) {
		if strings.Contains(string(resp.Body),"找不到") {
			finished = true
		}
	})

	c.OnHTML(".topic-title", func(element *colly.HTMLElement) {
		article := new(Article)
		article.title = strings.Trim(element.Text,"\n ")
		article.url = element.Request.AbsoluteURL(element.Attr("href"))
		articles = append(articles, article)
	})

	for i:=1;finished!=true;i++ {
		c.Visit(url+strconv.Itoa(i))
	}

	for _,v := range articles{
		fmt.Println(v)
	}
}
```







## 参考文章

https://www.ulovecode.com/2020/04/28/Go/Colly%E5%AE%98%E6%96%B9%E6%96%87%E6%A1%A3%E5%AD%A6%E4%B9%A0%E4%BB%8E%E5%85%A5%E9%97%A8%E5%88%B0%E5%85%A5%E5%9C%9F/

https://darjun.github.io/2021/06/30/godailylib/colly/

https://blog.csdn.net/qq_27818541/article/details/111492773