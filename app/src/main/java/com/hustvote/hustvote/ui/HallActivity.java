package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.VoteItemBean;
import com.hustvote.hustvote.net.bean.VoteListBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.ui.adapter.VoteListAdapter;
import com.hustvote.hustvote.utils.C;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import me.maxwin.view.XListView;

public class HallActivity extends BaseVoteUI implements XListView.IXListViewListener{

    private int page = 0;
    private List<VoteItemBean> voteItemList;
    private VoteListAdapter voteListAdapter;

    private XListView voteListView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setTitle(getString(R.string.hall));
        setContentView(R.layout.fregment_hall);

        voteListView = (XListView) findViewById(R.id.vote_list_view);

        voteListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int pos, long id) {
                if(pos > 0 && pos <= voteItemList.size()) {
                    Intent intent = new Intent(HallActivity.this, VoteActivity.class);
                    intent.putExtra("start_voteid", Long.toString(id));
                    startActivity(intent);
                }
            }
        });

        voteItemList = new ArrayList<>();
        voteListAdapter = new VoteListAdapter(this, voteItemList);
        voteListView.setAdapter(voteListAdapter);
        voteListView.setPullLoadEnable(true);
        voteListView.setPullRefreshEnable(true);
        voteListView.setXListViewListener(this);


        progressDialog.setMessage(getString(R.string.geting));
        //progressDialog.show();

        doGetVoteList();
    }

    private void doGetVoteList() {
        Map<String,String> params = new HashMap<>();
        params.put("page", Integer.toString(page));
        HustVoteRequest<VoteListBean> request = new HustVoteRequest<VoteListBean>(Request.Method.POST, C.Net.API.getVoteList,
                VoteListBean.class, params,
                new Response.Listener<VoteListBean>() {
                    @Override
                    public void onResponse(VoteListBean response) {
                        onLoad();
                        page++;
                        voteItemList.addAll(response.getVotelist());
                        voteListAdapter.notifyDataSetChanged();
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
        if(voteItemList.isEmpty()) {
            doGetVoteList();
            return;
        }
        String last_time = voteItemList.get(0).getCreate_time();
        Map<String,String> params = new HashMap<>();
        params.put("last_time", last_time);
        HustVoteRequest<VoteListBean> request = new HustVoteRequest<VoteListBean>(Request.Method.POST, C.Net.API.getNewVote,
                VoteListBean.class, params,
                new Response.Listener<VoteListBean>() {
                    @Override
                    public void onResponse(VoteListBean response) {
                        onLoad();
                        voteItemList.addAll(0, response.getVotelist());
                        voteListAdapter.notifyDataSetChanged();
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

    @Override
    public void onRefresh() {
        doRefresh();

    }

    @Override
    public void onLoadMore() {
        doGetVoteList();
    }

    private void onLoad() {
        voteListView.stopRefresh();
        voteListView.stopLoadMore();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        voteListView.setRefreshTime(df.format(new Date()));
    }

}
