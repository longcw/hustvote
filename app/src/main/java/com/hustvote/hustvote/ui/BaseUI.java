package com.hustvote.hustvote.ui;

import android.app.ActivityManager;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.hustvote.hustvote.net.utils.NetworkUtils;
import com.hustvote.hustvote.utils.UserInfo;
import com.sina.push.PushManager;

import java.util.List;

/**
 * Created by chenlong on 14-12-18.
 */
public class BaseUI extends ActionBarActivity {
    private static final String NAME_PUSH_SERVICE = "com.sina.push.service.SinaPushService";

    protected RequestQueue requestQueue;
    protected PushManager pushManager;
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
        pushManager = PushManager.getInstance(getApplicationContext());
        userInfo = UserInfo.getInstance(this);

        if(!isPushRunning()) {
            startSinaPushService();
        }

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

    /**
     * push 是否正在后台运行
     */
    private boolean isPushRunning() {
        // TODO Auto-generated method stub
        ActivityManager mActivityManager = (ActivityManager) getSystemService(Context.ACTIVITY_SERVICE);
        List<ActivityManager.RunningServiceInfo> serviceList = mActivityManager
                .getRunningServices(Integer.MAX_VALUE);

        for (ActivityManager.RunningServiceInfo info : serviceList) {

            if (NAME_PUSH_SERVICE.equals(info.service.getClassName())) {
                return true;
            }
        }
        return false;
    }

    @Override
    protected void onResume() {
        super.onResume();

        //
		/*
		 * 点击率统计 if(pushSystemMethod != null){
		 * pushSystemMethod.sendClickFeedBack(getIntent()); }
		 */
    }

    /**
     * 开启SinaPush服务
     */
    private void startSinaPushService() {

        pushManager.initPushChannel("21592", "21592", "100", "100");
    }

    /**
     * 关闭SinaPush服务
     */
    private void stopSinaPushService() {

        pushManager.close();
    }

    /**
     * 刷新Push服务长连接
     */
    private void refreshConnection() {
        pushManager.refreshConnection();

    }
}
