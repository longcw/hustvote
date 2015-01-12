package com.hustvote.hustvote.ui.fragment;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.VoteItemBean;
import com.hustvote.hustvote.net.bean.VoteListBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.ui.VoteActivity;
import com.hustvote.hustvote.ui.adapter.VoteListAdapter;
import com.hustvote.hustvote.utils.C;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import me.maxwin.view.XListView;

/**
 * Created by chenlong on 15-1-11.
 */
public class HallFragment extends BaseFragment implements XListView.IXListViewListener {

    public static final String ARG_SECTION_NUMBER = "ARG_SECTION_NUMBER";

    //排序方式 0-时间， 1-热门
    private int section;

    private int offset = 0;
    private int page = 0;
    private List<VoteItemBean> voteItemList;
    private VoteListAdapter voteListAdapter;

    //@ViewInject(R.id.vote_list_view)
    private XListView voteListView;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Bundle args = getArguments();
        section = args.getInt(ARG_SECTION_NUMBER, 0);
        voteItemList = new ArrayList<>();

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fregment_hall, container, false);
        voteListView = (XListView)rootView.findViewById(R.id.vote_list_view);

        voteListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int pos, long id) {
                if(pos > 0 && pos <= voteItemList.size()) {
                    Intent intent = new Intent(HallFragment.this.getActivity(), VoteActivity.class);
                    intent.putExtra("start_voteid", Long.toString(id));
                    startActivity(intent);
                }
            }
        });

        voteListAdapter = new VoteListAdapter(this.getActivity(), voteItemList);
        voteListView.setAdapter(voteListAdapter);
        voteListView.setPullLoadEnable(true);
        voteListView.setPullRefreshEnable(true);
        voteListView.setXListViewListener(this);

        doGetVoteList();
        return rootView;
    }

    private void doGetVoteList() {
        Map<String,String> params = new HashMap<>();
        params.put("page", Integer.toString(page));
        params.put("is_hot", Integer.toString(section));
        params.put("offset", Integer.toString(offset));
        HustVoteRequest<VoteListBean> request = new HustVoteRequest<VoteListBean>(Request.Method.POST, C.Net.API.getVoteList,
                VoteListBean.class, params,
                new Response.Listener<VoteListBean>() {
                    @Override
                    public void onResponse(VoteListBean response) {
                        onLoad();
                        //热门投票
                        if(section == 1 && page == 0) {
                            voteItemList.clear();
                        }
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
        if(voteItemList.isEmpty() || section == 1) {
            page = 0;
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
                        offset += response.getVotelist().size();
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
