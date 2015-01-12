package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.android.volley.toolbox.NetworkImageView;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.CommentItemBean;
import com.hustvote.hustvote.net.bean.VoteItemBean;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

/**
 * Created by chenlong on 15-1-1.
 */
public class CommentListAdapter extends BaseAdapter {
    List<CommentItemBean> data;
    LayoutInflater layoutInflater;

    public CommentListAdapter(Context context, List<CommentItemBean> commentItemBeanList) {
        data = commentItemBeanList;
        layoutInflater = LayoutInflater.from(context);
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
    public long getItemId(int pos) {
        return (long) pos;
    }

    @Override
    public View getView(int pos, View convertView, ViewGroup arg2) {
        View itemView = null;
        if(convertView == null) {
            itemView = layoutInflater.inflate(R.layout.review_item, null);
        } else {
            itemView = convertView;
        }

        TextView nickname = (TextView) itemView.findViewById(R.id.nickname);
        TextView time = (TextView) itemView.findViewById(R.id.time);
        TextView content = (TextView) itemView.findViewById(R.id.content);

        CommentItemBean commentItemBean = data.get(pos);

        nickname.setText(commentItemBean.getFrom_nickname());

        Date create_time = new Date(Long.valueOf(commentItemBean.getCreate_time()) * 1000);
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        time.setText(df.format(create_time));

        content.setText(commentItemBean.getContent());
        return itemView;
    }

}
