package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-18.
 */
public class VoteItemBean {

    private int start_voteid;
    private int uid;
    private int is_completed;
    private int log_count;
    private String title;
    private String summary;
    private String image;
    private int choice_max;
    private String start_time;
    private String end_time;
    private String create_time;

    public VoteItemBean() {

    }

    @Override
    public String toString() {
        return "VoteItemBean{" +
                "start_voteid=" + start_voteid +
                ", uid=" + uid +
                ", is_completed=" + is_completed +
                ", log_count=" + log_count +
                ", title='" + title + '\'' +
                ", summary='" + summary + '\'' +
                ", image='" + image + '\'' +
                ", choice_max=" + choice_max +
                ", start_time='" + start_time + '\'' +
                ", end_time='" + end_time + '\'' +
                ", create_time='" + create_time + '\'' +
                '}';
    }

    public int getLog_count() {
        return log_count;
    }

    public void setLog_count(int log_count) {
        this.log_count = log_count;
    }

    public int getStart_voteid() {
        return start_voteid;
    }

    public void setStart_voteid(int start_voteid) {
        this.start_voteid = start_voteid;
    }

    public int getUid() {
        return uid;
    }

    public void setUid(int uid) {
        this.uid = uid;
    }

    public int getIs_completed() {
        return is_completed;
    }

    public void setIs_completed(int is_completed) {
        this.is_completed = is_completed;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getSummary() {
        return summary;
    }

    public void setSummary(String summary) {
        this.summary = summary;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        //TODO 删除replace
        //本地调试用
        this.image = image.replace("localhost", "10.42.0.1");
    }

    public int getChoice_max() {
        return choice_max;
    }

    public void setChoice_max(int choice_max) {
        this.choice_max = choice_max;
    }

    public String getStart_time() {
        return start_time;
    }

    public void setStart_time(String start_time) {
        this.start_time = start_time;
    }

    public String getEnd_time() {
        return end_time;
    }

    public void setEnd_time(String end_time) {
        this.end_time = end_time;
    }

    public String getCreate_time() {
        return create_time;
    }

    public void setCreate_time(String create_time) {
        this.create_time = create_time;
    }
}
