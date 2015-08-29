package net.techgalore.harborguardboats;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentTabHost;
import android.view.Menu;
import android.view.MenuItem;


public class EmployeeHome extends FragmentActivity {

    private FragmentTabHost mTabHost;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.bottom_tabs);
        mTabHost = (FragmentTabHost) findViewById(android.R.id.tabhost);
        mTabHost.setup(this, getSupportFragmentManager(), R.id.realtabcontent);
        String userId = getIntent().getExtras().getString("userId");
        Bundle b = new Bundle();
        b.putString("key", "Process");
        b.putString("userId", userId);
        mTabHost.addTab(mTabHost.newTabSpec("process").setIndicator("Process"), EProcess.class, b);

        b = new Bundle();
        b.putString("key", "Profile");
        b.putString("userId", userId);
        mTabHost.addTab(mTabHost.newTabSpec("profile").setIndicator("Profile"), EProfile.class, b);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        menu.clear();
        getMenuInflater().inflate(R.menu.top_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.logout:
                logout();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void logout() {
        Intent intent = new Intent(EmployeeHome.this, LoginActivity.class);
        startActivity(intent);
        finish();
    }
}
