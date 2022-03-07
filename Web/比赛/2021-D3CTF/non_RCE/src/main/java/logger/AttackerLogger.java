/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package logger;

import java.util.logging.FileHandler;
import java.util.logging.Logger;
import java.util.logging.SimpleFormatter;

/**
 *
 * @author fantasyC4t
 * @version : AttackerLogger.java, v 0.1 2021年03月02日 8:44 下午 th1s Exp $
 */
public class AttackerLogger {
    public static Logger logger;

    public static Logger getLogger() {
        if(logger==null) {
            logger = Logger.getLogger("log");

            String logPath = "/opt/log.txt";
            try{
                FileHandler fileHandler = new FileHandler(logPath);
                fileHandler.setFormatter(new SimpleFormatter());
                logger.addHandler(fileHandler);
            }catch (Exception e) {
                e.printStackTrace();
            }

        }
        return logger;
    }
}