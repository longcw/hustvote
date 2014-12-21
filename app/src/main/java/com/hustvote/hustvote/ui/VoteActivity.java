package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.text.Html;
import android.text.Spanned;
import android.text.method.ScrollingMovementMethod;
import android.view.View;
import android.webkit.WebView;
import android.widget.ListView;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceItemBean;
import com.hustvote.hustvote.net.bean.VoteDetailBean;
import com.hustvote.hustvote.net.bean.VoteItemBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.ui.adapter.ChoiceListAdapter;
import com.hustvote.hustvote.utils.C;
import com.hustvote.hustvote.utils.VolleyImageGetter;
import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;

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

    private TextView title;
    private TextView introView;

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
        choiceListAdapter = new ChoiceListAdapter(this, choiceItemBeanList);

        View header = getLayoutInflater().inflate(R.layout.activity_vote_header, null);
        title = (TextView)header.findViewById(R.id.vote_detail_title);
        introView = (TextView) header.findViewById(R.id.vote_detail_intro);
        choiceListView.addHeaderView(header);

        choiceListView.setAdapter(choiceListAdapter);



//        introView.getSettings().setJavaScriptEnabled(false);
//        introView.getSettings().setLoadWithOverviewMode(true);
//        introView.getSettings().setUseWideViewPort(true);
        doGetVoteDetail();
    }

    private void doGetVoteDetail() {
        Map<String, String> params = new HashMap<>();
        params.put("vid", Long.toString(start_voteid));
        HustVoteRequest<VoteDetailBean> request = new HustVoteRequest<VoteDetailBean>(Request.Method.POST,
                C.Net.API.getVoteDetail, VoteDetailBean.class,
                params, new Response.Listener<VoteDetailBean>() {
            @Override
            public void onResponse(VoteDetailBean response) {
                voteDetailBean = response;
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

    private void doShowVoteDetail() {
        title.setText(voteDetailBean.getContent().getTitle());
        Spanned spanned = Html.fromHtml(voteDetailBean.getContent().getIntro(),
                new VolleyImageGetter(VoteActivity.this, introView), null);
        introView.setText(spanned);
//        introView.loadData(voteDetailBean.getContent().getIntro(), "text/html; charset=utf-8", "UTF-8");

        choiceItemBeanList.addAll(voteDetailBean.getChoices());
        choiceListAdapter.notifyDataSetChanged();

    }
}
