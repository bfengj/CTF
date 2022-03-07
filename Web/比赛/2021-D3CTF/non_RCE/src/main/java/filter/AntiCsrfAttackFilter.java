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
 * @version : CsrfAttackFilter.java, v 0.1 2021年03月01日 8:36 下午 fantasyC4t Exp $
 */
@WebFilter(
        filterName = "AntiCsrfAttackFilter",
        urlPatterns = {"/*"}
)
public class AntiCsrfAttackFilter implements Filter {
    @Override
    public void init(FilterConfig filterConfig) throws ServletException {

    }

    @Override
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain)
            throws IOException, ServletException {
        // TODO: We should check the referer to prevent csrf attacking at the next version.
        filterChain.doFilter(servletRequest, servletResponse);
    }

    @Override
    public void destroy() {

    }
}