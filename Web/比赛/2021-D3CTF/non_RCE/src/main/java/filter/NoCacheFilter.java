/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package filter;

import javax.servlet.*;
import javax.servlet.annotation.WebFilter;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

/**
 *
 * @author fantasyC4t
 * @version : NoCacheFilter.java, v 0.1 2021年03月01日 9:07 下午 fantasyC4t Exp $
 */
@WebFilter(
        filterName = "NoCacheFilter",
        urlPatterns = {"/*"}
)
public class NoCacheFilter implements Filter {
    @Override
    public void init(FilterConfig filterConfig) throws ServletException {
    }

    @Override
    public void doFilter(ServletRequest req, ServletResponse res,
                         FilterChain chain)
            throws IOException, ServletException {
        HttpServletResponse httpRes = (HttpServletResponse) res;
        httpRes.setHeader("Cache-Control", "no-cache");
        long now = System.currentTimeMillis();
        httpRes.addDateHeader("Expires", now);
        httpRes.addDateHeader("Date", now);
        httpRes.addHeader("Pragma", "no-cache");
        chain.doFilter(req, res);
    }

    @Override
    public void destroy() {
    }
}