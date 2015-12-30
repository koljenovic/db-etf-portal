package com.example.netpc.etf;

import android.os.Bundle;
import android.support.design.widget.NavigationView;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;


public class MainActivity extends AppCompatActivity{
    DrawerLayout mDrawerLayout;
    NavigationView mNavigationView;
    FragmentManager mFragmentManager;
    FragmentTransaction mFragmentTransaction;








    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        /**
         *Setup the DrawerLayout and NavigationView
         */

        mDrawerLayout = (DrawerLayout) findViewById(R.id.drawerLayout);
        mNavigationView = (NavigationView) findViewById(R.id.shitstuff) ;

        /**
         * Lets inflate the very first fragment
         * Here , we are inflating the TabFragment as the first Fragment
         */

        mFragmentManager = getSupportFragmentManager();
        mFragmentTransaction = mFragmentManager.beginTransaction();
        mFragmentTransaction.replace(R.id.containerView,new TabFragment()).commit();
        /**
         * Setup click events on the Navigation View Items.
         */

        mNavigationView.setNavigationItemSelectedListener(new NavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(MenuItem menuItem) {
                mDrawerLayout.closeDrawers();

                if (menuItem.getItemId() == R.id.nav_item_login) {
                    FragmentTransaction xfragmentTransaction = mFragmentManager.beginTransaction();
                    xfragmentTransaction.replace(R.id.containerView,new TabFragment()).commit();
                }

                return false;
            }

        });

        /**
         * Setup Drawer Toggle of the Toolbar
         */

        android.support.v7.widget.Toolbar toolbar = (android.support.v7.widget.Toolbar) findViewById(R.id.toolbar);
        ActionBarDrawerToggle mDrawerToggle = new ActionBarDrawerToggle(this,mDrawerLayout, toolbar,R.string.app_name,
                R.string.app_name);

        mDrawerLayout.setDrawerListener(mDrawerToggle);

        mDrawerToggle.syncState();

        mNavigationView = (NavigationView) findViewById(R.id.shitstuff);
        // Setup drawer view
        setupDrawerContent(mNavigationView);

    }

    public void selectDrawerItem(MenuItem menuItem) {

        // Create a new fragment and specify the planet to show based on
        // position

        Fragment fragment = null;
        Class fragmentClass;
        switch(menuItem.getItemId()) {

            case R.id.nav_item_home:

                fragmentClass = TabFragment.class;
                break;

            case R.id.nav_item_login:

                fragmentClass = login.class;
                break;

            case R.id.nav_item_fakultet:

                fragmentClass = TabFragmentfakultet.class;
                break;

            case R.id.nav_item_studij:

                fragmentClass = TabFragmentstudij.class;
                break;

            case R.id.nav_item_odsjeci:

                fragmentClass = TabFragmentodsjeci.class;
                break;

            case R.id.nav_item_naucnirad:

                fragmentClass = TabFragmentnaucnirad.class;
                break;

            case R.id.nav_item_media:

                fragmentClass = TabFragmentmedia.class;
                break;

            default:
                fragmentClass = TabFragment.class;
        }



        try {
            fragment = (Fragment) fragmentClass.newInstance();
        } catch (Exception e) {
            e.printStackTrace();
        }

        // Insert the fragment by replacing any existing fragment

        FragmentManager fragmentManager = getSupportFragmentManager();
        fragmentManager.beginTransaction().replace(R.id.containerView, fragment).commit();

        // Highlight the selected item, update the title, and close the drawer

        menuItem.setChecked(true);
        setTitle(menuItem.getTitle());
        mDrawerLayout.closeDrawers();
    }

    private void setupDrawerContent(NavigationView navigationView) {
        navigationView.setNavigationItemSelectedListener(
                new NavigationView.OnNavigationItemSelectedListener() {
                    @Override
                    public boolean onNavigationItemSelected(MenuItem menuItem) {
                        selectDrawerItem(menuItem);
                        return true;
                    }
                });

    }
}