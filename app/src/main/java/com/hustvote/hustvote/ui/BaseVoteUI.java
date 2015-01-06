package com.hustvote.hustvote.ui;

import android.app.ActivityManager;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.EmptyBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.utils.C;
import com.sina.push.PushManager;

import java.util.List;

/**
 * Created by chenlong on 14-12-19.
 */
public class BaseVoteUI extends BaseUI {
    private static final String NAME_PUSH_SERVICE = "com.sina.push.service.SinaPushService";
    protected PushManager pushManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (!userInfo.isLogin()) {
            startActivityAndFinish(new Intent(BaseVoteUI.this, LoginActivity.class));
        }
        pushManager = PushManager.getInstance(getApplicationContext());
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_hall, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        switch (id) {
            case R.id.action_logout:
                HustVoteRequest<EmptyBean> request = new HustVoteRequest<EmptyBean>(Request.Method.GET, C.Net.API.Logout,
                        EmptyBean.class, new Response.Listener<EmptyBean>() {
                    @Override
                    public void onResponse(EmptyBean response) {
                        toast(getString(R.string.logout_ok));
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        toast(error.getMessage());
                    }
                });
                addToRequsetQueue(request);
                userInfo.setUserInfoBean(null);
                startActivityAndFinish(new Intent(BaseVoteUI.this, LoginActivity.class));
                return true;

            case R.id.action_scanner:
                Intent scannerIntent = new Intent(BaseVoteUI.this, QRCodeScannerActivtiy.class);
                startActivity(scannerIntent);
                return true;

            case R.id.action_notice:
                Intent noticeIntent = new Intent(BaseVoteUI.this, NoticeActivity.class);
                startActivity(noticeIntent);
                return true;
        }

        return super.onOptionsItemSelected(item);
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

        if(!isPushRunning()) {
            startSinaPushService();
        }

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

        pushManager.initPushChannel("21592", Integer.toString(userInfo.getUserInfoBean().getUid()), "100", "100");
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
