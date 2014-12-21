package com.hustvote.hustvote.utils;

import android.graphics.Canvas;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.util.Log;

/**
 * Created by chenlong on 14-12-20.
 */
public class URLDrawable extends BitmapDrawable {
    // the drawable that you need to set, you could set the initial drawing
    // with the loading image if you need to
    protected Drawable drawable;

    public URLDrawable(Drawable drawable) {
        this.drawable = drawable;
    }

    public void setDrawable(Drawable drawable) {
        this.drawable = drawable;
    }

    @Override
    public void draw(Canvas canvas) {
        // override the draw to facilitate refresh function later
        if(drawable != null) {
            drawable.draw(canvas);
        }
    }
}