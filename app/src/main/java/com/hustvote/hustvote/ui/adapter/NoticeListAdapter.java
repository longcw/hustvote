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

import java.util.List;

/**
 * Created by chenlong on 15-1-1.
 */
public class NoticeListAdapter extends BaseAdapter {
    List<CommentItemBean> data;
    LayoutInflater layoutInflater;

    public NoticeListAdapter(Context context, List<CommentItemBean> commentItemBeanList) {
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
        return data.get(pos).getCid();
    }

    @Override
    public View getView(int pos, View convertView, ViewGroup arg2) {
        View itemView = null;
        if(convertView == null) {
            itemView = layoutInflater.inflate(R.layout.noticeitem_layout, null);
        } else {
            itemView = convertView;
        }

        TextView title = (TextView) itemView.findViewById(R.id.notice_title);
        TextView content = (TextView) itemView.findViewById(R.id.notice_content);

        CommentItemBean commentItemBean = data.get(pos);
        String titleStr = commentItemBean.getFrom_nickname() + "在投票【" +commentItemBean.getTitle() +
                "】评论了你";
        title.setText(titleStr);
        if(commentItemBean.getIs_read() == 0) {
            //TODO 未读消息
            title.setTextColor(Color.RED);
        } else {
            title.setTextColor(Color.BLACK);
        }
        content.setText(commentItemBean.getContent());
        return itemView;
    }

}
