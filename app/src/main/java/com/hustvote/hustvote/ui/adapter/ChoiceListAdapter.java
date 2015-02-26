package com.hustvote.hustvote.ui.adapter;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
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

    private int maxChoice;
    private int countChoice = 0;

    private CompoundButton.OnCheckedChangeListener onCheckedChangeListener;
    private View.OnClickListener onClickListener;

    public ChoiceListAdapter(Context context, ArrayList<ChoiceItemBean> data, int max) {
        this.context = context;
        this.data = data;
        layoutInflater = LayoutInflater.from(context);

        this.maxChoice = max;

        onClickListener = new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                CompoundButton button = (CompoundButton)view;
                boolean b = button.isChecked();
                int count = countChoice + (b ? 1 : -1);
                if(count < 0 || count > maxChoice) {
                    Log.i("choice count error: ", Integer.toString(count));
                    return;
                }
                countChoice = count;

                ChoiceItemBean choice = (ChoiceItemBean)getItem((int)button.getTag());
                choice.isSelected = b;

                if(b && countChoice >= maxChoice || !b && countChoice == maxChoice - 1) {
                    notifyDataSetChanged();
                }
                Log.i("select changed:",
                        (b ? "select " : "unselect ") + Integer.toString(choice.getChoiceid()));
                Log.i("select count:", Integer.toString(countChoice));
            }
        };

        //选择事件的监听器
        onCheckedChangeListener = new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton button, boolean b) {
                ChoiceItemBean choice = (ChoiceItemBean)getItem((int)button.getTag());
                choice.isSelected = b;
                countChoice += b ? 1 : -1;
                if(b && countChoice >= maxChoice || !b && countChoice == maxChoice - 1) {
                    notifyDataSetChanged();
                }
                Log.i("select changed:",
                        (b ? "select " : "unselect ") + Integer.toString(choice.getChoiceid()));
                Log.i("select count:", Integer.toString(countChoice));
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

        checkBox.setTag(pos);

        //判断是否可选
        ChoiceItemBean choice = (ChoiceItemBean)getItem(pos);
        boolean enable = countChoice < maxChoice || choice.isSelected;
        checkBox.setEnabled(enable);
        checkBox.setChecked(choice.isSelected);

        name.setText(choice.getChoice_name());
        name.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(context, ChoiceIntroFragmentActivity.class);
                intent.putExtra(ChoiceIntroFragmentActivity.ARG_POS, pos);
                intent.putExtra(ChoiceIntroFragmentActivity.ARG_CHOICE_LIST, data);

                context.startActivity(intent);
            }
        });

        //checkBox.setOnCheckedChangeListener(onCheckedChangeListener);
        checkBox.setOnClickListener(onClickListener);

        return itemView;
    }

    public int getCountChoice() {
        return countChoice;
    }

}
