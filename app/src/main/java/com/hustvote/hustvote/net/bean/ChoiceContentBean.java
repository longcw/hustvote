package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-20.
 */
public class ChoiceContentBean {
    private int start_voteid;
    private int uid;
    private String title;
    private String intro;
    private String summary;
    private String image;
    private int choice_max;
    private String start_time;
    private String end_time;
    private String create_time;
    private int is_completed;

    public ChoiceContentBean() {
    }

    @Override
    public String toString() {
        return "ChoiceContentBean{" +
                "start_voteid=" + start_voteid +
                ", uid=" + uid +
                ", title='" + title + '\'' +
                ", intro='" + intro + '\'' +
                ", summary='" + summary + '\'' +
                ", image='" + image + '\'' +
                ", choice_max=" + choice_max +
                ", start_time='" + start_time + '\'' +
                ", end_time='" + end_time + '\'' +
                ", create_time='" + create_time + '\'' +
                ", is_completed=" + is_completed +
                '}';
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

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getIntro() {
        return intro;
    }

    public void setIntro(String intro) {
        this.intro = intro;
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
        this.image = image;
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

    public int getIs_completed() {
        return is_completed;
    }

    public void setIs_completed(int is_completed) {
        this.is_completed = is_completed;
    }
}
