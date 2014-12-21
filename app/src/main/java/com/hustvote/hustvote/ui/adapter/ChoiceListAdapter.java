package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;

import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceItemBean;

import java.util.List;

/**
 * Created by chenlong on 14-12-21.
 */
public class ChoiceListAdapter extends BaseAdapter {
    Context context;
    List<ChoiceItemBean> data;
    LayoutInflater layoutInflater;


    public ChoiceListAdapter(Context context, List<ChoiceItemBean> data) {
        this.context = context;
        this.data = data;
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
        return data.get(pos).getChoiceid();
    }

    @Override
    public View getView(int pos, View convertView, ViewGroup arg2) {
        View itemView = null;
        CheckBox checkBox = null;
        if(convertView != null) {
            itemView = convertView;
            checkBox = (CheckBox) convertView.findViewById(R.id.choice_item_name);
        }
        if(checkBox == null) {
            itemView = layoutInflater.inflate(R.layout.choiceitem_layout, null);
            checkBox = (CheckBox) itemView.findViewById(R.id.choice_item_name);
        }
        checkBox.setText(data.get(pos).getChoice_name());
        checkBox.setId(data.get(pos).getChoiceid());
        return itemView;
    }

}
