package com.hustvote.hustvote.net.bean;

import java.util.List;

/**
 * Created by chenlong on 14-12-20.
 */
public class VoteDetailBean {

    List<ChoiceItemBean> choices;
    ChoiceContentBean content;
    ChoiceLimitBean limit;

    public VoteDetailBean() {

    }

    @Override
    public String toString() {
        return "VoteDetailBean{" +
                "choices=" + choices +
                ", content=" + content +
                ", limit=" + limit +
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
}
