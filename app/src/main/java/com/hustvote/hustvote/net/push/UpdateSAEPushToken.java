package com.hustvote.hustvote.net.push;

import android.content.Context;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.hustvote.hustvote.net.bean.EmptyBean;
import com.hustvote.hustvote.net.utils.HustVoteRequest;
import com.hustvote.hustvote.net.utils.NetworkUtils;
import com.hustvote.hustvote.utils.C;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by chenlong on 15-1-1.
 */
public class UpdateSAEPushToken {
    public static void update(Context context, int uid, final String token) {
        Log.i("UpdateSAEToken", token);
        if (token == null || token.equals(C.NULL_STR)) {
            return;
        }
        Map<String, String> params = new HashMap<>();
        params.put("uid", Integer.toString(uid));
        params.put("saetoken", token);
        RequestQueue requestQueue = NetworkUtils.getInstance(context).getRequestQueue();
        Request<EmptyBean> request = new HustVoteRequest<EmptyBean>(Request.Method.POST,
                C.Net.API.updateSaeToken, EmptyBean.class, params,
                new Response.Listener<EmptyBean>() {
                    @Override
                    public void onResponse(EmptyBean response) {
                        Log.i("updatetoken", "update success, token = " + token);
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.i("updatetoken", "update failded, token = " + token);
            }
        });
       requestQueue.add(request);
    }

}
