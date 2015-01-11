package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.CommentItemBean;
import com.hustvote.hustvote.net.bean.CommentListBean;
import com.hustvote.hustvote.net.bean.EmptyBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.ui.adapter.CommentListAdapter;
import com.hustvote.hustvote.utils.C;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import me.maxwin.view.XListView;

public class ReviewActivity extends BaseVoteUI implements XListView.IXListViewListener {

    private int page = 0;
    private int offset = 0;
    private String vid;
    private String vote_uid;
    private List<CommentItemBean> commentItemList;
    private CommentListAdapter commentListAdapter;

    private String content;
    private String to_uid;

    private XListView commentListView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_review);
        setTitle("查看评论");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        Intent intent = getIntent();
        vid = intent.getStringExtra("vid");
        vote_uid = intent.getStringExtra("vote_uid");
        if(vid == null || vote_uid == null) {
            finish();
            return;
        }

        commentListView = (XListView) findViewById(R.id.review_list_view);

        commentItemList = new ArrayList<>();
        commentListAdapter = new CommentListAdapter(this, commentItemList);
        commentListView.setAdapter(commentListAdapter);
        commentListView.setPullLoadEnable(true);
        commentListView.setPullRefreshEnable(true);
        commentListView.setXListViewListener(this);


        progressDialog.setMessage(getString(R.string.geting));
        //progressDialog.show();

        doGetCommentList();
    }

    private void doGetCommentList() {
        Map<String,String> params = new HashMap<>();
        params.put("page", Integer.toString(page));
        params.put("vid", vid);
        params.put("offset", Integer.toString(offset));
        HustVoteRequest<CommentListBean> request = new HustVoteRequest<>(Request.Method.POST, C.Net.API.getCommentByVote,
                CommentListBean.class, params,
                new Response.Listener<CommentListBean>() {
                    @Override
                    public void onResponse(CommentListBean response) {
                        onLoad();
                        page++;
                        commentItemList.addAll(response.getCommentlist());
                        commentListAdapter.notifyDataSetChanged();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onLoad();
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }

    private void doRefresh() {
        if(commentItemList.isEmpty()) {
            doGetCommentList();
            return;
        }
        String last_time = commentItemList.get(0).getCreate_time();
        Map<String,String> params = new HashMap<>();
        params.put("last_time", last_time);
        params.put("vid", vid);
        HustVoteRequest<CommentListBean> request = new HustVoteRequest<CommentListBean>(Request.Method.POST, C.Net.API.getNewCommentByVote,
                CommentListBean.class, params,
                new Response.Listener<CommentListBean>() {
                    @Override
                    public void onResponse(CommentListBean response) {
                        onLoad();
                        commentItemList.addAll(0, response.getCommentlist());
                        commentListAdapter.notifyDataSetChanged();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                onLoad();
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }


    private void doCommit() {
        if(to_uid == null || to_uid.isEmpty()) {
            to_uid = vote_uid;
        }

        progressDialog.setMessage("提交中");
        progressDialog.show();
        Map<String,String> params = new HashMap<>();
        params.put("from_uid", Integer.toString(userInfo.getUserInfoBean().getUid()));
        params.put("vid", vid);
        params.put("to_uid", to_uid);
        params.put("content", content);
        HustVoteRequest<CommentItemBean> request = new HustVoteRequest<>(Request.Method.POST, C.Net.API.addComment,
                CommentItemBean.class, params,
                new Response.Listener<CommentItemBean>() {
                    @Override
                    public void onResponse(CommentItemBean response) {
                        offset++;
                        commentItemList.add(0, response);
                        commentListAdapter.notifyDataSetChanged();
                        progressDialog.cancel();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
                progressDialog.cancel();
            }
        });
        addToRequsetQueue(request);
    }

    @Override
    public void onRefresh() {
        doRefresh();

    }

    @Override
    public void onLoadMore() {
        doGetCommentList();
    }

    private void onLoad() {
        commentListView.stopRefresh();
        commentListView.stopLoadMore();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        commentListView.setRefreshTime(df.format(new Date()));
    }
}
