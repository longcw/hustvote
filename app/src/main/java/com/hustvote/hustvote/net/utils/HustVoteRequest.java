package com.hustvote.hustvote.net.utils;

import android.util.Log;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.android.volley.AuthFailureError;
import com.android.volley.NetworkResponse;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.HttpHeaderParser;
import com.hustvote.hustvote.utils.C;

import java.io.UnsupportedEncodingException;
import java.util.Collections;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by chenlong on 14-12-18.
 */
public class HustVoteRequest<T> extends Request<T> {

    private static final String SERVER_URL = C.Net.BaseUrl;
    private static final String SUCC_CODE = C.Net.SUCC_CODE;


    private Class<T> clazz;
    private Map<String, String> params;
    private Response.Listener<T> listener;
    private String url;

    public HustVoteRequest(int method, String url, Class<T> clazz, Map<String, String> params,
                           Response.Listener<T> listener, Response.ErrorListener errorListener) {
        super(method, SERVER_URL+url, errorListener);
        Log.i("request_params", params.toString());
        init(url, clazz, params, listener);
    }

    public HustVoteRequest(int method, String url, Class<T> clazz,
                           Response.Listener<T> listener, Response.ErrorListener errorListener) {
        super(method, SERVER_URL+url, errorListener);
        init(url, clazz, null, listener);
    }

    private void init(String url, Class<T> clazz, Map<String, String> params,
                      Response.Listener<T> listener) {
        this.url = url;
        this.clazz = clazz;
        this.params = params;
        this.listener = listener;
    }


    @Override
    protected VolleyError parseNetworkError(VolleyError volleyError) {
//        if(volleyError.getMessage().isEmpty()) {
//            volleyError = new HustVoteError(volleyError.getCause().getCause().getMessage());
//        }
        return volleyError;
    }

    @Override
    protected void deliverResponse(T response) {
        listener.onResponse(response);
    }

    @Override
    protected Response<T> parseNetworkResponse(NetworkResponse response) {
        try {
            NetworkUtils.checkSessionCookie(response.headers);
            String body = new String(response.data, HttpHeaderParser.parseCharset(response.headers));
            Log.i("body", body);
            JSONObject json = JSON.parseObject(body);
            Log.i("hustvote_session", json.getString("sid"));
            if(!json.getString("code").equals(SUCC_CODE)) {
                String msg = json.getString("message");
                return Response.error(new HustVoteError(msg));
            }

            return Response.success(JSON.parseObject(json.getString("result"), clazz),
                    HttpHeaderParser.parseCacheHeaders(response));

        } catch (UnsupportedEncodingException e) {
            return Response.error(new ParseError(e));
        }


    }

    @Override
    protected Map<String, String> getParams() throws AuthFailureError {
        return params != null ? params : super.getParams();
    }

    @Override
    public Map<String, String> getHeaders() throws AuthFailureError {
        Map<String, String> headers = super.getHeaders();
        if(headers == null || headers.equals(Collections.emptyMap())) {
            headers = new HashMap<String, String>();
        }
        NetworkUtils.addSessionCookie(headers);
        Log.i("session_add", headers.toString());
        return headers;
    }


}
