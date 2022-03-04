package com.web.simplejava.model;

import java.io.Serializable;



public class Flag implements Serializable {
    public String flag;
    public static final Flag flagInstance;
    static {
        flagInstance = new Flag(System.getProperty("flag"));
    }
    public boolean create = true;

    public Flag(String flag) {
        this.flag = flag;
    }

    public Flag() {
    }


    public Flag getFlagInstance(Flag flagTemplate) throws Exception {
            if (create){
            if (!flagInstance.flag.startsWith(flagTemplate.flag)){
                throw new Exception("flag not valid");
            } else {
                return flagTemplate;
            }
        } else {
            return flagInstance;
        }
    }


    private Object readResolve() throws Exception{
        return getFlagInstance(this);
    }
}
