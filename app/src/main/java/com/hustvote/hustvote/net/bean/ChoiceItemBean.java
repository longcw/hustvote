package com.hustvote.hustvote.net.bean;

import java.io.Serializable;

/**
 * Created by chenlong on 14-12-20.
 */
public class ChoiceItemBean implements Serializable{

    public boolean isSelected = false;
    private int choiceid;
    private int start_voteid;
    private String choice_name;

    public ChoiceItemBean() {
    }

    @Override
    public String toString() {
        return "ChoiceItemBean{" +
                "choiceid=" + choiceid +
                ", start_voteid=" + start_voteid +
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

    public String getChoice_name() {
        return choice_name;
    }

    public void setChoice_name(String choice_name) {
        this.choice_name = choice_name;
    }
}
