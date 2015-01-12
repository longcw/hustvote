package com.hustvote.hustvote.ui;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.ActionBar;
import android.util.Log;

import com.hustvote.hustvote.R;
import com.hustvote.hustvote.net.bean.ChoiceItemBean;
import com.hustvote.hustvote.ui.fragment.ChoiceIntroFragment;

import java.util.ArrayList;

/**
 * Created by chenlong on 15-1-11.
 */
public class ChoiceIntroFragmentActivity extends BaseVoteUI {

    public static final String ARG_CHOICE_LIST = "ARG_CHOICE_LIST";
    public static final String ARG_CID = "cid";

    private Fragment []fragments;

    private ViewPager mViewPager;
    private ChoiceIntroPagerAdapter introPagerAdapter;

    private int current_cid;
    private ArrayList<ChoiceItemBean> choiceList;

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_choiceintro_fragment);

        Intent intent = getIntent();
        choiceList = (ArrayList<ChoiceItemBean>)intent.getSerializableExtra(ARG_CHOICE_LIST);
        if(choiceList == null) {
            finish();
            return;
        }
        current_cid = intent.getIntExtra(ARG_CID, -1);

        fragments = new Fragment[choiceList.size()];
        introPagerAdapter = new ChoiceIntroPagerAdapter(getSupportFragmentManager());

        // Set up action bar.
        final ActionBar actionBar = getSupportActionBar();

        actionBar.setDisplayHomeAsUpEnabled(true);

        // Set up the ViewPager, attaching the adapter.
        mViewPager = (ViewPager) findViewById(R.id.pager);
        mViewPager.setAdapter(introPagerAdapter);
    }

    public class ChoiceIntroPagerAdapter extends FragmentStatePagerAdapter {

        public ChoiceIntroPagerAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int i) {
            if(fragments[i] != null) {
                Log.i("choiceIntroFragment", "fragment match:"+Integer.toString(i));
                return fragments[i];
            }

            Fragment fragment = new ChoiceIntroFragment();
            Bundle args = new Bundle();
            args.putInt(ChoiceIntroFragment.ARG_CID, choiceList.get(i).getChoiceid());
            fragment.setArguments(args);
            fragments[i] = fragment;
            return fragment;
        }

        @Override
        public int getCount() {
            return choiceList.size();
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return choiceList.get(position).getChoice_name();
        }
    }

}
