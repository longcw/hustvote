package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import com.google.zxing.Result;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.utils.C;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

import me.dm7.barcodescanner.zxing.ZXingScannerView;

/**
 * Created by chenlong on 14-12-27.
 */
public class QRCodeScannerActivtiy extends BaseVoteUI implements ZXingScannerView.ResultHandler {
    private ZXingScannerView mScannerView;

    @Override
    public void onCreate(Bundle state) {
        super.onCreate(state);

        setTitle(R.string.qrcode);
        mScannerView = new ZXingScannerView(this);   // Programmatically initialize the scanner view
        setContentView(mScannerView);                // Set the scanner view as the content view
    }

    @Override
    public void onResume() {
        super.onResume();
        mScannerView.setResultHandler(this); // Register ourselves as a handler for scan results.
        mScannerView.startCamera();          // Start camera on resume
    }

    @Override
    public void onPause() {
        super.onPause();
        mScannerView.stopCamera();           // Stop camera on pause
        finish();
    }

    @Override
    public void handleResult(Result rawResult) {
        // Do something with the result here
        String text = rawResult.getText();
        Log.i("codescan", text); // Prints scan results

        String siteUrl = C.Net.SiteUrl;
        //TODO 本地调试
        siteUrl = siteUrl.replace("10.42.0.1", "localhost");

        //正则表达式获取vid和code
        String pattern = siteUrl + "vote/join/(\\d+)(\\?code=(\\w+))?";
        Pattern r = Pattern.compile(pattern);
        Matcher m = r.matcher(text);
        if(m.find()) {
            String vid = m.group(1);
            String code = m.group(3)==null ? "" : m.group(3);
            Log.i("scanner_vid", vid);
            Log.i("scanner_code", code);
            Intent voteIntent = new Intent(this, VoteActivity.class);
            voteIntent.putExtra("start_voteid", vid);
            voteIntent.putExtra("code", code);
            startActivityAndFinish(voteIntent);
        } else {
            toast(text);
            Log.i("scanner_null", text);
        }

    }
}
