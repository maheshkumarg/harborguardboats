package net.techgalore.harborguardboats;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.BasicHttpContext;
import org.apache.http.protocol.HttpContext;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;


public class LoginActivity extends Activity {
    //10.0.2.2 is the address used by the Android emulators to refer to the host address

    private static String loginURL = "http://nutech-solutions.com/harborguardboats/index.php/authenticate";
    //private static String loginURL = "http://10.0.2.2/~Mahesh/Admin/index.php/authenticate";

    private EditText username = null;
    private EditText password = null;
    private Button signInBtn;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.login);

        username = (EditText) findViewById(R.id.username);
        password = (EditText) findViewById(R.id.password);
        signInBtn = (Button) findViewById(R.id.siginBtn);
        signInBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (username.getText().length() < 1) {
                    GeneralUtility.showToast(LoginActivity.this, "Please enter username");
                } else if (password.getText().length() < 1) {
                    GeneralUtility.showToast(LoginActivity.this, "Please enter password");
                } else if (username.getText().length() > 0 && password.getText().length() > 0) {
                    new authenticate().execute();
                }
            }
        });
    }

    private class authenticate extends AsyncTask<Void, Void, String> {
        private Activity activity;

        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(LoginActivity.this);
            pDialog.setMessage("Authenticating ...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        protected String getASCIIContentFromEntity(HttpEntity entity) throws IllegalStateException, IOException {
            InputStream in = entity.getContent();

            StringBuffer out = new StringBuffer();
            int n = 1;
            while (n > 0) {
                byte[] b = new byte[4096];
                n = in.read(b);


                if (n > 0) out.append(new String(b, 0, n));
            }

            return out.toString();
        }

        @Override
        protected String doInBackground(Void... params) {
            HttpClient httpClient = new DefaultHttpClient();
            HttpContext localContext = new BasicHttpContext();
            HttpPost httppost = new HttpPost(loginURL);
            HttpResponse response = null;

            try {
                // Add your data
                List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
                nameValuePairs.add(new BasicNameValuePair("username", username.getText().toString()));
                nameValuePairs.add(new BasicNameValuePair("password", password.getText().toString()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

                // Execute HTTP Post Request
                response = httpClient.execute(httppost);
                return inputStreamToString(response.getEntity().getContent()).toString();
            } catch (Exception e) {
                GeneralUtility.showToast(LoginActivity.this, "Oops! Please try again");
            }

            return response.toString();
        }

        // Fast Implementation
        private StringBuilder inputStreamToString(InputStream is) {
            String line = "";
            StringBuilder total = new StringBuilder();
            try {
                // Wrap a BufferedReader around the InputStream
                BufferedReader rd = new BufferedReader(new InputStreamReader(is));

                // Read response until the end
                while ((line = rd.readLine()) != null) {
                    total.append(line);
                }
            } catch (IOException e) {
                GeneralUtility.showToast(LoginActivity.this, "Oops! Please try again");
            }
            return total;
        }

        protected void onPostExecute(String results) {
            pDialog.dismiss();
            super.onPostExecute(results);
            try {
                Intent homeScreen = null;
                JSONArray array = new JSONArray(results);
                if (array.length() > 0) {
                    JSONObject jsonObj = new JSONObject(array.get(0).toString());
                    if (jsonObj.get("userId") != null && jsonObj.get("userType").equals("Admin")) {
                        // homeScreen = new Intent(getApplicationContext(), AdminActivity.class);
                        GeneralUtility.showToast(LoginActivity.this, "Admin login is not available");
                    } else if (jsonObj.get("userId") != null && jsonObj.get("userType").equals("Employee")) {
                        homeScreen = new Intent(getApplicationContext(), EmployeeHome.class);
                        homeScreen.putExtra("userId", jsonObj.get("userId").toString());
                        startActivity(homeScreen);
                    }
                } else {
                    GeneralUtility.showToast(LoginActivity.this, "Invalid login credentials");
                }
            } catch (JSONException e) {
                e.printStackTrace();
                GeneralUtility.showToast(LoginActivity.this, "Oops! Please try again");
            }
        }
    }
}