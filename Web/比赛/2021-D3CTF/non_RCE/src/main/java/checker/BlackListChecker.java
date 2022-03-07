/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */
package checker;

/**
 *
 * @author fantasyC4t
 * @version : BlackListChecker.java, v 0.1 2021年03月01日 9:59 下午 fantasyC4t Exp $
 */
/**
 * Alipay.com Inc. Copyright (c) 2004-2021 All Rights Reserved.
 */

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 *
 * @author fantasyC4t
 * @version : BlackListChecker.java, v 0.1 2021年03月01日 9:59 下午 fantasyC4t Exp $
 */
public class BlackListChecker {
    public static BlackListChecker blackListChecker;

    public String[] blackList = new String[] {"%", "autoDeserialize"};

    public DataMap checkDataMap;

    public volatile String toBeChecked;

    public BlackListChecker() {
        List<String> jdbcBlackList = new ArrayList<>();
        jdbcBlackList.add(blackList[0]);
        jdbcBlackList.add(blackList[1]);

        Map<String, List<String>> checkMap = new HashMap<>();
        checkMap.put("jdbc", jdbcBlackList);

        this.checkDataMap = new DataMap(checkMap);
    }

    public void setToBeChecked(String s) {
        this.toBeChecked = s;
    }

    public static BlackListChecker getBlackListChecker() {
        if (blackListChecker == null){
            blackListChecker = new BlackListChecker();
        }
        return blackListChecker;
    }

    public static boolean check(String s) {
        BlackListChecker blackListChecker = getBlackListChecker();
        blackListChecker.setToBeChecked(s);
        return blackListChecker.doCheck();
    }

    public boolean doCheck() {
        for (String s : blackList) {
            if (toBeChecked.contains(s)) {
                return false;
            }
        }
        return true;
    }

    public void addCheckList(String key, List checkList){
        if(this.checkDataMap.containsKey(key)){
            List list = (List) this.checkDataMap.get(key);
            if (!list.containsAll(checkList)) {
                list.add(checkList);
            }
        }else {
            this.checkDataMap.put(key, checkList);
        }
    }

    public void removeCheckList(String key){
        this.checkDataMap.remove(key);
    }
}
