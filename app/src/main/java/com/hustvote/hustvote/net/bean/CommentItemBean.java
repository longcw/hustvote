package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 15-1-1.
 */
public class CommentItemBean {
    private String from_nickname;
    private String content;
    private int cid;
    private int from_uid;
    private int to_uid;
    private int vid;
    private String create_time;
    private int is_read;
    private String title;

    public CommentItemBean() {
    }

    @Override
    public String toString() {
        return "CommentItemBean{" +
                "from_nickname='" + from_nickname + '\'' +
                ", content='" + content + '\'' +
                ", cid=" + cid +
                ", from_uid=" + from_uid +
                ", to_uid=" + to_uid +
                ", vid=" + vid +
                ", create_time='" + create_time + '\'' +
                ", is_read=" + is_read +
                ", title='" + title + '\'' +
                '}';
    }

    public String getFrom_nickname() {
        return from_nickname;
    }

    public void setFrom_nickname(String from_nickname) {
        this.from_nickname = from_nickname;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public int getCid() {
        return cid;
    }

    public void setCid(int cid) {
        this.cid = cid;
    }

    public int getFrom_uid() {
        return from_uid;
    }

    public void setFrom_uid(int from_uid) {
        this.from_uid = from_uid;
    }

    public int getTo_uid() {
        return to_uid;
    }

    public void setTo_uid(int to_uid) {
        this.to_uid = to_uid;
    }

    public int getVid() {
        return vid;
    }

    public void setVid(int vid) {
        this.vid = vid;
    }

    public String getCreate_time() {
        return create_time;
    }

    public void setCreate_time(String create_time) {
        this.create_time = create_time;
    }

    public int getIs_read() {
        return is_read;
    }

    public void setIs_read(int is_read) {
        this.is_read = is_read;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }
}
