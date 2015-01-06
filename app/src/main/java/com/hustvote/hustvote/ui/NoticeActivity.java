package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ListView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.CommentItemBean;
import com.hustvote.hustvote.net.bean.CommentListBean;
import com.hustvote.hustvote.net.bean.VoteItemBean;
import com.hustvote.hustvote.net.bean.VoteListBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.ui.adapter.NoticeListAdapter;
import com.hustvote.hustvote.ui.adapter.VoteListAdapter;
import com.hustvote.hustvote.utils.C;
import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnItemClick;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import me.maxwin.view.XListView;

/**
 * Created by chenlong on 15-1-1.
 */
public class NoticeActivity extends BaseVoteUI implements XListView.IXListViewListener {

    private int page = 0;
    private List<CommentItemBean> commentItemList;
    private NoticeListAdapter noticeListAdapter;

    @ViewInject(R.id.notice_list_view)
    private XListView noticeListView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_notice);
        ViewUtils.inject(this);
        commentItemList = new ArrayList<>();
        noticeListAdapter = new NoticeListAdapter(this, commentItemList);
        noticeListView.setAdapter(noticeListAdapter);
        noticeListView.setPullLoadEnable(true);
        noticeListView.setPullRefreshEnable(true);
        noticeListView.setXListViewListener(this);


        progressDialog.setMessage(getString(R.string.geting));
        //progressDialog.show();

        doGetNoticeList();
    }

    private void doGetNoticeList() {
        Map<String,String> params = new HashMap<>();
        params.put("page", Integer.toString(page));
        HustVoteRequest<CommentListBean> request = new HustVoteRequest<>(Request.Method.POST, C.Net.API.getCommentList,
                CommentListBean.class, params,
                new Response.Listener<CommentListBean>() {
                    @Override
                    public void onResponse(CommentListBean response) {
                        onLoad();
                        page++;
                        commentItemList.addAll(response.getCommentlist());
                        noticeListAdapter.notifyDataSetChanged();
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
            doGetNoticeList();
            return;
        }
        String last_time = commentItemList.get(0).getCreate_time();
        Map<String,String> params = new HashMap<>();
        params.put("last_time", last_time);
        HustVoteRequest<CommentListBean> request = new HustVoteRequest<CommentListBean>(Request.Method.POST, C.Net.API.getNewComment,
                CommentListBean.class, params,
                new Response.Listener<CommentListBean>() {
                    @Override
                    public void onResponse(CommentListBean response) {
                        onLoad();
                        commentItemList.addAll(0, response.getCommentlist());
                        noticeListAdapter.notifyDataSetChanged();
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

    @OnItemClick(R.id.notice_list_view)
    private void onItemClickVoteListView(ListView parent, View view, int pos, long id) {
        if(pos > 0 && pos <= commentItemList.size()) {
            int vid = commentItemList.get(pos - 1).getVid();
            Intent intent = new Intent(NoticeActivity.this, VoteActivity.class);
            intent.putExtra("start_voteid", Integer.toString(vid));
            startActivity(intent);
        }
    }

    @Override
    public void onRefresh() {
        doRefresh();

    }

    @Override
    public void onLoadMore() {
        doGetNoticeList();
    }

    private void onLoad() {
        noticeListView.stopRefresh();
        noticeListView.stopLoadMore();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        noticeListView.setRefreshTime(df.format(new Date()));
    }

}
