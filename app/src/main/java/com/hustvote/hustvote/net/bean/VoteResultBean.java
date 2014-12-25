package com.hustvote.hustvote.net.bean;

import java.util.List;

/**
 * Created by chenlong on 14-12-24.
 */
public class VoteResultBean {
    private List<ResultDataBean> resultdata;
    private String title;

    public VoteResultBean() {
    }

    @Override
    public String toString() {
        return "VoteResultBean{" +
                "resultdata=" + resultdata +
                ", title='" + title + '\'' +
                '}';
    }

    public List<ResultDataBean> getResultdata() {
        return resultdata;
    }

    public void setResultdata(List<ResultDataBean> resultdata) {
        this.resultdata = resultdata;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }
}
