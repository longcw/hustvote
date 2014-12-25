package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.github.mikephil.charting.charts.BarChart;
import com.github.mikephil.charting.charts.RadarChart;
import com.github.mikephil.charting.data.BarData;
import com.github.mikephil.charting.data.BarDataSet;
import com.github.mikephil.charting.data.BarEntry;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.RadarDataSet;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ResultDataBean;
import com.hustvote.hustvote.net.bean.VoteResultBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.utils.C;
import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;


/**
 * Created by chenlong on 14-12-24.
 */


public class ResultActivity extends BaseVoteUI {

    private String vid;
    private VoteResultBean voteResultBean;

    @ViewInject(R.id.barChart)
    private BarChart barChart;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_result);
        ViewUtils.inject(this);

        Intent intent = getIntent();
        vid = intent.getStringExtra("vid") == null ? "" : intent.getStringExtra("vid");
        doGetResult();
    }

    private void doGetResult() {
        Map<String, String>params = new HashMap<>();
        params.put("vid", vid);
        HustVoteRequest<VoteResultBean> request = new HustVoteRequest<VoteResultBean>(Request.Method.POST,
                C.Net.API.doGetVoteResult, VoteResultBean.class, params,
                new Response.Listener<VoteResultBean>() {
                    @Override
                    public void onResponse(VoteResultBean response) {
                        voteResultBean = response;
                        doShowResult();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }

    private void doShowResult() {
        //barChart.setDescription(voteResultBean.getTitle());

        ArrayList<BarEntry> voteData = new ArrayList<>();
        ArrayList<String> xVals = new ArrayList<>();
        int i = 0;
        for (ResultDataBean dataBean : voteResultBean.getResultdata()) {
            voteData.add(new BarEntry(dataBean.getCount(), i));
            xVals.add(dataBean.getChoice_name());
            i++;
        }
        BarDataSet barDataSet = new BarDataSet(voteData, voteResultBean.getTitle());
        BarData barData = new BarData(xVals, barDataSet);
        barChart.setData(barData);
        barChart.notifyDataSetChanged();
    }
}
