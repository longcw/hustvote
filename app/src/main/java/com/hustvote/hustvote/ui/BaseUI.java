package com.hustvote.hustvote.ui;


import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;

import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.hustvote.hustvote.net.utils.NetworkUtils;
import com.hustvote.hustvote.utils.UserInfo;

/**
 * Created by chenlong on 14-12-18.
 */
public class BaseUI extends ActionBarActivity {
    protected RequestQueue requestQueue;
    protected UserInfo userInfo;

    protected ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        progressDialog = new ProgressDialog(this){
            @Override
            protected void onStop() {
                //取消登录
                requestQueue.cancelAll(this);
            }
        };
        progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
        progressDialog.setCancelable(true);
        progressDialog.setCanceledOnTouchOutside(false);

        requestQueue = NetworkUtils.getInstance(getApplicationContext()).getRequestQueue();

        userInfo = UserInfo.getInstance(this);

    }

    public void toast(String msg) {
        Toast.makeText(getApplicationContext(), msg, Toast.LENGTH_SHORT).show();
    }

    @Override
    protected void onStop() {
        super.onStop();
        requestQueue.cancelAll(this);
    }


    protected void addToRequsetQueue(Request request) {
        request.setTag(this);
        requestQueue.add(request);
    }

    protected void startActivityAndFinish(Intent intent) {
        startActivity(intent);
        finish();
    }


}
