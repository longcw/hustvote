package com.hustvote.hustvote.net.bean;

import java.io.Serializable;

/**
 * Created by chenlong on 14-12-17.
 */
public class UserInfoBean implements Serializable {
    private String groupname;
    private int uid;
    private String email;
    private int groupid;
    private String nickname;
    private int is_verified;
    private String verify_token;
    private String exp_time;
    private String createtime;

    public UserInfoBean() {

    }

    @Override
    public String toString() {
        return "UserInfoBean{" +
                "groupname='" + groupname + '\'' +
                ", uid=" + uid +
                ", email='" + email + '\'' +
                ", groupid=" + groupid +
                ", nickname='" + nickname + '\'' +
                ", is_verified=" + is_verified +
                ", verify_token='" + verify_token + '\'' +
                ", exp_time='" + exp_time + '\'' +
                ", createtime='" + createtime + '\'' +
                '}';
    }

    public String getGroupname() {
        return groupname;
    }

    public void setGroupname(String groupname) {
        this.groupname = groupname;
    }

    public String getCreatetime() {
        return createtime;
    }

    public void setCreatetime(String createtime) {
        this.createtime = createtime;
    }

    public String getExp_time() {
        return exp_time;
    }

    public void setExp_time(String exp_time) {
        this.exp_time = exp_time;
    }

    public String getVerify_token() {
        return verify_token;
    }

    public void setVerify_token(String verify_token) {
        this.verify_token = verify_token;
    }

    public int getIs_verified() {
        return is_verified;
    }

    public void setIs_verified(int is_verified) {
        this.is_verified = is_verified;
    }

    public String getNickname() {
        return nickname;
    }

    public void setNickname(String nickname) {
        this.nickname = nickname;
    }

    public int getGroupid() {
        return groupid;
    }

    public void setGroupid(int groupid) {
        this.groupid = groupid;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public int getUid() {
        return uid;
    }

    public void setUid(int uid) {
        this.uid = uid;
    }
}
