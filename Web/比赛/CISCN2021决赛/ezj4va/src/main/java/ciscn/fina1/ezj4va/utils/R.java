package ciscn.fina1.ezj4va.utils;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.TypeReference;
import java.util.HashMap;
import java.util.Map;

public class R extends HashMap<String, Object> {
    private static final long serialVersionUID = 1L;

    public <T> T getData(String name, TypeReference<T> typeReference) {
        Object o = get(name);
        String s = JSON.toJSONString(o);
        return JSON.parseObject(s,typeReference);
    }

    public <T> T getData(TypeReference<T> typeReference) {
        Object o = get("data");
        String s = JSON.toJSONString(o);
        return JSON.parseObject(s,typeReference);
    }

    public R setData(Object data) {
        put("data",data);
        return this;
    }

    public R() {
        put("code", 0);
        put("msg", "success");
    }

    public static R error() {
        return error(500, "unknown error");
    }

    public static R error(String msg) {
        return error(500, msg);
    }

    public static R error(int code, String msg) {
        R r = new R();
        r.put("code", code);
        r.put("msg", msg);
        return r;
    }

    public static R ok(String msg) {
        R r = new R();
        r.put("msg", msg);
        return r;
    }

    public static R ok(Map<String, Object> map) {
        R r = new R();
        r.putAll(map);
        return r;
    }

    public static R ok() {
        return new R();
    }

    public R put(String key, Object value) {
        super.put(key, value);
        return this;
    }

    public int getCode(){
        return (int) get("code");
    }
}
