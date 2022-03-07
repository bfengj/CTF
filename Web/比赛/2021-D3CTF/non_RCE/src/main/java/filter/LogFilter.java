/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package filter;

import javax.servlet.*;
import javax.servlet.annotation.WebFilter;
import java.io.IOException;

/**
 *
 * @author fantasyC4t
 * @version : LogFilter.java, v 0.1 2021年03月01日 8:49 下午 fantasyC4t Exp $
 */
@WebFilter(
        filterName = "LogFilter",
        urlPatterns = {"/*"}
)
public class LogFilter implements Filter {
    @Override
    public void init(FilterConfig filterConfig) throws ServletException {

    }

    @Override
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain)
            throws IOException, ServletException {
        // TODO: There are so many attackers now, so we should logging user's ip, user-agent,
        //  request url and request parameters here at the next version.
        filterChain.doFilter(servletRequest, servletResponse);
    }

    @Override
    public void destroy() {

    }
}