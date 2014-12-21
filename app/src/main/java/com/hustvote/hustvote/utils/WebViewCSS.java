package com.hustvote.hustvote.utils;

import android.os.Build;
import android.webkit.WebSettings;
import android.webkit.WebView;

/**
 * Created by chenlong on 14-12-21.
 */
public class WebViewCSS {
    //设置WebView
    public static void openWebView(WebView webView, String data) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.KITKAT) {
            webView.getSettings().setLayoutAlgorithm(WebSettings.LayoutAlgorithm.TEXT_AUTOSIZING);
        } else {
            webView.getSettings().setLayoutAlgorithm(WebSettings.LayoutAlgorithm.NORMAL);
        }
        webView.loadDataWithBaseURL(C.Net.BaseUrl, getHtmlData(data), "text/html", "utf-8", null);
    }

    //使用CSS设置图片大小
    public static String getHtmlData(String bodyHTML) {
        String head = "<head><style>img{max-width: 100%; width:auto; height: auto;}</style></head>";
        return "<html>" + head + "<body>" + bodyHTML + "</body></html>";
    }

}
