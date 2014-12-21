package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.UserInfoBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.utils.C;
import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnClick;

import java.util.HashMap;
import java.util.Map;


public class LoginActivity extends BaseUI {

    @ViewInject(R.id.email_edit)
    private EditText emailEdit;

    @ViewInject(R.id.password_edit)
    private EditText passwordEdit;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        ViewUtils.inject(this);

        if(userInfo.isLogin()) {
            verfiyLogin();
        }
        Map<String, String> params = userInfo.getPassword();
        emailEdit.setText(params.get("email"));
        passwordEdit.setText(params.get("password"));

    }

    @OnClick(R.id.loginButton)
    private void onClickLoginButton(View view) {
        String email = emailEdit.getText().toString();
        String password = passwordEdit.getText().toString();
        if(email.isEmpty() || password.isEmpty()) {
            toast(getString(R.string.email_need));
            return;
        }

        Map<String, String> params = new HashMap<>();
        params.put("email", email);
        params.put("password", password);
        userInfo.setPassword(email, password);
        doLogin(params);

    }

    private void doLogin(Map<String, String> params) {
        progressDialog.setMessage(getString(R.string.logining));
        progressDialog.show();
        HustVoteRequest<UserInfoBean> request = new HustVoteRequest<UserInfoBean>(Request.Method.POST, C.Net.API.Login,
                UserInfoBean.class, params,
                new Response.Listener<UserInfoBean>() {
                    @Override
                    public void onResponse(UserInfoBean response) {
                        progressDialog.cancel();
                        //toast(getString(R.string.login_ok));
                        userInfo.setUserInfoBean(response);
                        startActivityAndFinish(new Intent(LoginActivity.this, HallActivity.class));
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.cancel();
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }

    private void verfiyLogin() {
        Map<String, String> params = userInfo.getPassword();
        doLogin(params);
    }
}
