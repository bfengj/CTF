package com.example.jdbcSer;

import com.alibaba.fastjson.JSON;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;
import java.sql.ResultSet;


@RestController
public class Login {

    @GetMapping("/login")
    public boolean login(HttpServletRequest request) throws Exception {
        String data = request.getParameter("data");
        UserBean user = JSON.parseObject(data, UserBean.class);
        JdbcUtils jdbcUtils = new JdbcUtils("jdbc:mysql://127.0.0.1:3306/www?serverTimezone=UTC", "root", "root");
        ResultSet rs = jdbcUtils.select(user);
        int count = 0;
        while (rs.next()){
            count = rs.getInt(1);
        }
        if(count > 0){
            HttpSession httpSession = request.getSession();
            httpSession.setAttribute("username", user.getUsername());
            return true;
        }
        return false;
    }
}
