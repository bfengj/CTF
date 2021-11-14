package com.example.jdbcSer;

import com.alibaba.fastjson.JSON;
import org.springframework.web.bind.annotation.*;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

@RestController
public class Register {
    @GetMapping("/register")
    public boolean register(@RequestParam(value = "user", defaultValue = "{\"username\":\"hacker\",\"password\":\"guess\"}")String user) throws Exception {
        System.out.println(user);
        user = user.replace("'", "\"");
        Pattern pattern = Pattern.compile("\"username\":\"(.*?)\"");
        Matcher matcher = pattern.matcher(user);
        String username = "";
        while (matcher.find()){
            username =matcher.group();
        }
        if(!username.isEmpty()){
            user = user.replace(username, "\"username\":\"hacker\"");
        }
        System.out.println(user);

        UserBean o = JSON.parseObject(user, UserBean.class);
        JdbcUtils jdbcUtils = new JdbcUtils("jdbc:mysql://127.0.0.1:3306/www?serverTimezone=UTC", "root", "root");
        jdbcUtils.insert(o);
        return true;
    }
}
