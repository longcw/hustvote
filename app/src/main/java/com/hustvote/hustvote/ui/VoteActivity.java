package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceItemBean;
import com.hustvote.hustvote.net.bean.VoteDetailBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.ui.adapter.ChoiceListAdapter;
import com.hustvote.hustvote.utils.C;
import com.hustvote.hustvote.utils.WebViewCSS;
import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnClick;
import com.lidroid.xutils.view.annotation.event.OnItemClick;
import com.lidroid.xutils.view.annotation.event.OnItemSelected;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Created by chenlong on 14-12-20.
 */
public class VoteActivity extends BaseVoteUI {

    private long start_voteid;
    private VoteDetailBean voteDetailBean;
    private List<ChoiceItemBean> choiceItemBeanList;
    private ChoiceListAdapter choiceListAdapter;

    private List<Integer> selected;

    private TextView title;
    private WebView introView;
    private Button submitButton;

    @ViewInject(R.id.vote_detail_choicelist)
    private ListView choiceListView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_vote);
        ViewUtils.inject(this);

        Intent intent = getIntent();
        start_voteid = intent.getLongExtra("start_voteid", -1);

        choiceItemBeanList = new ArrayList<>();
        selected = new ArrayList<>();
        choiceListAdapter = new ChoiceListAdapter(this, choiceItemBeanList, selected);

        //添加header和footer
        View header = getLayoutInflater().inflate(R.layout.activity_vote_header_webview, null);
        title = (TextView)header.findViewById(R.id.vote_detail_title_webview);
        introView = (WebView) header.findViewById(R.id.vote_detail_intro_webview);
        View footer = getLayoutInflater().inflate(R.layout.activity_vote_footer, null);
        submitButton = (Button) footer.findViewById(R.id.vote_submit_button);
        submitButton.setEnabled(false);

        choiceListView.addHeaderView(header);
        choiceListView.addFooterView(footer);
        choiceListView.setAdapter(choiceListAdapter);
        choiceListAdapter.setListView(choiceListView);
        doGetVoteDetail();
    }

    //联网获取
    private void doGetVoteDetail() {
        Map<String, String> params = new HashMap<>();
        params.put("vid", Long.toString(start_voteid));
        HustVoteRequest<VoteDetailBean> request = new HustVoteRequest<VoteDetailBean>(Request.Method.POST,
                C.Net.API.getVoteDetail, VoteDetailBean.class,
                params, new Response.Listener<VoteDetailBean>() {
            @Override
            public void onResponse(VoteDetailBean response) {
                voteDetailBean = response;
                //doGetVoteLog();
                doShowVoteDetail();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
                VoteActivity.this.finish();
            }
        });
        addToRequsetQueue(request);
    }

    private void doGetVoteLog() {
        //TODO votelog
        Map<String, String> params = new HashMap<>();
        params.put("vid", Long.toString(start_voteid));

        HustVoteRequest<VoteDetailBean> request = new HustVoteRequest<VoteDetailBean>(Request.Method.POST,
                C.Net.API.getVoteLog, VoteDetailBean.class,
                params, new Response.Listener<VoteDetailBean>() {
            @Override
            public void onResponse(VoteDetailBean response) {
                //允许投票
                submitButton.setEnabled(true);
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }

    //展示详情
    private void doShowVoteDetail() {
        title.setText(voteDetailBean.getContent().getTitle());
        WebViewCSS.openWebView(introView, voteDetailBean.getContent().getIntro());

        choiceItemBeanList.addAll(voteDetailBean.getChoices());
        choiceListAdapter.setMaxChoice(voteDetailBean.getContent().getChoice_max());

        choiceListAdapter.notifyDataSetChanged();
    }

    @OnClick(R.id.vote_submit_button)
    private void doSubmit() {

    }
}
