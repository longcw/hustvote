package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.TextView;

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
import com.github.mikephil.charting.utils.ColorTemplate;
import com.github.mikephil.charting.utils.XLabels;
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

    @ViewInject(R.id.result_title)
    private TextView title;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setTitle(getString(R.string.vote_result));
        setContentView(R.layout.activity_result);
        ViewUtils.inject(this);

        //隐藏
        barChart.setVisibility(View.INVISIBLE);


        Intent intent = getIntent();
        vid = intent.getStringExtra("vid") == null ? "" : intent.getStringExtra("vid");
        doGetResult();
    }

    private void doGetResult() {
        progressDialog.setMessage(getString(R.string.geting));
        progressDialog.show();
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
                progressDialog.cancel();
                finish();
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }

    private void doShowResult() {
        barChart.setDescription("");
        title.setText("【" + voteResultBean.getTitle() + "】的投票结果");
        //y轴坐标
        barChart.setDrawYLabels(false);
        //大小
        barChart.setDrawYValues(true);

        barChart.setPinchZoom(false);

        //x轴坐标
        XLabels xLabels = barChart.getXLabels();
        xLabels.setPosition(XLabels.XLabelPosition.BOTTOM);
        xLabels.setCenterXLabelText(true);
        xLabels.setSpaceBetweenLabels(0);

        // 动画效果
        barChart.animateY(2000);

        //背景表格
        barChart.setDrawGridBackground(false);
        barChart.setDrawHorizontalGrid(false);
        barChart.setDrawVerticalGrid(false);


        ArrayList<BarEntry> voteData = new ArrayList<>();
        ArrayList<String> xVals = new ArrayList<>();
        int i = 0;
        for (ResultDataBean dataBean : voteResultBean.getResultdata()) {
            voteData.add(new BarEntry(dataBean.getCount(), i));
            xVals.add(dataBean.getChoice_name());
            i++;
        }
        BarDataSet barDataSet = new BarDataSet(voteData, voteResultBean.getTitle());
        barDataSet.setColors(ColorTemplate.VORDIPLOM_COLORS);

        ArrayList<BarDataSet> barDataSets = new ArrayList<>();
        barDataSets.add(barDataSet);

        BarData barData = new BarData(xVals, barDataSets);
        barChart.setData(barData);
        barChart.invalidate();

        //显示
        barChart.setVisibility(View.VISIBLE);
        progressDialog.cancel();

    }
}
