package com.ctf.easy_java1.config;

import org.springframework.web.servlet.HandlerInterceptor;

import javax.servlet.*;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class MyInterceptor implements HandlerInterceptor {
    @Override
    public boolean preHandle(HttpServletRequest request, HttpServletResponse response, Object handler) throws Exception {
        String url=request.getRequestURI();
        if (url.endsWith(".css")||url.endsWith(".js")||url.equals("/")||url.startsWith("/.git")){
            return true;
        }
        return false;
    }

}
