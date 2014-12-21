package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;

import java.util.List;

/**
 * Created by chenlong on 14-12-21.
 */
public abstract class BeanListAdapter<T> extends BaseAdapter {
    List<T> data;
    Context context;

    public BeanListAdapter(Context context, List<T> data) {
        this.data = data;
        this.context = context;
    }

    @Override
    public int getCount() {
        return data.size();
    }

    @Override
    public Object getItem(int pos) {
        return data.get(pos);
    }

    @Override
    public abstract long getItemId(int position);

    @Override
    public abstract View getView(int pos, View convertView, ViewGroup arg2);
}
