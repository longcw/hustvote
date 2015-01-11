package com.hustvote.hustvote.ui;

import android.os.Bundle;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.view.ViewPager;
import android.support.v7.app.ActionBar;

import com.hustvote.hustvote.R;
import com.hustvote.hustvote.ui.adapter.HallFragmentAdapter;

/**
 * Created by chenlong on 15-1-11.
 */
public class HallFragmentActivity extends BaseVoteUI implements ActionBar.TabListener{

    ViewPager viewPager;

    HallFragmentAdapter hallFragmentAdapter;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_hall_fragemt);

        final ActionBar actionBar = getSupportActionBar();
        actionBar.setDisplayUseLogoEnabled(true);
        actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);

        viewPager = (ViewPager) findViewById(R.id.pager);
        hallFragmentAdapter = new HallFragmentAdapter(getSupportFragmentManager());
        viewPager.setAdapter(hallFragmentAdapter);
        viewPager.setOnPageChangeListener(new ViewPager.SimpleOnPageChangeListener() {
            @Override
            public void onPageSelected(int position) {
                // When swiping between different app sections, select the corresponding tab.
                // We can also use ActionBar.Tab#select() to do this if we have a reference to the
                // Tab.
                actionBar.setSelectedNavigationItem(position);
            }
        });

        for (int i = 0; i < hallFragmentAdapter.getCount(); i++) {
            // Create a tab with text corresponding to the page title defined by the adapter.
            // Also specify this Activity object, which implements the TabListener interface, as the
            // listener for when this tab is selected.
            actionBar.addTab(
                    actionBar.newTab()
                            .setText(hallFragmentAdapter.getPageTitle(i))
                            .setTabListener(this));
        }


    }

    @Override
    public void onTabSelected(ActionBar.Tab tab, FragmentTransaction fragmentTransaction){
        viewPager.setCurrentItem(tab.getPosition());
    }

    public void onTabUnselected(ActionBar.Tab tab, FragmentTransaction fragmentTransaction){

    }

    public void onTabReselected(ActionBar.Tab tab, FragmentTransaction fragmentTransaction){

    }


}
