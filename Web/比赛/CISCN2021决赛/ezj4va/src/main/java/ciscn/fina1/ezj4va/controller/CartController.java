package ciscn.fina1.ezj4va.controller;

import ciscn.fina1.ezj4va.domain.Cart;
import ciscn.fina1.ezj4va.service.CartService;
import ciscn.fina1.ezj4va.service.impl.CartServiceImpl;
import ciscn.fina1.ezj4va.utils.R;
import ciscn.fina1.ezj4va.utils.Serializer;
import com.alibaba.fastjson.JSON;

import javax.servlet.ServletException;
import javax.servlet.ServletOutputStream;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

@WebServlet(
        name = "CartController",
        urlPatterns = {"/cart/*"}
)
public class CartController extends HttpServlet {
    CartService cartService= CartServiceImpl.getInstance();

    @Override
    protected void service(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        String uri = req.getRequestURI();
        if(uri.startsWith("/cart/add"))
            add(req,resp);
        else if(uri.startsWith("/cart/query"))
            query(req,resp);
        else if(uri.startsWith("/cart/remove"))
            remove(req,resp);
    }

    private void add(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        String skus=req.getParameter("skus"),oldCart=null;
        Cookie[] cookies = req.getCookies();
        if(cookies!=null&&cookies.length>0){
            for(Cookie cookie:cookies){
                if("cart".equals(cookie.getName()))
                    oldCart=cookie.getValue();
            }
        }
        try {
            Cart cart=cartService.addToCart(skus,oldCart);
            Cookie cookie = new Cookie("cart", Serializer.serialize(cart));
            resp.addCookie(cookie);
            outputResponse(resp, JSON.toJSONString(R.ok()));
        } catch (Exception e) {
            e.printStackTrace();
            outputResponse(resp, JSON.toJSONString(R.error()));
        }
    }

    private void remove(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        String skus=req.getParameter("skus"),oldCart=null;
        Cookie[] cookies = req.getCookies();
        if(cookies!=null&&cookies.length>0){
            for(Cookie cookie:cookies){
                if("cart".equals(cookie.getName()))
                    oldCart=cookie.getValue();
            }
        }
        try {
            Cart cart=cartService.remove(skus,oldCart);
            Cookie cookie = new Cookie("cart", Serializer.serialize(cart));
            resp.addCookie(cookie);
            outputResponse(resp, JSON.toJSONString(R.ok()));
        } catch (Exception e) {
            e.printStackTrace();
            outputResponse(resp, JSON.toJSONString(R.error()));
        }
    }

    private void query(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        String oldCart="";
        Cookie[] cookies = req.getCookies();
        if(cookies!=null&&cookies.length>0){
            for(Cookie cookie:cookies){
                if("cart".equals(cookie.getName()))
                    oldCart=cookie.getValue();
            }
        }
        try {
            Cart query = cartService.query(oldCart);
            outputResponse(resp, JSON.toJSONString(R.ok().setData(query)));
        } catch (Exception e) {
            e.printStackTrace();
            outputResponse(resp, JSON.toJSONString(R.error()));
        }
    }



    private void outputResponse(HttpServletResponse resp, String output) throws IOException {
        ServletOutputStream out = resp.getOutputStream();
        out.write(output.getBytes());
        out.flush();
        out.close();
    }

}
