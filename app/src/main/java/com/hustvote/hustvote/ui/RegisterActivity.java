package com.hustvote.hustvote.ui;

import android.app.ActionBar;
import android.content.Intent;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.UserInfoBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.utils.C;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

public class RegisterActivity extends BaseUI {

    private EditText regEmail;
    private EditText nickname;
    private EditText regPassword;
    private EditText regPasswordAga;
    private TextView confirm;

    private Map<String, String> params;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setTitle(getString(R.string.register));
        setContentView(R.layout.activity_register);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        regEmail = (EditText)findViewById(R.id.regemail_edit);
        nickname = (EditText)findViewById(R.id.regnickname_edit);
        regPassword = (EditText)findViewById(R.id.regpassword_edit);
        regPasswordAga = (EditText)findViewById(R.id.regpasswordaga_edit);
        confirm = (TextView)findViewById(R.id.regButton);

        params = new HashMap<>();

        confirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                params.put("email", regEmail.getText().toString());
                params.put("password", regPassword.getText().toString());
                params.put("nickname", nickname.getText().toString());
                for(Map.Entry<String, String> item : params.entrySet()) {
                    if(item.getValue().isEmpty()) {
                        toast(getString(R.string.please_complete_info));
                        return;
                    }
                }
                if(!params.get("password").equals(regPasswordAga.getText().toString())) {
                    toast(getString(R.string.password_different));
                    return;
                }
                doRegister();
            }
        });
    }

    private void doRegister() {
        progressDialog.setMessage(getString(R.string.register_loading));
        progressDialog.show();
        HustVoteRequest<UserInfoBean> request = new HustVoteRequest<UserInfoBean>(Request.Method.POST,
                C.Net.API.Register, UserInfoBean.class, params, new Response.Listener<UserInfoBean>() {
            @Override
            public void onResponse(UserInfoBean response) {
                progressDialog.cancel();
                userInfo.setUserInfoBean(response);
                userInfo.setPassword(params.get("email"), params.get("password"));
                //startActivityAndFinish(new Intent(RegisterActivity.this, LoginActivity.class));
                finish();
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
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_register, menu);
        return true;
    }




}
