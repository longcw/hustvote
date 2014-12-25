package com.hustvote.hustvote.net.bean;

import java.util.List;

/**
 * Created by chenlong on 14-12-20.
 */
public class VoteDetailBean {

    private List<ChoiceItemBean> choices;
    private ChoiceContentBean content;
    private ChoiceLimitBean limit;

    private String logtype;
    private String logmsg;

    public VoteDetailBean() {

    }

    @Override
    public String toString() {
        return "VoteDetailBean{" +
                "choices=" + choices +
                ", content=" + content +
                ", limit=" + limit +
                ", logtype='" + logtype + '\'' +
                ", logmsg='" + logmsg + '\'' +
                '}';
    }

    public List<ChoiceItemBean> getChoices() {
        return choices;
    }

    public void setChoices(List<ChoiceItemBean> choices) {
        this.choices = choices;
    }

    public ChoiceContentBean getContent() {
        return content;
    }

    public void setContent(ChoiceContentBean content) {
        this.content = content;
    }

    public ChoiceLimitBean getLimit() {
        return limit;
    }

    public void setLimit(ChoiceLimitBean limit) {
        this.limit = limit;
    }

    public String getLogtype() {
        return logtype;
    }

    public void setLogtype(String logtype) {
        this.logtype = logtype;
    }

    public String getLogmsg() {
        return logmsg;
    }

    public void setLogmsg(String logmsg) {
        this.logmsg = logmsg;
    }
}
