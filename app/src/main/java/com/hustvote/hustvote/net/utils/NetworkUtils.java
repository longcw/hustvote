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
    public static final String SESSION_COOKIE = "hustvote_session";
    public static final String PREFERENCE_NAME = "Network";

    private static String sessionID;
    private static SharedPreferences preferences;
    private static SharedPreferences.Editor editor;

    private static NetworkUtils mInstance;
    private RequestQueue mRequestQueue;
    private ImageLoader mImageLoader;
    private static Context mCtx;

    private NetworkUtils(Context context) {
        mCtx = context;
        preferences = mCtx.getSharedPreferences(PREFERENCE_NAME, Context.MODE_PRIVATE);
        editor = preferences.edit();
        sessionID = preferences.getString(SESSION_COOKIE, "");

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


    /****************sessionID*******************/

    public static void setSessionID(String sessionID) {
        NetworkUtils.sessionID = sessionID;
        editor.putString(SESSION_COOKIE, sessionID);
        editor.commit();
    }

    public static String getSessionID() {
        return sessionID;
    }


    /**
     * Checks the response headers for session cookie and saves it
     * if it finds it.
     * @param headers Response Headers.
     */
    public static void checkSessionCookie(Map<String, String> headers) {
        if (headers.containsKey(SET_COOKIE_KEY)
                && headers.get(SET_COOKIE_KEY).startsWith(SESSION_COOKIE)) {
            String cookie = headers.get(SET_COOKIE_KEY);
            if (cookie.length() > 0) {
                String[] splitCookie = cookie.split(";");
                String[] splitSessionId = splitCookie[0].split("=");
                cookie = splitSessionId[1];
                editor.putString(SESSION_COOKIE, cookie);
                NetworkUtils.sessionID = cookie;
                editor.commit();
            }
        }
    }

    public static void addSessionCookie(Map<String, String> headers) {
        //Log.i("VolleySingletonSID", "SID=" + sessionId);
        if (sessionID.length() > 0) {
            StringBuilder builder = new StringBuilder();
            builder.append(NetworkUtils.SESSION_COOKIE);
            builder.append("=");
            builder.append(sessionID);
            if (headers.containsKey(NetworkUtils.COOKIE_KEY)) {
                builder.append("; ");
                builder.append(headers.get(NetworkUtils.COOKIE_KEY));
            }
            headers.put(NetworkUtils.COOKIE_KEY, builder.toString());
        }
        //Log.i("session", headers.toString());
    }
}

