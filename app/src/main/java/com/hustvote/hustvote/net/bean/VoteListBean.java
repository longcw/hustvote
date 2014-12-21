package com.hustvote.hustvote.net.bean;

import java.util.ArrayList;

/**
 * Created by chenlong on 14-12-18.
 */
public class VoteListBean {
    private ArrayList<VoteItemBean> votelist;

    public VoteListBean() {

    }

    @Override
    public String toString() {
        return "VoteList{" +
                "votelist=" + votelist +
                '}';
    }

    public ArrayList<VoteItemBean> getVotelist() {
        return votelist;
    }

    public void setVotelist(ArrayList<VoteItemBean> votelist) {
        this.votelist = votelist;
    }
}
