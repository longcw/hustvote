package com.hustvote.hustvote.net.bean;

/**
 * Created by chenlong on 14-12-20.
 */
public class ChoiceLimitBean {
    private int ip_address;
    private int captcha_need;
    private int cycle_time;
    private int code_need;
    private int limitid;
    private int start_voteid;
    private int email_limit;
    private String email_area;

    public ChoiceLimitBean() {
    }

    @Override
    public String toString() {
        return "ChoiceLimitBean{" +
                "ip_address=" + ip_address +
                ", captcha_need=" + captcha_need +
                ", cycle_time=" + cycle_time +
                ", code_need=" + code_need +
                ", limitid=" + limitid +
                ", start_voteid=" + start_voteid +
                ", email_limit=" + email_limit +
                ", email_area='" + email_area + '\'' +
                '}';
    }

    public int getIp_address() {
        return ip_address;
    }

    public void setIp_address(int ip_address) {
        this.ip_address = ip_address;
    }

    public int getCaptcha_need() {
        return captcha_need;
    }

    public void setCaptcha_need(int captcha_need) {
        this.captcha_need = captcha_need;
    }

    public int getCycle_time() {
        return cycle_time;
    }

    public void setCycle_time(int cycle_time) {
        this.cycle_time = cycle_time;
    }

    public int getCode_need() {
        return code_need;
    }

    public void setCode_need(int code_need) {
        this.code_need = code_need;
    }

    public int getLimitid() {
        return limitid;
    }

    public void setLimitid(int limitid) {
        this.limitid = limitid;
    }

    public int getStart_voteid() {
        return start_voteid;
    }

    public void setStart_voteid(int start_voteid) {
        this.start_voteid = start_voteid;
    }

    public int getEmail_limit() {
        return email_limit;
    }

    public void setEmail_limit(int email_limit) {
        this.email_limit = email_limit;
    }

    public String getEmail_area() {
        return email_area;
    }

    public void setEmail_area(String email_area) {
        this.email_area = email_area;
    }
}
