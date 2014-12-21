package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
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

        VoteItemBean voteItem = data.get(pos);
        imageView.setDefaultImageResId(R.drawable.vote_default);
        imageView.setErrorImageResId(R.drawable.vote_default);
        imageView.setImageUrl(voteItem.getImage(), imageLoader);
        titleView.setText(voteItem.getTitle());
        summaryView.setText(voteItem.getSummary());

        return itemView;
    }
}
