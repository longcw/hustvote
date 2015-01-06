package com.hustvote.hustvote.ui;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.parser.DefaultJSONParser;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.ImageRequest;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceItemBean;
import com.hustvote.hustvote.net.bean.EmptyBean;
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

    private String code;
    private String captcha;
    private String start_voteid;
    private VoteDetailBean voteDetailBean;
    private List<ChoiceItemBean> choiceItemBeanList;
    private ChoiceListAdapter choiceListAdapter;

    private List<Integer> selected;
    private Button submitButton;
    View captchaLayout;

    @ViewInject(R.id.vote_detail_choicelist)
    private ListView choiceListView;

    private TelephonyManager telephonyManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_vote);
        ViewUtils.inject(this);

        Intent intent = getIntent();
        start_voteid = intent.getStringExtra("start_voteid")!=null ? intent.getStringExtra("start_voteid") : "";
        code = intent.getStringExtra("code") == null ? "" : intent.getStringExtra("code");
        captcha = "";

        telephonyManager = (TelephonyManager)getSystemService(TELEPHONY_SERVICE);
        choiceItemBeanList = new ArrayList<>();
        selected = new ArrayList<>();
        choiceListAdapter = new ChoiceListAdapter(this, choiceItemBeanList, selected);

        doGetVoteDetail();
    }

    //联网获取
    private void doGetVoteDetail() {
        Map<String, String> params = new HashMap<>();
        params.put("code", code);
        params.put("IMEI", telephonyManager.getDeviceId());
        params.put("vid", start_voteid);

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

                toast(error.getLocalizedMessage());
                VoteActivity.this.finish();
            }
        });
        addToRequsetQueue(request);
    }

    //展示详情
    private void doShowVoteDetail() {
        //添加header和footer
        View header = getLayoutInflater().inflate(R.layout.activity_vote_header_webview, null);
        TextView title = (TextView)header.findViewById(R.id.vote_detail_title_webview);
        WebView introView = (WebView) header.findViewById(R.id.vote_detail_intro_webview);
        View footer = getLayoutInflater().inflate(R.layout.activity_vote_footer, null);
        submitButton = (Button) footer.findViewById(R.id.vote_submit_button);
        submitButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //投票
                doShowConfirmDialog();
            }
        });

        Button resultButton = (Button) footer.findViewById(R.id.vote_result_button);
        resultButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                toResultActivity();
            }
        });

        //提示信息
        Button infoButton = (Button) footer.findViewById(R.id.vote_info_button);
        infoButton.setVisibility(View.GONE);
        if(!voteDetailBean.getLogmsg().equals("none")) {
            //不能投票
            infoButton.setVisibility(View.VISIBLE);
            submitButton.setEnabled(false);
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setTitle("提示").setMessage(voteDetailBean.getLogmsg() + "\n是否去查看投票结果");
            builder.setPositiveButton("确定", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {
                    toResultActivity();
                }
            }).setNegativeButton("取消", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialogInterface, int i) {}
            });

            final Dialog infoDialog = builder.create();
            infoDialog.show();
            infoButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    infoDialog.show();
                }
            });
        }

        //设置listView
        choiceListView.addHeaderView(header);
        choiceListView.addFooterView(footer);
        choiceListView.setAdapter(choiceListAdapter);
        //传入listView，便于获取childView
        choiceListAdapter.setListView(choiceListView);

        title.setText(voteDetailBean.getContent().getTitle());
        WebViewCSS.openWebView(introView, voteDetailBean.getContent().getIntro());

        choiceItemBeanList.addAll(voteDetailBean.getChoices());
        choiceListAdapter.setMaxChoice(voteDetailBean.getContent().getChoice_max());

        choiceListAdapter.notifyDataSetChanged();
    }

    private void doShowConfirmDialog() {
        //TODO 确认框(判断投票权限、空选、验证码)
        if(selected.isEmpty()) {
            toast("请至少选择一项");
            return;
        }
        final AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("提示");
        if (voteDetailBean.getLogtype().equals("captcha_need")) {
            captchaLayout = getLayoutInflater().inflate(R.layout.captcha_layout, null);
            captchaLayout.findViewById(R.id.captchaLayout).setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    doGetCaptcha();
                }
            });

            builder.setView(captchaLayout);
            //获取验证码
            doGetCaptcha();
        } else {
            builder.setMessage("确认提交?");
        }

        builder.setPositiveButton("提交", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                if(captchaLayout != null) {
                    EditText editText = (EditText) captchaLayout.findViewById(R.id.captchaText);
                    captcha = editText.getText().toString();
                    Log.i("captcha", captcha);
                }
                doJoinVote();
            }
        });
        builder.setNegativeButton("取消", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });

        builder.create().show();

    }


    private void doGetCaptcha() {
        //TODO 获取验证码
        if(captchaLayout == null) {
            return;
        }
        ImageRequest request = new ImageRequest(C.Net.API.doGetCaptcha, new Response.Listener<Bitmap>() {
            @Override
            public void onResponse(Bitmap response) {
                ImageView captchaImage = (ImageView)captchaLayout.findViewById(R.id.captchaImage);
                captchaImage.setImageBitmap(response);
            }
        }, 140, 50, Bitmap.Config.RGB_565, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast("获取验证码失败");
            }
        });
        addToRequsetQueue(request);
    }

    private void doJoinVote() {
        Map<String, String> params = new HashMap<>();
        params.put("captcha", captcha);
        params.put("code", code);
        params.put("IMEI", telephonyManager.getDeviceId());
        params.put("vid", start_voteid);
        params.put("email", userInfo.getPassword().get("email"));
        params.put("password", userInfo.getPassword().get("password"));
        StringBuilder stringBuilder = new StringBuilder();
        for(Integer integer : selected) {
            stringBuilder.append(integer.toString());
            stringBuilder.append(":");
        }
        params.put("choice", stringBuilder.toString());
        Log.i("choice", params.get("choice"));

        HustVoteRequest<EmptyBean> request = new HustVoteRequest<EmptyBean>(Request.Method.POST,
                C.Net.API.doJoinVote, EmptyBean.class, params,
                new Response.Listener<EmptyBean>() {
                    @Override
                    public void onResponse(EmptyBean response) {
                        toast("投票成功");
                        submitButton.setEnabled(false);
                        toResultActivity();
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                toast(error.getMessage());
            }
        });
        addToRequsetQueue(request);
    }

    private void toResultActivity() {
        Intent intent = new Intent(this, ResultActivity.class);
        intent.putExtra("vid", start_voteid);
        startActivity(intent);
    }

}
