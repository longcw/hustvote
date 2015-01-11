package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.webkit.WebView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceDetailBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.utils.C;
import com.hustvote.hustvote.utils.WebViewCSS;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by chenlong on 14-12-21.
 */
public class ChoiceIntroActivity extends BaseVoteUI {
    private int cid;

    private WebView webView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setTitle(getString(R.string.choice_intro));
        setContentView(R.layout.fregment_choice_intro);
        webView = (WebView) findViewById(R.id.choice_intro_webview);

        Intent intent = getIntent();
        cid = intent.getIntExtra("cid", -1);
        doGetDetail();
    }

    private void doGetDetail() {
        Map<String, String> params = new HashMap<>();
        params.put("cid", Integer.toString(cid));
        HustVoteRequest<ChoiceDetailBean> request = new HustVoteRequest<ChoiceDetailBean>(Request.Method.POST,
                C.Net.API.getChoiceDetail, ChoiceDetailBean.class,
                params, new Response.Listener<ChoiceDetailBean>() {
            @Override
            public void onResponse(ChoiceDetailBean response) {
                if(response.getChoice_intro().isEmpty()) {
                    response.setChoice_intro(response.getChoice_name());
                }
                setTitle(response.getChoice_name() + " --" + getString(R.string.choice_intro));
                WebViewCSS.openWebView(webView, response.getChoice_intro());

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
                finish();
            }
        });
        addToRequsetQueue(request);
    }


}
