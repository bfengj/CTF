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
 * @version : JdbcUrlFilter.java, v 0.1 2021年03月01日 7:41 下午 fantasyC4t Exp $
 */
@WebFilter(
        filterName = "AntiUrlAttackFilter",
        urlPatterns = {"/*"}
)
public class AntiUrlAttackFilter implements Filter {
    @Override
    public void init(FilterConfig filterConfig) throws ServletException {

    }

    @Override
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) servletRequest;
        HttpServletResponse res = (HttpServletResponse) servletResponse;
        String url = req.getRequestURI();

        if (url.contains("../") && url.contains("..") && url.contains("//")) {
            res.sendError(HttpServletResponse.SC_BAD_REQUEST, "The '.' & '/' is not allowed in the url");
        } else if (url.contains("\20")) {
            res.sendError(HttpServletResponse.SC_BAD_REQUEST, "The empty value is not allowed in the url.");
        } else if (url.contains("\\")) {
            res.sendError(HttpServletResponse.SC_BAD_REQUEST, "The '\\' is not allowed in the url.");
        } else if (url.contains("./")) {
            String filteredUrl = url.replaceAll("./", "");
            req.getRequestDispatcher(filteredUrl).forward(servletRequest, servletResponse);
        } else if (url.contains(";")) {
            String filteredUrl = url.replaceAll(";", "");
            req.getRequestDispatcher(filteredUrl).forward(servletRequest, servletResponse);
        } else {
            filterChain.doFilter(servletRequest, servletResponse);
        }
    }

    @Override
    public void destroy() {

    }

}