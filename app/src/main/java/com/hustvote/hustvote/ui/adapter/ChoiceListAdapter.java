package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.ListView;
import android.widget.TextView;

import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceItemBean;
import com.hustvote.hustvote.ui.ChoiceIntroFragmentActivity;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by chenlong on 14-12-21.
 */
public class ChoiceListAdapter extends BaseAdapter {
    private Context context;
    private ArrayList<ChoiceItemBean> data;
    private LayoutInflater layoutInflater;

    private List<Integer> selected;
    private ListView listView;
    private int maxChoice;

    private CompoundButton.OnCheckedChangeListener onCheckedChangeListener;

    public ChoiceListAdapter(Context context, ArrayList<ChoiceItemBean> data,
                             List<Integer> selected) {
        this.context = context;
        this.data = data;
        layoutInflater = LayoutInflater.from(context);

        this.selected = selected;

        //选择事件的监听器
        onCheckedChangeListener = new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton button, boolean b) {
                if(b) {
                    ChoiceListAdapter.this.selected.add((Integer) button.getTag());

                    if(ChoiceListAdapter.this.selected.size() >= maxChoice) {
                        setUnCheckable();
                    }
                } else {
                    ChoiceListAdapter.this.selected.remove(button.getTag());
                    if(ChoiceListAdapter.this.selected.size() == maxChoice-1) {
                        setCheckable();
                    }
                }
                Log.i("selected", ChoiceListAdapter.this.selected.toString());
            }
        };


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
    public View getView(final int pos, View convertView, ViewGroup arg2) {
        View itemView = null;
        if (convertView == null) {
            itemView = layoutInflater.inflate(R.layout.choiceitem_layout, null);
        } else {
            itemView = convertView;
        }
        CheckBox checkBox = (CheckBox)itemView.findViewById(R.id.choice_item_checkbox);
        TextView name = (TextView) itemView.findViewById(R.id.choice_item_name);
        checkBox.setFocusableInTouchMode(false);
        checkBox.setTag(data.get(pos).getChoiceid());

        name.setText(data.get(pos).getChoice_name());
        name.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(context, ChoiceIntroFragmentActivity.class);
                intent.putExtra(ChoiceIntroFragmentActivity.ARG_POS, pos);
                intent.putExtra(ChoiceIntroFragmentActivity.ARG_CHOICE_LIST, data);

                context.startActivity(intent);
            }
        });

        checkBox.setOnCheckedChangeListener(onCheckedChangeListener);

        return itemView;
    }


    //未选中的全部禁止
    private void setUnCheckable() {
        int count = getCount();
        for (int i = 1; i <= count; i++) {
            View view = listView.getChildAt(i);
            if(view == null) {
                continue;
            }

            CheckBox checkBox = (CheckBox) view.findViewById(R.id.choice_item_checkbox);
            if (!checkBox.isChecked()) {
                checkBox.setEnabled(false);
            }
        }
    }

    //允许未选中的选择
    private void setCheckable() {
        int count = getCount();
        for (int i = 1; i <= count; i++) {
            View view = listView.getChildAt(i);
            CheckBox checkBox = (CheckBox) view.findViewById(R.id.choice_item_checkbox);
            if (!checkBox.isChecked()) {
                checkBox.setEnabled(true);
            }
        }
    }

    public void clearSelected() {
        selected.clear();
        int count = getCount();
        int count_all = listView.getCount();

        for (int i = 1; i <= count && i < count_all; i++) {
            View view = listView.getChildAt(i);
            CheckBox checkBox = (CheckBox) view.findViewById(R.id.choice_item_checkbox);
            if(checkBox == null) {
                continue;
            }
            checkBox.setChecked(false);
        }
        setCheckable();
    }

    public void setMaxChoice(int maxChoice) {
        this.maxChoice = maxChoice;
    }

    public void setListView(ListView listView) {
        this.listView = listView;
    }
}
