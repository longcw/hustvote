package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-19.
 */
public class EmptyBean {
    private Boolean empty;

    public EmptyBean() {

    }

    public Boolean getEmpty() {
        return empty;
    }

    public void setEmpty(Boolean empty) {
        this.empty = empty;
    }

    @Override
    public String toString() {
        return "EmptyBean{" +
                "empty=" + empty +
                '}';
    }
}
