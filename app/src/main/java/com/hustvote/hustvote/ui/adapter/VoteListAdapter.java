package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.VoteItemBean;
import com.hustvote.hustvote.net.utils.NetworkUtils;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

/**
 * Created by chenlong on 14-12-20.
 */
public class VoteListAdapter extends BaseAdapter {
    List<VoteItemBean> data;
    Context context;
    LayoutInflater layoutInflater;
    ImageLoader imageLoader;

    public VoteListAdapter(Context context, List<VoteItemBean> data) {
        this.context = context;
        this.data = data;
        layoutInflater = LayoutInflater.from(context);
        imageLoader = NetworkUtils.getInstance(context).getImageLoader();
    }

    public VoteListAdapter(LayoutInflater inflater, List<VoteItemBean> data) {
        this.data = data;
        layoutInflater = inflater;
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
    public long getItemId(int position) {
        return data.get(position).getStart_voteid();
    }

    @Override
    public View getView(int pos, View convertView, ViewGroup arg2) {
        View itemView = null;
        if(convertView == null) {
            itemView = layoutInflater.inflate(R.layout.voteitem_layout, null);
        } else {
            itemView = convertView;
        }
        NetworkImageView imageView = (NetworkImageView)itemView.findViewById(R.id.vote_item_image);
        TextView titleView = (TextView) itemView.findViewById(R.id.vote_item_title);
        TextView summaryView = (TextView) itemView.findViewById(R.id.vote_item_summary);
        TextView deadline = (TextView) itemView.findViewById(R.id.vote_item_deadline);

        VoteItemBean voteItem = data.get(pos);
        long mTime = Long.valueOf(voteItem.getEnd_time()) * 1000;
        long cTime = System.currentTimeMillis();
        if (mTime <= 0) {
            deadline.setText("本投票长期有效");
        } else if (cTime <= mTime) {
            Date date = new Date(mTime);
            SimpleDateFormat df = new SimpleDateFormat("yyyy年MM月dd日 hh时mm分");
            String timeStr = df.format(date);
            deadline.setText("本投票将于" + timeStr + "结束");
        } else {
            deadline.setText("投票已经结束");
        }

        imageView.setDefaultImageResId(R.drawable.vote_default);
        imageView.setErrorImageResId(R.drawable.vote_default);
        imageView.setImageUrl(voteItem.getImage(), imageLoader);
        titleView.setText(voteItem.getTitle());
        summaryView.setText(voteItem.getSummary());

        return itemView;
    }
}
