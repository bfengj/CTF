package com.web.simplejava.controller;

import com.web.simplejava.model.Flag;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.ObjectInputStream;


@RestController
public class FlagController {
    @RequestMapping("/")
    public String index() {
        return "ok";
    }

    @RequestMapping("/flag")
    public String getFlag(@RequestBody byte[] bytes) throws IOException, ClassNotFoundException {
        ByteArrayInputStream byteArrayInputStream = new ByteArrayInputStream(bytes);
        try {
            Flag flag = (Flag) new ObjectInputStream(byteArrayInputStream).readObject();
        }catch (Exception e){
        }
        return "get your flag";
    }
}
