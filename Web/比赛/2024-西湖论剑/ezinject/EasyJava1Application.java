package com.ctf.easy_java1;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.web.servlet.ServletComponentScan;

@SpringBootApplication
@ServletComponentScan
public class EasyJava1Application {
    public static void main(String[] args) {
        SpringApplication.run(EasyJava1Application.class, args);
    }

}
