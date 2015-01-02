package com.hustvote.hustvote.net.utils;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.util.Log;
import android.util.LruCache;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.Volley;

import java.util.Map;

/**
 * Created by chenlong on 14-12-18.
 */
public class NetworkUtils {

    //保存sessionID
    public static final String COOKIE_KEY = "Cookie";
    public static final String SET_COOKIE_KEY = "Set-Cookie";
    public static final String HUSTVOTE_SESSION_COOKIE = "hustvote_session";
    public static final String SAE_SESSION_COOKIE = "saeut";
    public static final String PREFERENCE_NAME = "Network";
    public static final String NULL_STR = "NULL_STR";

    private static String HustvoteSessionId = NULL_STR;
    private static String SAESessionId = NULL_STR;
    private static SharedPreferences preferences;

    private static NetworkUtils mInstance;
    private RequestQueue mRequestQueue;
    private ImageLoader mImageLoader;
    private static Context mCtx;

    private NetworkUtils(Context context) {
        mCtx = context;
        preferences = mCtx.getSharedPreferences(PREFERENCE_NAME, Context.MODE_PRIVATE);
        HustvoteSessionId = preferences.getString(HUSTVOTE_SESSION_COOKIE, NULL_STR);
        SAESessionId = preferences.getString(SAE_SESSION_COOKIE, NULL_STR);
        Log.i("shareSessionID", HustvoteSessionId+";"+SAESessionId);

        mRequestQueue = getRequestQueue();

        mImageLoader = new ImageLoader(mRequestQueue,
                new ImageLoader.ImageCache() {
                    private final LruCache<String, Bitmap>
                            cache = new LruCache<String, Bitmap>(20);

                    @Override
                    public Bitmap getBitmap(String url) {
                        return cache.get(url);
                    }

                    @Override
                    public void putBitmap(String url, Bitmap bitmap) {
                        cache.put(url, bitmap);
                    }
                });
    }

    public static synchronized NetworkUtils getInstance(Context context) {
        if (mInstance == null) {
            mInstance = new NetworkUtils(context);
        }
        return mInstance;
    }

//    public static synchronized NetworkUtils getInstance() {
//        return mInstance;
//    }

    public RequestQueue getRequestQueue() {
        if (mRequestQueue == null) {
            // getApplicationContext() is key, it keeps you from leaking the
            // Activity or BroadcastReceiver if someone passes one in.
            mRequestQueue = Volley.newRequestQueue(mCtx.getApplicationContext());
        }
        return mRequestQueue;
    }

    public <T> void addToRequestQueue(Request<T> req) {
        getRequestQueue().add(req);
    }

    public ImageLoader getImageLoader() {
        return mImageLoader;
    }


    /****************HustvoteSessionId*******************/

    public static void setSessionId(String type ,String sessionid) {
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString(type, sessionid);
        editor.apply();
    }

    public static String getHustvoteSessionId() {
        return HustvoteSessionId;
    }


    /**
     * Checks the response headers for session cookie and saves it
     * if it finds it.
     * @param headers Response Headers.
     */
    public static void checkSessionCookie(Map<String, String> headers) {
        if (!headers.containsKey(SET_COOKIE_KEY)) {
            return;
        }
        String cookie = headers.get(SET_COOKIE_KEY);
        if (cookie.length() > 0) {
            String[] splitCookie = cookie.split(";");
            for (String item : splitCookie) {
                if(item.contains(SAE_SESSION_COOKIE)) {
                    SAESessionId = item;
                    setSessionId(SAE_SESSION_COOKIE, item);
                } else if(item.contains(HUSTVOTE_SESSION_COOKIE)) {
                    HustvoteSessionId = item;
                    setSessionId(HUSTVOTE_SESSION_COOKIE, item);
                }

            }
        }
//
//        if (headers.containsKey(SET_COOKIE_KEY)) {
//            String cookie = headers.get(SET_COOKIE_KEY);
//            if (cookie.contains(HUSTVOTE_SESSION_COOKIE) || HustvoteSessionId == null || HustvoteSessionId.equals(NULL_STR)
//                    || !HustvoteSessionId.contains(SAE_SESSION_COOKIE)) {
//                setHustvoteSessionId(cookie);
//            }
//        }
        Log.i("session_check", headers.toString());
    }

    public static void addSessionCookie(Map<String, String> headers) {
        StringBuilder builder = new StringBuilder();
        if (HustvoteSessionId != null && !HustvoteSessionId.equals(NULL_STR)) {
            builder.append(HustvoteSessionId);
            builder.append(";");
        }
        if(SAESessionId != null && !SAESessionId.equals(NULL_STR)) {
            builder.append(SAESessionId);
            builder.append(";");
        }
        headers.put(NetworkUtils.COOKIE_KEY, builder.toString());

        Log.i("session_add_utils", headers.toString());
    }
}

