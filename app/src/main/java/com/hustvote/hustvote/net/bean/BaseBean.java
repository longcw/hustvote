package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-18.
 */
public class BaseBean {
    private String sid;

    public BaseBean() {
    }

    public String getSid() {
        return sid;
    }

    public void setSid(String sid) {
        this.sid = sid;
    }

    @Override
    public String toString() {
        return "BaseBean{" +
                "sid='" + sid + '\'' +
                '}';
    }
}
