package com.hustvote.hustvote.net.bean;

import java.util.ArrayList;

/**
 * Created by chenlong on 15-1-1.
 */
public class CommentListBean {
    private ArrayList<CommentItemBean> commentlist;

    public CommentListBean() {
    }

    @Override
    public String toString() {
        return "CommentListBean{" +
                "commentlist=" + commentlist +
                '}';
    }

    public ArrayList<CommentItemBean> getCommentlist() {
        return commentlist;
    }

    public void setCommentlist(ArrayList<CommentItemBean> commentlist) {
        this.commentlist = commentlist;
    }
}
