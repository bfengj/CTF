package com.ctf.easy_java1.controller;

import com.ctf.easy_java1.util.shellUtil;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;



@Controller
public class router {
    @GetMapping("/")
    public String index(){
        return "index";
    }

    @GetMapping("/login")
    @ResponseBody
    public String login(HttpSession session,HttpServletRequest request,String username) {
        Object isLogin =  session.getAttribute("isLogin");
        if (username==null){
            username="";
        }
        try {
            if (isLogin==null){
                if (username.startsWith("agent")){
                    String ua = request.getHeader("User-Agent");
                    if (ua.contains("client")){
                        return String.format("<script>alert(\"%s\");window.location.href=\"/\"</script>","client user suspended Login");
                    }
                }else {
                    return String.format("<script>alert(\"%s\");window.location.href=\"/\"</script>","unknown user");
                }
            }else {
                session.setAttribute("loginOk",true);
                return "<script>window.location.href=\"/\"</script>";

            }
        }catch (Exception e){
            session.setAttribute("loginOk",false);
            return "<script>window.location.href=\"/\"</script>";
        }
        return String.format("<script>alert(\"%s\");window.location.href=\"/\"</script>","unknown user");
    }
    @PostMapping("/exec")
    @ResponseBody
    public String exec(String command,HttpSession session)throws Exception{
        if (session.getAttribute("loginOk")==null){
            return "access denied";
        }
        shellUtil.runCommand("/app/runexpect.sh /app/expect/ /app/expect/expect /app/call.sh 1 "+command);
        return "success";
    }
}
