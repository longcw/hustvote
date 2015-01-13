package com.hustvote.hustvote.ui.fragment;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
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
 * Created by chenlong on 15-1-11.
 */
public class ChoiceIntroFragment extends BaseFragment {

    public static final String ARG_CID = "arg_cid";

    private int cid;
    private WebView webView;

    private View rootView;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Bundle args = getArguments();
        cid = args.getInt(ARG_CID, -1);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        if(rootView != null) {
            ((ViewGroup) rootView.getParent()).removeView(rootView);
            Log.i("choiceIntroFragment", "rootView match");
            return rootView;
        }

        rootView = inflater.inflate(R.layout.fregment_choice_intro, container, false);

        webView = (WebView) rootView.findViewById(R.id.choice_intro_webview);
        doGetDetail();

        return rootView;
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
                WebViewCSS.openWebView(webView, response.getChoice_intro());

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }



}