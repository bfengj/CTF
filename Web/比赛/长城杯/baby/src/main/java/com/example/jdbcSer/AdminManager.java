package com.example.jdbcSer;

import com.alibaba.fastjson.JSON;
import org.apache.tomcat.jni.File;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import java.io.FileReader;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.Scanner;

@RestController
public class AdminManager {
    @GetMapping("/admin")
    public String hello(HttpServletRequest request) throws Exception {
        HttpSession httpSession = request.getSession();
        if(httpSession.getAttribute("username") == null ||!httpSession.getAttribute("username").equals("admin"))
            return "not admin";
        String url = request.getParameter("url");
        String name = request.getParameter("name");
        String pwd = request.getParameter("pwd");
        ArrayList<UserBean> arrayList = new ArrayList<>();
        if(! (url.isEmpty() || name.isEmpty() || pwd.isEmpty())){
            JdbcUtils jdbcUtils = new JdbcUtils(url,name,pwd);
            ResultSet rs = jdbcUtils.selectAll();
            while (rs.next()){
                UserBean tmp = new UserBean();
                tmp.setUsername(rs.getString(1));
                tmp.setPassword(rs.getString(2));
                arrayList.add(tmp);
            }
        }
        String fileName = "/tmp/admin";

        String someThing = "";
        try (Scanner sc = new Scanner(new FileReader(fileName))) {
            while (sc.hasNextLine()) {
                someThing += sc.nextLine();
            }
        }
        return  JSON.toJSONString(arrayList) + "\n" + someThing;
    }
}
