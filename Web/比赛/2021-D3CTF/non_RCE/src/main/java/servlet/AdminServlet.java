/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package servlet;

import checker.BlackListChecker;
import logger.AttackerLogger;

import javax.servlet.ServletException;
import javax.servlet.ServletOutputStream;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.sql.DriverManager;
import java.util.logging.Level;

/**
 *
 * @author fantasyC4t
 * @version : AdminServlet.java, v 0.1 2021年03月01日 4:47 下午 fantasyC4t Exp $
 */
@WebServlet(
        name = "AdminServlet",
        urlPatterns = {"/admin/*"}
)
public class AdminServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        if (req.getRequestURI().startsWith("/admin/importData")) {
            AttackerLogger.getLogger().log(Level.INFO,req.getRemoteAddr()+" phase2, requestURI="+req.getRequestURI());
            String databaseType = req.getParameter("databaseType");
            String jdbcUrl = req.getParameter("jdbcUrl");

            if (databaseType == null || jdbcUrl == null) {
                outputResponse(resp, "The parameter databaseType or jdbcUrl can not be null!");
                return;
            }

            if (!BlackListChecker.check(jdbcUrl)) {
                System.out.println("detect attacking!");
                resp.sendError(HttpServletResponse.SC_BAD_REQUEST, "The jdbc url contains illegal character!");
                return;
            }
            try {
                if (("mysql").equals(databaseType)) {
                    AttackerLogger.getLogger().log(Level.INFO,req.getRemoteAddr()+" phase3, jdbcUrl="+jdbcUrl);
                    DriverManager.setLoginTimeout(5);
                    Class.forName("com.mysql.jdbc.Driver");
                    DriverManager.getConnection(jdbcUrl);
                    outputResponse(resp, "ok");
                }
            } catch (Exception e) {
                outputResponse(resp, "The jdbc url " + jdbcUrl + " connects error.");
            }
        }
    }

    private void outputResponse(HttpServletResponse resp, String output) throws IOException {
        ServletOutputStream out = resp.getOutputStream();
        out.write(output.getBytes());
        out.flush();
        out.close();
    }
}