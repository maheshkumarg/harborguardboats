package net.techgalore.harborguardboats;

import android.app.ProgressDialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.Iterator;
import java.util.Timer;
import java.util.TimerTask;

public class EProfile extends Fragment {
    private static String get_profile_URL = "http://nutech-solutions.com/harborguardboats/index.php/user/";
    private static String update_profile_URL = "http://nutech-solutions.com/harborguardboats/index.php/users/";
    private static String userId;
    JSONArray android = null;
    private TextView firstName;
    private TextView lastName;
    private TextView desig;
    private TextView email;
    private TextView phone;
    private TextView passwd;
    private TextView cPasswd;
    //URL to get JSON Array
    //private static final String get_profile_URL = "http://10.0.2.2/~Mahesh/Admin/index.php/user/";
    //private static final String update_profile_URL = "http://10.0.2.2/~Mahesh/Admin/index.php/users/";
    private Button updateBtn;
    private ProgressDialog pDialog;
    private View rootView;// Cache Fragment view

    public EProfile() {
    }

    public static String POST(String url, User user) {
        InputStream inputStream = null;
        String result = "";
        try {
            // 1. create HttpClient
            HttpClient httpclient = new DefaultHttpClient();

            // 2. make POST request to the given URL
            HttpPost httpPost = new HttpPost(url);

            String json = "";

            // 3. build jsonObject
            JSONObject jsonObject = new JSONObject();
            jsonObject.accumulate("firstName", user.getFirstName());
            jsonObject.accumulate("lastName", user.getLastName());
            jsonObject.accumulate("email", user.getEmail());
            jsonObject.accumulate("designation", user.getDesignation());
            jsonObject.accumulate("phoneNumber", user.getPhoneNumber());
            jsonObject.accumulate("userType", user.getUserType());
            jsonObject.accumulate("updatedBy", user.getUpdatedBy());
            jsonObject.accumulate("password", user.getPassword());

            // 4. convert JSONObject to JSON to String
            json = jsonObject.toString();

            // ** Alternative way to convert User object to JSON string usin Jackson Lib
            // ObjectMapper mapper = new ObjectMapper();
            // json = mapper.writeValueAsString(person);

            // 5. set json to StringEntity
            StringEntity se = new StringEntity(json);

            // 6. set httpPost Entity
            httpPost.setEntity(se);

            // 7. Set some headers to inform server about the type of the content
            httpPost.setHeader("Accept", "application/json");
            httpPost.setHeader("Content-type", "application/json");

            // 8. Execute POST request to the given URL
            HttpResponse httpResponse = httpclient.execute(httpPost);

            // 9. receive response as inputStream
            inputStream = httpResponse.getEntity().getContent();

            // 10. convert inputstream to string
            if (inputStream != null)
                result = convertInputStreamToString(inputStream);
            else
                result = "Did not work!";

        } catch (Exception e) {
            Log.d("InputStream", e.getLocalizedMessage());
        }

        // 11. return result
        return result;
    }

