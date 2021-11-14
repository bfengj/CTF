package com.example.jdbcSer;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class Index {

    @GetMapping("/")
    public String index() {
        return "wow!!! easy management system!!!";
    }
}
