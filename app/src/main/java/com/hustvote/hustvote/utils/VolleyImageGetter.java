package com.hustvote.hustvote.utils;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.graphics.Rect;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.LevelListDrawable;
import android.text.Html;
import android.util.DisplayMetrics;
import android.util.Log;
import android.widget.TextView;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.ImageRequest;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.utils.NetworkUtils;

/**
 * Created by chenlong on 14-12-20.
 */
public class VolleyImageGetter implements Html.ImageGetter {

    private Context mCtx;
    private TextView mTV;
    private RequestQueue requestQueue;

    private int height;
    private int width;

    public VolleyImageGetter(Context context, TextView textView) {
        mCtx = context;
        mTV = textView;
        DisplayMetrics displayMetrics = new DisplayMetrics();
        ((Activity)mCtx).getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        //图片的最大宽度和高度
        height = (int)(displayMetrics.heightPixels * 0.3);
        width = (int)(displayMetrics.widthPixels * 0.8);

        requestQueue = NetworkUtils.getInstance(context).getRequestQueue();
    }

    @Override
    public Drawable getDrawable(String source) {
//        LevelListDrawable d = new LevelListDrawable();
//
//        Drawable empty = mCtx.getResources().getDrawable(R.drawable.loading);
//        d.addLevel(0, 0, empty);
//        //d.setBounds(0, 0, empty.getIntrinsicWidth(), empty.getIntrinsicHeight());
//        d.setLevel(0);
//        d.setBounds(getDefaultBitmapBounds(empty.getIntrinsicWidth(), empty.getIntrinsicHeight()));
//        doGetImage(source, d);
//        return d;

        //Drawable empty = mCtx.getResources().getDrawable(R.drawable.loading);
        //empty.setBounds(getDefaultBitmapBounds(empty.getIntrinsicWidth(), empty.getIntrinsicHeight()));
        Drawable empty = new ColorDrawable(Color.TRANSPARENT);
        URLDrawable drawable = new URLDrawable(empty);
        drawable.setBounds(0, 0, width, height);

        doGetImage(source, drawable);
        return drawable;
    }

    private void doGetImage(String source, final URLDrawable d) {
      ImageRequest imageRequest = new ImageRequest(source, new Response.Listener<Bitmap>() {
            @Override
            public void onResponse(Bitmap response) {
                BitmapDrawable bitmapDrawable = new BitmapDrawable(mCtx.getResources(), response);
                bitmapDrawable.setBounds(getDefaultBitmapBounds(response.getWidth(), response.getHeight()));
//                d.addLevel(1, 1,bitmapDrawable);
//                //d.setBounds(0, 0, response.getWidth(), response.getHeight());
//                d.setLevel(1);
                d.setDrawable(bitmapDrawable);
                d.setBounds(getDefaultBitmapBounds(response.getWidth(), response.getHeight()));

                mTV.invalidate();
            }
        }, 0, 0, Bitmap.Config.RGB_565, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        });
        imageRequest.setTag(mCtx);
        requestQueue.add(imageRequest);
    }

    //图片显示大小
    public Rect getDefaultBitmapBounds(int realW, int realH) {
        double rate = realW > width ? (double)width / realW : 1;
        Rect rect = new Rect(0, 0, (int)(realW*rate), (int)(realH*rate));

        return rect;
    }
}
