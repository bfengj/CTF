package ciscn.fina1.ezj4va.controller;

import ciscn.fina1.ezj4va.launch.Main;

import javax.servlet.ServletException;
import javax.servlet.ServletOutputStream;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.net.URLEncoder;

@WebServlet(
        name = "IndexController",
        urlPatterns = {"/*","/"}
    )
public class IndexController extends HttpServlet {

    @Override
    protected void service(HttpServletRequest req, HttpServletResponse resp)
            throws IOException {
        String uri = req.getRequestURI();
        if("/robots.txt".equals(uri)){
            outputResponse(resp,"disable:www.zip");
        }else if("/www.zip".equals(uri)){
            try {
                download(resp);
            } catch (URISyntaxException e) {
                e.printStackTrace();
            }
        }else{
            outputResponse(resp,"Welcome to ezj4va");
        }
    }

    private void outputResponse(HttpServletResponse resp, String output) throws IOException {
        ServletOutputStream out = resp.getOutputStream();
        out.write(output.getBytes());
        out.flush();
        out.close();
    }

    private void download(HttpServletResponse resp) throws IOException, URISyntaxException {
        File f = new File(Main.getRootFolder().getAbsolutePath(), "target/classes/www.zip");
        if(f.exists()){
            FileInputStream fis = new FileInputStream(f);
            String filename= URLEncoder.encode(f.getName(),"utf-8");
            byte[] b = new byte[fis.available()];
            fis.read(b);
            resp.setCharacterEncoding("utf-8");
            resp.setHeader("Content-Disposition","attachment; filename="+filename+"");
            //获取响应报文输出流对象
            ServletOutputStream  out =resp.getOutputStream();
            //输出
            out.write(b);
            out.flush();
            out.close();
        }
    }

    
}
