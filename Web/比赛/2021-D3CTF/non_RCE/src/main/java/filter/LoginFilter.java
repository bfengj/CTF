/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package filter;

import javax.servlet.*;
import javax.servlet.annotation.WebFilter;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

/**
 *
 * @author fantasyC4t
 * @version : AdminFilter.java, v 0.1 2021年03月01日 7:18 下午 fantasyC4t Exp $
 */
@WebFilter(
        filterName = "LoginFilter",
        urlPatterns = {"/admin/*"}
)
public class LoginFilter implements Filter {
    //The true password has being removed from the source code for security.
    private static final String PASSWORD = "";

    @Override
    public void init(FilterConfig var1) throws ServletException {

    }

    @Override
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) servletRequest;
        HttpServletResponse res = (HttpServletResponse) servletResponse;
        String password = req.getParameter("password");
        if (password == null) {
            res.sendError( HttpServletResponse.SC_UNAUTHORIZED, "The password can not be null!");
            return;
        }
        try {
            //you can't get this password forever, because the author has forgotten it.
            if (password.equals(PASSWORD)) {
                filterChain.doFilter(servletRequest, servletResponse);
            } else {
                res.sendError(HttpServletResponse.SC_UNAUTHORIZED, "The password is not correct!");
            }
        } catch (Exception e) {
            res.sendError( HttpServletResponse.SC_BAD_REQUEST, "Oops!");
        }
    }

    @Override
    public void destroy() {

    }
}