    private static String convertInputStreamToString(InputStream inputStream) throws IOException {
        BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream));
        String line = "";
        String result = "";
        while ((line = bufferedReader.readLine()) != null)
            result += line;
        inputStream.close();
        return result;

    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Bundle data = getArguments();
        userId = data.getString("userId");
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        setRetainInstance(true);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        if (rootView == null) {
            rootView = LayoutInflater.from(getActivity()).inflate(R.layout.profile,
                    null);
        }
        // Cached rootView need to determine whether the parent has been added, if there is parent needs to be deleted from parent, or else will happen this rootview has a parent error.
        ViewGroup parent = (ViewGroup) rootView.getParent();
        if (parent != null) {
            parent.removeView(rootView);
        }

        firstName = (TextView) rootView.findViewById(R.id.txtFirstName);
        lastName = (TextView) rootView.findViewById(R.id.txtLastName);
        desig = (TextView) rootView.findViewById(R.id.txtDesig);
        email = (TextView) rootView.findViewById(R.id.txtEmail);
        phone = (TextView) rootView.findViewById(R.id.txtPhone);
        passwd = (TextView) rootView.findViewById(R.id.txtPasswd);
        cPasswd = (TextView) rootView.findViewById(R.id.txtCPasswd);

        updateBtn = (Button) rootView.findViewById(R.id.btnSubmit);
        updateBtn.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                if (validateForm()) {
                    pDialog = new ProgressDialog(getActivity());
                    pDialog.setMessage("Updating Profile...");
                    pDialog.setIndeterminate(false);
                    pDialog.setCancelable(true);
                    pDialog.show();
                    new HttpAsyncTask().execute(update_profile_URL + userId);
                }
            }
        });

        new JSONParse().execute();

        return rootView;
    }

    private boolean validateForm() {
        boolean validForm = true;
        String msg = null;
        if (firstName.getText().length() < 1) {
            msg = "Please enter first name";
            validForm = false;
        } else if (lastName.getText().length() < 1) {
            msg = "Please enter last name";
            validForm = false;
        } else if (email.getText().length() < 1) {
            msg = "Please select a valid email";
            validForm = false;
        } else {
            if (passwd.getText().length() > 0) {
                if (!passwd.getText().toString().equalsIgnoreCase(cPasswd.getText().toString())) {
                    msg = "Passwords do not match";
                    validForm = false;
                }
            }
        }

        if (!validForm) {
            GeneralUtility.showToast(getActivity(), msg);
        }

        return validForm;
    }

    private class JSONParse extends AsyncTask<String, String, JSONObject> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(getActivity());
            pDialog.setMessage("Getting profile ...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        @Override
        protected JSONObject doInBackground(String... args) {
            JSONParser jParser = new JSONParser();
            JSONObject json = jParser.getJSONFromUrl(get_profile_URL + userId);
            return json;
        }

        @Override
        protected void onPostExecute(JSONObject json) {
            pDialog.dismiss();
            try {
                // Getting JSON Array from URL
                android = json.getJSONArray("user");
                for (int i = 0; i < android.length(); i++) {
                    JSONObject c = android.getJSONObject(i);

                    Iterator<?> keys = c.keys();
                    firstName.setText(c.get("firstName").toString());
                    lastName.setText(c.get("lastName").toString());
                    desig.setText(c.get("designation").toString());
                    email.setText(c.get("email").toString());
                    phone.setText(c.get("phoneNumber").toString());
                    passwd.setText(c.get("password").toString());
                    cPasswd.setText(c.get("password").toString());
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }

    private class HttpAsyncTask extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... urls) {

            User user = new User();
            user.setFirstName(firstName.getText().toString());
            user.setLastName(lastName.getText().toString());
            user.setEmail(email.getText().toString());
            user.setDesignation(desig.getText().toString());
            user.setPhoneNumber(phone.getText().toString());
            user.setUserType("Employee");
            user.setUpdatedBy(Integer.parseInt(userId));
            if (passwd.getText().length() > 0) {
                user.setPassword(passwd.getText().toString());
            }

            return POST(urls[0], user);
        }

        // onPostExecute displays the results of the AsyncTask.
        @Override
        protected void onPostExecute(String result) {
            JSONObject json = null;
            try {
                json = new JSONObject(result);
                if (json != null) {
                    if (json.getString("firstName") == null) {
                        pDialog.setMessage(json.get("error").toString());
                    } else {
                        pDialog.setMessage("Successfully updated profile");
                    }
                }
            } catch (Exception e) {
                GeneralUtility.showToast(getActivity(), "Oops! Please try again");
            }

            long delayInMillis = 1000;
            Timer timer = new Timer();
            timer.schedule(new TimerTask() {
                @Override
                public void run() {
                    pDialog.dismiss();
                }
            }, delayInMillis);
        }
    }
}