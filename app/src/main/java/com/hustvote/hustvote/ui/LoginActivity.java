package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.EmptyBean;
import com.hustvote.hustvote.net.bean.UserInfoBean;
import com.hustvote.hustvote.net.push.UpdateSAEPushToken;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.utils.C;

import java.util.HashMap;
import java.util.Map;


public class LoginActivity extends BaseUI {

    private EditText emailEdit;
    private EditText passwordEdit;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setTitle("HustVote");
        setContentView(R.layout.activity_login);

        emailEdit = (EditText) findViewById(R.id.email_edit);
        passwordEdit = (EditText) findViewById(R.id.password_edit);

        TextView registerButton = (TextView) findViewById(R.id.registerButton);
        registerButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(LoginActivity.this, RegisterActivity.class));
            }
        });

        TextView loginButton = (TextView) findViewById(R.id.loginButton);
        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String email = emailEdit.getText().toString();
                String password = passwordEdit.getText().toString();
                if(email.isEmpty() || password.isEmpty()) {
                    toast(getString(R.string.email_need));
                    return;
                }
                final Map<String, String> params = new HashMap<>();
                params.put("email", email);
                params.put("password", password);
                userInfo.setPassword(email, password);
                doSendEmptyRequest(params);

            }
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        if(userInfo.isLogin()) {
            verifyLogin();
        }
        Map<String, String> params = userInfo.getPassword();
        emailEdit.setText(params.get("email"));
        passwordEdit.setText(params.get("password"));

    }

    //发送空消息获取session
    private void doSendEmptyRequest(final Map<String, String> params) {

        HustVoteRequest<EmptyBean> request = new HustVoteRequest<EmptyBean>(Request.Method.GET, C.Net.API.Logout,
                EmptyBean.class, new Response.Listener<EmptyBean>() {
            @Override
            public void onResponse(EmptyBean response) {
                doLogin(params);
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast("登录失败："+error.getMessage());
            }
        });
        addToRequsetQueue(request);

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
                        //更新推送token
                        UpdateSAEPushToken.update(getApplicationContext(), response.getUid(), userInfo.getSAEPushToken());
                        startActivityAndFinish(new Intent(LoginActivity.this, HallFragmentActivity.class));
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

    private void verifyLogin() {
        Map<String, String> params = userInfo.getPassword();
        doSendEmptyRequest(params);
    }
}
