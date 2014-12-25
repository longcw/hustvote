package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-24.
 */
public class ResultDataBean {
    private int choiceid;
    private int start_voteid;
    private int count;
    private String choice_name;

    public ResultDataBean() {
    }

    @Override
    public String toString() {
        return "ResultDataBean{" +
                "choiceid=" + choiceid +
                ", start_voteid=" + start_voteid +
                ", count=" + count +
                ", choice_name='" + choice_name + '\'' +
                '}';
    }

    public int getChoiceid() {
        return choiceid;
    }

    public void setChoiceid(int choiceid) {
        this.choiceid = choiceid;
    }

    public int getStart_voteid() {
        return start_voteid;
    }

    public void setStart_voteid(int start_voteid) {
        this.start_voteid = start_voteid;
    }

    public int getCount() {
        return count;
    }

    public void setCount(int count) {
        this.count = count;
    }

    public String getChoice_name() {
        return choice_name;
    }

    public void setChoice_name(String choice_name) {
        this.choice_name = choice_name;
    }
}
