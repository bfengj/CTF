package com.ctf.badbean.main;
import com.alibaba.com.caucho.hessian.io.Hessian2Input;
import org.springframework.boot.autoconfigure.EnableAutoConfiguration;
import org.springframework.stereotype.*;
import org.springframework.ui.*;
import java.io.*;
import java.util.Base64;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
@EnableAutoConfiguration
@RestController
public class IndexController
{
    @GetMapping("/")
    public String hello(){
        return "<h1>Starter BadBean v2.0</h1><code>Other router at /api</code>";
    }

    @RequestMapping("/api")
    public String starter(@RequestParam(name = "data", required = false) final String data, final Model model) throws Exception {
        if(data!=null){
            final byte[] b = Base64.getDecoder().decode(data);
            final InputStream inputStream = new ByteArrayInputStream(b);
            Hessian2Input hessian2Input = new Hessian2Input(inputStream);
            hessian2Input.readObject();
        }

        return "<h1>Api is running,This time i use hessian2, plz hack me</h1>";
    }
}

