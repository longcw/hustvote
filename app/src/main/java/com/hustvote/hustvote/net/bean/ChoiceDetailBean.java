package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-21.
 */
public class ChoiceDetailBean {
    private int choiceid;
    private int start_voteid;
    private String choice_name;
    private String choice_intro;

    public ChoiceDetailBean() {
    }

    @Override
    public String toString() {
        return "ChoiceDetailBean{" +
                "choiceid=" + choiceid +
                ", start_voteid=" + start_voteid +
                ", choice_name='" + choice_name + '\'' +
                ", choice_intro='" + choice_intro + '\'' +
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

    public String getChoice_intro() {
        return choice_intro;
    }

    public void setChoice_intro(String choice_intro) {
        //TODO delete debug info
        this.choice_intro = choice_intro.replace("localhost", "10.42.0.1");
    }
}
