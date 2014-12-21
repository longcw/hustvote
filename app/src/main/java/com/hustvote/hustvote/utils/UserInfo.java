package com.hustvote.hustvote.utils;

import android.content.Context;
import android.content.SharedPreferences;

import com.alibaba.fastjson.JSON;
import com.hustvote.hustvote.net.bean.UserInfoBean;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by chenlong on 14-12-19.
 */
public class UserInfo {

    private static final String PREFERENCE_NAME = "UserInfo";
    private static final String EMAIL = "email";
    private static final String PASSWORD = "password";
    private static final String USERINFOBEAN = "userinfobean";

    private static UserInfo ourInstance;

    private Context mCtx;
    private SharedPreferences preferences;


    private UserInfoBean userInfoBean = null;

    public static UserInfo getInstance(Context mCtx) {
        if(ourInstance == null) {
            ourInstance = new UserInfo(mCtx);
        }
        return ourInstance;
    }

    private UserInfo(Context mCtx) {
        this.mCtx = mCtx;
        preferences = mCtx.getSharedPreferences(PREFERENCE_NAME, Context.MODE_PRIVATE);
        String json = preferences.getString(USERINFOBEAN, "");
        if(!json.isEmpty()) {
            userInfoBean = JSON.parseObject(json, UserInfoBean.class);
        }
    }

    public void setPassword(String email, String password) {
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString(EMAIL, email);
        editor.putString(PASSWORD, password);
        editor.commit();
    }

    public Map<String, String> getPassword() {
        Map<String, String> params = new HashMap<>();
        params.put("email", preferences.getString(EMAIL, ""));
        params.put("password", preferences.getString(PASSWORD, ""));
        return params;
    }

    public void setUserInfoBean(UserInfoBean userInfoBean) {
        this.userInfoBean = userInfoBean;
        String json = JSON.toJSONString(userInfoBean);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString(USERINFOBEAN, json);
        editor.commit();
    }

    public UserInfoBean getUserInfoBean() {
        return userInfoBean;
    }

    public boolean isLogin() {
        return userInfoBean != null;
    }


}
