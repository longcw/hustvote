package com.hustvote.hustvote.ui.fragment;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.hustvote.hustvote.net.utils.NetworkUtils;
import com.hustvote.hustvote.utils.UserInfo;

/**
 * Created by chenlong on 15-1-11.
 */
public class BaseFragment extends Fragment {

    protected RequestQueue requestQueue;
    protected UserInfo userInfo;

    //protected ProgressDialog progressDialog;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

//        progressDialog = new ProgressDialog(this.getActivity()){
//            @Override
//            protected void onStop() {
//                //取消登录
//                requestQueue.cancelAll(this);
//            }
//        };
//        progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
//        progressDialog.setCancelable(true);
//        progressDialog.setCanceledOnTouchOutside(false);

        requestQueue = NetworkUtils.getInstance(getActivity().getApplicationContext()).getRequestQueue();

        userInfo = UserInfo.getInstance(this.getActivity());

    }

    @Override
    public void onStop() {
        super.onStop();
        requestQueue.cancelAll(this);
    }


    protected void addToRequsetQueue(Request request) {
        request.setTag(this);
        requestQueue.add(request);
    }

    public void toast(String msg) {
        Toast.makeText(getActivity().getApplicationContext(), msg, Toast.LENGTH_SHORT).show();
    }
}
