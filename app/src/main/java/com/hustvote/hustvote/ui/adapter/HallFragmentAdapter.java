package com.hustvote.hustvote.ui.adapter;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;

import com.hustvote.hustvote.ui.fragment.HallFragment;

/**
 * Created by chenlong on 15-1-11.
 */
public class HallFragmentAdapter extends FragmentStatePagerAdapter {


        public HallFragmentAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int i) {
            Fragment fragment = new HallFragment();
            Bundle args = new Bundle();
            args.putInt(HallFragment.ARG_SECTION_NUMBER, i);
            fragment.setArguments(args);

            return fragment;
        }

        @Override
        public int getCount() {
            // For this contrived example, we have a 100-object collection.
            return 2;
        }

        @Override
        public CharSequence getPageTitle(int position) {
            String title;
            switch (position) {
                case 0:
                    //投票大厅
                    title = "投票大厅";
                    break;
                case 1:
                    //热门投票
                    title = "热门投票";
                    break;
                default:
                    title = "unknown";
                    break;
            }
            return title;
        }
}
