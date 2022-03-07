/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package filter;

import javax.servlet.*;
import javax.servlet.annotation.WebFilter;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

/**
 *
 * @author fantasyC4t
 * @version : SecurityHeadersFilter.java, v 0.1 2021年03月01日 9:08 下午 fantasyC4t Exp $
 */
@WebFilter(
        filterName = "AntiXssAttackFilter",
        urlPatterns = {"/*"}
)
public class AntiXssAttackFilter implements Filter {
    private static final String       DEFAULT_HSTS = "max-age=127800";
    private static final String       DEFAULT_CSP = "script-src 'self'";
    private              FilterConfig filterConfig;

    @Override
    public void init(FilterConfig filterConfig) throws ServletException {
        this.filterConfig = filterConfig;
    }

    @Override
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain)
            throws IOException, ServletException {
        HttpServletResponse httpResponse = (HttpServletResponse) response;
        httpResponse.addHeader("X-Content-Type-Options", "nosniff");
        httpResponse.addHeader("X-XSS-Protection", "1; mode=block");
        httpResponse.addHeader("Strict-Transport-Security", DEFAULT_HSTS);
        httpResponse.addHeader("Content-Security-Policy", DEFAULT_CSP);
        chain.doFilter(request, response);
    }

    @Override
    public void destroy() {
    }
}