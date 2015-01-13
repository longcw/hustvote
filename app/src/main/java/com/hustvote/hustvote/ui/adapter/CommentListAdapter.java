package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.CommentItemBean;
import com.hustvote.hustvote.net.bean.VoteItemBean;
import com.hustvote.hustvote.net.utils.NetworkUtils;
import com.hustvote.hustvote.utils.C;
import com.hustvote.hustvote.utils.UserInfo;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

/**
 * Created by chenlong on 15-1-1.
 */
public class CommentListAdapter extends BaseAdapter {
    List<CommentItemBean> data;
    LayoutInflater layoutInflater;
    ImageLoader imageLoader;
    Context context;

    public CommentListAdapter(Context context, List<CommentItemBean> commentItemBeanList) {
        this.context = context;
        data = commentItemBeanList;
        layoutInflater = LayoutInflater.from(context);
        imageLoader = NetworkUtils.getInstance(context).getImageLoader();
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
        NetworkImageView userIcon = (NetworkImageView) itemView.findViewById(R.id.userIcon);

        CommentItemBean commentItemBean = data.get(pos);

        userIcon.setDefaultImageResId(R.drawable.face_default);
        userIcon.setErrorImageResId(R.drawable.face_default);
        int fid = commentItemBean.getFrom_uid() % C.FACE_COUNT;
        String icon_url = C.Net.API.getUserFace + fid + ".jpg";
        userIcon.setImageUrl(icon_url, imageLoader);



        nickname.setText(commentItemBean.getFrom_nickname());

        Date create_time = new Date(Long.valueOf(commentItemBean.getCreate_time()) * 1000);
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
        time.setText(df.format(create_time));

        content.setText(commentItemBean.getContent());
        return itemView;
    }

}
