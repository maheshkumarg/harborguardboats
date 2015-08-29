package net.techgalore.harborguardboats;


import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.*;
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
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.*;

public class EProcess extends Fragment implements AdapterView.OnItemSelectedListener {

    //private static final String add_process_URL = "http://10.0.2.2/~Mahesh/Admin/index.php/process";
    //private static String get_process_groups_URL = "http://10.0.2.2/~Mahesh/Admin/index.php/process/groups/";
    //private static String get_materials_URL = "http://10.0.2.2/~Mahesh/Admin/index.php/materials/";
    //private static final String get_products_URL = "http://10.0.2.2/~Mahesh/Admin/index.php/products";

    private static final String add_process_URL = "http://nutech-solutions.com/harborguardboats/index.php/process";
    private static final String get_products_URL = "http://nutech-solutions.com/harborguardboats/index.php/products";
    private static String get_process_groups_URL = "http://nutech-solutions.com/harborguardboats/index.php/process/groups/";
    private static String get_materials_URL = "http://nutech-solutions.com/harborguardboats/index.php/materials/";

    private static String userId;
    JSONArray android;

    ArrayAdapter<String> processGrpAdapter;
    ArrayAdapter<String> productsAdapter;
    ArrayAdapter<String> materialAdapter;

    List<String> processLbls;
    List<String> prodctLbls;
    List<String> materialLbls;

    MyCustomAdapter dataAdapter;

    ListView listView;
    EditText myFilter;

    private TextView name;
    private TextView startDate;
    private TextView startTime;
    private TextView endDate;
    private TextView endTime;
    private Button addBtn;

    private ProgressDialog pDialog;

    private Spinner spinnerProcessGroups;
    private Spinner spinnerProducts;

    private ArrayList<ProcessVO> processVOs;
    private ArrayList<ProductVO> productsVOs;
    private ArrayList<MaterialVO> materialVOs;

    private TextView lblMaterials;
    private List<Integer> selectedMaterialIds;

    // Cache Fragment view
    private View rootView;

    private View.OnFocusChangeListener onFocusListener = new View.OnFocusChangeListener() {
        @Override
        public void onFocusChange(View v, boolean hasFocus) {
            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

            View rootView = LayoutInflater.from(getActivity()).inflate(R.layout.dialog,
                    null);

            ArrayList<MaterialVO> materialList = new ArrayList<MaterialVO>();

            for (int i = 0; i < materialVOs.size(); i++) {
                MaterialVO material = new MaterialVO(materialVOs.get(i).getId(), materialVOs.get(i).getName(), false);
                materialList.add(material);
            }

            dataAdapter = new MyCustomAdapter(getActivity(), R.layout.row, materialList);

            // Assign adapter to ListView
            listView = (ListView) rootView.findViewById(R.id.listView1);
            myFilter = (EditText) rootView.findViewById(R.id.inputSearch);
            listView.setAdapter(dataAdapter);

            //enables filtering for the contents of the given ListView
            listView.setTextFilterEnabled(true);

            myFilter.addTextChangedListener(new TextWatcher() {

                public void afterTextChanged(Editable s) {
                }

                public void beforeTextChanged(CharSequence s, int start, int count, int after) {
                }

                public void onTextChanged(CharSequence s, int start, int before, int count) {
                    dataAdapter.getFilter().filter(s.toString());
                }
            });

            builder.setView(rootView);

            builder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int which) {
                    dialog.dismiss();
                }
            });

            builder.setPositiveButton("ok", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int which) {
                    dialog.dismiss();
                }
            });

            AlertDialog alertDialog = builder.create();
            alertDialog.getWindow().setLayout(300, 300);
            alertDialog.show();
        }
    };

    public EProcess() {
    }

    public static String POST(String url, Process process) {
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
            jsonObject.accumulate("productId", process.getBoatId());
            jsonObject.accumulate("name", process.getName());
            jsonObject.accumulate("processGroupId", process.getProcessGroupId());
            jsonObject.accumulate("materialIds", process.getMaterialIds());
            jsonObject.accumulate("userId", process.getCreatedBy());
            jsonObject.accumulate("startTime", process.getStartTime());
            jsonObject.accumulate("endTime", process.getEndTime());
            jsonObject.accumulate("createdBy", process.getCreatedBy());

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
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        if (rootView == null) {
            rootView = LayoutInflater.from(getActivity()).inflate(R.layout.process_employee,
                    null);
        }
        // Cached rootView need to determine whether the parent has been added, if there is parent needs to be deleted from parent, or else will happen this rootview has a parent error.
        ViewGroup parent = (ViewGroup) rootView.getParent();
        if (parent != null) {
            parent.removeView(rootView);
        }

        selectedMaterialIds = new ArrayList<Integer>();
        processLbls = new ArrayList<String>();
        prodctLbls = new ArrayList<String>();
        materialLbls = new ArrayList<String>();

        name = (TextView) rootView.findViewById(R.id.txtName);
        startDate = (TextView) rootView.findViewById(R.id.txtStartDate);
        startTime = (TextView) rootView.findViewById(R.id.txtStartTime);
        endDate = (TextView) rootView.findViewById(R.id.txtEndDate);
        endTime = (TextView) rootView.findViewById(R.id.txtEndTime);

        lblMaterials = (TextView) rootView.findViewById(R.id.lblMaterials);

        lblMaterials.setOnFocusChangeListener(onFocusListener);

        //lblMaterials.setOnClickListener(onClickListener);

        startDate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                GeneralUtility.getDate(startDate, getActivity());
            }
        });
        startTime.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                GeneralUtility.getTime(startTime, getActivity());
            }
        });

        startDate.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if (hasFocus) {
                    GeneralUtility.getDate(startDate, getActivity());
                }
            }
        });

        startTime.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if (hasFocus) {
                    GeneralUtility.getTime(startTime, getActivity());
                }
            }
        });

        endDate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                GeneralUtility.getDate(endDate, getActivity());
            }
        });

        endTime.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                GeneralUtility.getTime(endTime, getActivity());
            }
        });

        endDate.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if (hasFocus) {
                    GeneralUtility.getDate(endDate, getActivity());
                }
            }
        });

        endTime.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if (hasFocus) {
                    GeneralUtility.getTime(endTime, getActivity());
                }
            }
        });

        spinnerProcessGroups = (Spinner) rootView.findViewById(R.id.spinProcess);
        spinnerProducts = (Spinner) rootView.findViewById(R.id.spinProducts);

        processVOs = new ArrayList<ProcessVO>();
        productsVOs = new ArrayList<ProductVO>();
        prodctLbls = new ArrayList<String>();

        new getProducts().execute();

        spinnerProducts.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                ProductVO selectedProduct = productsVOs.get(position);
                get_process_groups_URL = get_process_groups_URL.substring(0, get_process_groups_URL.lastIndexOf("/") + 1) + selectedProduct.getId();
                get_materials_URL = get_materials_URL.substring(0, get_materials_URL.lastIndexOf("/") + 1) + selectedProduct.getId();
                lblMaterials.setText(R.string.material_drpdwn_def_text);
                new getProcessGroups().execute();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                // sometimes you need nothing here
            }
        });

        addBtn = (Button) rootView.findViewById(R.id.btnSubmit);
        addBtn.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                String retVal = validateForm();
                if (retVal == null) {
                    pDialog = new ProgressDialog(getActivity());
                    pDialog.setMessage("Adding Process...");
                    pDialog.setIndeterminate(false);
                    pDialog.setCancelable(true);
                    pDialog.show();
                    new HttpAsyncTask().execute(add_process_URL);
                } else {
                    GeneralUtility.showToast(getActivity(), retVal);
                }
            }
        });

        return rootView;
    }

    private String validateForm() {
        if (name.getText().length() < 1) {
            return "Please enter a process name";
        }

        if (selectedMaterialIds.size() < 1) {
            return "Please select a material";
        }

        if (startDate.getText().length() < 1) {
            return "Please select a valid start date";
        }
        {
            SimpleDateFormat format = new SimpleDateFormat("dd-MM-yyyy");

            Date today = new Date();
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);

            Date startDt = null;
            int hh = 0, mm = 0, ss = 10;
            try {
                startDt = format.parse(startDate.getText().toString());
            } catch (ParseException e) {
                return "Please select a valid start date";
            }

            if (startTime.getText().length() > 1) {
                String[] time = startTime.getText().toString().split(":");
                hh = Integer.parseInt(time[0]);
                mm = Integer.parseInt(time[1]);
            }

            startDt.setHours(hh);
            startDt.setMinutes(mm);
            startDt.setSeconds(ss);

            if (startDt.before(today)) {
                return "Start date cannot be a past date";
            }

            today.setHours(23);
            today.setMinutes(59);
            today.setSeconds(59);

            if (startDt.after(today)) {
                return "Start date cannot be a future date";
            }
        }
        if (startTime.getText().length() < 1) {
            return "Please select a valid start time";
        }


        if (endDate.getText().length() < 1) {
            return "Please select a valid end date";
        }
        {
            SimpleDateFormat format = new SimpleDateFormat("dd-MM-yyyy");

            Date today = new Date();
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);

            Date endDt = null;
            int hh = 0, mm = 0, ss = 10;
            try {
                endDt = format.parse(endDate.getText().toString());
            } catch (ParseException e) {
                return "Please select a valid end date";
            }

            if (endTime.getText().length() > 1) {
                String[] time = endTime.getText().toString().split(":");
                hh = Integer.parseInt(time[0]);
                mm = Integer.parseInt(time[1]);
            }

            endDt.setHours(hh);
            endDt.setMinutes(mm);
            endDt.setSeconds(ss);

            if (endDt.before(today)) {
                return "End date cannot be a past date";
            }

            today.setHours(23);
            today.setMinutes(59);
            today.setSeconds(59);

            if (endDt.after(today)) {
                return "End date cannot be a future date";
            }
        }

        if (endTime.getText().length() < 1) {
            return "Please select a valid end time";
        }

        return null;
    }

    private void resetFields() {
        name.setText("");
        startDate.setText("");
        startTime.setText("");
        endDate.setText("");
        endTime.setText("");
        spinnerProducts.setSelection(0);
        spinnerProcessGroups.setSelection(0);
        lblMaterials.setText(R.string.material_drpdwn_def_text);
        selectedMaterialIds = new ArrayList<Integer>();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.menu_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    private void populateProcessSpinner() {
        for (int i = 0; i < processVOs.size(); i++) {
            processLbls.add(processVOs.get(i).getName());
        }

        if (processGrpAdapter == null) {
            processGrpAdapter = new ArrayAdapter<String>(getActivity(), R.layout.simple_spinner_item, processLbls);

            processGrpAdapter.setDropDownViewResource(R.layout.simple_spinner_dropdown_item);

            spinnerProcessGroups.setAdapter(processGrpAdapter);
        } else {
            processGrpAdapter.notifyDataSetChanged();
        }

        selectedMaterialIds = new ArrayList<Integer>();
        new getMaterials().execute();
    }

    private void populateProductSpinner() {
        for (int i = 0; i < productsVOs.size(); i++) {
            prodctLbls.add(productsVOs.get(i).getName());
        }

        if (productsAdapter == null) {
            // Creating adapter for materialSpinner
            productsAdapter = new ArrayAdapter<String>(getActivity(), R.layout.simple_spinner_item, prodctLbls);

            // Drop down layout style - list view with radio button
            productsAdapter.setDropDownViewResource(R.layout.simple_spinner_dropdown_item);

            // attaching data adapter to materialSpinner
            spinnerProducts.setAdapter(productsAdapter);
        } else {
            productsAdapter.notifyDataSetChanged();
        }
    }

    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

    }

    @Override
    public void onNothingSelected(AdapterView<?> parent) {

    }

    private class MyCustomAdapter extends ArrayAdapter<MaterialVO> implements CompoundButton.OnCheckedChangeListener {

        private ArrayList<MaterialVO> originalList;
        private ArrayList<MaterialVO> materialList;
        private MaterialFilter filter;

        public MyCustomAdapter(Context context, int textViewResourceId,
                               ArrayList<MaterialVO> materialList) {
            super(context, textViewResourceId, materialList);
            this.materialList = new ArrayList<MaterialVO>();
            this.materialList.addAll(materialList);
            this.originalList = new ArrayList<MaterialVO>();
            this.originalList.addAll(materialList);
        }

        @Override
        public Filter getFilter() {
            if (filter == null) {
                filter = new MaterialFilter();
            }
            return filter;
        }

        @Override
        public View getView(int position, View convertView, ViewGroup parent) {

            ViewHolder holder = null;
            if (convertView == null) {
                LayoutInflater vi = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
                convertView = vi.inflate(R.layout.row, null);

                holder = new ViewHolder();
                holder.checkbox = (CheckBox) convertView.findViewById(R.id.checkBox1);
                holder.textView = (TextView) convertView.findViewById(R.id.textView1);

                convertView.setTag(holder);
            } else {
                holder = (ViewHolder) convertView.getTag();
            }

            MaterialVO material = materialList.get(position);
            holder.textView.setText(material.getName());
            holder.checkbox.setTag(position);
            holder.checkbox.setChecked(selectedMaterialIds.contains(material.getId()));
            holder.checkbox.setOnCheckedChangeListener(this);
            return convertView;
        }

        @Override
        public void onCheckedChanged(CompoundButton buttonView,
                                     boolean isChecked) {
            Integer val = (Integer) buttonView.getTag();
            int materialId = materialList.get(val).getId();

            if (isChecked) {
                if (!selectedMaterialIds.contains(materialId)) {
                    materialList.get(val).setSelected(true);
                    selectedMaterialIds.add(materialId);
                }
            } else {
                removeItem(materialId);
            }

            int count = selectedMaterialIds.size();
            if (count > 0) {
                lblMaterials.setText(" " + count + " Materials Selected");
            } else {
                lblMaterials.setText("Select Materials");
            }
        }

        private void removeItem(int val) {
            for (int i = 0; i < selectedMaterialIds.size(); i++) {
                if (selectedMaterialIds.get(i) == val) {
                    selectedMaterialIds.remove(i);
                    break;
                }
            }
        }

        private class ViewHolder {
            CheckBox checkbox;
            TextView textView;
        }

        private class MaterialFilter extends Filter {

            @Override
            protected FilterResults performFiltering(CharSequence constraint) {

                constraint = constraint.toString().toLowerCase();
                FilterResults result = new FilterResults();
                if (constraint != null && constraint.toString().length() > 0) {
                    ArrayList<MaterialVO> filteredItems = new ArrayList<MaterialVO>();

                    for (int i = 0, l = originalList.size(); i < l; i++) {
                        MaterialVO material = originalList.get(i);
                        if (material.toString().toLowerCase().contains(constraint))
                            filteredItems.add(material);
                    }
                    result.count = filteredItems.size();
                    result.values = filteredItems;
                } else {
                    synchronized (this) {
                        result.values = originalList;
                        result.count = originalList.size();
                    }
                }
                return result;
            }

            @SuppressWarnings("unchecked")
            @Override
            protected void publishResults(CharSequence constraint,
                                          FilterResults results) {

                materialList = (ArrayList<MaterialVO>) results.values;
                notifyDataSetChanged();
                clear();
                for (int i = 0, l = materialList.size(); i < l; i++)
                    add(materialList.get(i));
                notifyDataSetInvalidated();
            }
        }
    }

    private class getProcessGroups extends AsyncTask<String, String, JSONObject> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(getActivity());
            pDialog.setMessage("Getting process groups ...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        @Override
        protected JSONObject doInBackground(String... args) {
            JSONParser jParser = new JSONParser();
            JSONObject json = jParser.getJSONFromUrl(get_process_groups_URL);
            return json;
        }

        @Override
        protected void onPostExecute(JSONObject json) {
            pDialog.dismiss();
            if (json != null) {
                try {
                    if (json != null) {
                        JSONArray processGrps = json
                                .getJSONArray("processgroups");

                        processVOs = new ArrayList<ProcessVO>();
                        processLbls.removeAll(processLbls);

                        for (int i = 0; i < processGrps.length(); i++) {
                            JSONObject pgObj = (JSONObject) processGrps.get(i);
                            ProcessVO processVO = new ProcessVO(pgObj.getInt("id"),
                                    pgObj.getString("name"));
                            processVOs.add(processVO);
                        }

                        populateProcessSpinner();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            } else {
                Log.e("JSON Data", "Didn't receive any data from server!");
            }
        }
    }

    private class getMaterials extends AsyncTask<String, String, JSONObject> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(getActivity());
            pDialog.setMessage("Getting materials...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        @Override
        protected JSONObject doInBackground(String... args) {
            JSONParser jParser = new JSONParser();
            JSONObject json = jParser.getJSONFromUrl(get_materials_URL);
            return json;
        }

        @Override
        protected void onPostExecute(JSONObject json) {
            pDialog.dismiss();
            if (json != null) {
                try {
                    if (json != null) {
                        JSONArray materials = json.getJSONArray("materials");

                        materialVOs = new ArrayList<MaterialVO>();
                        materialLbls.removeAll(materialLbls);

                        for (int i = 0; i < materials.length(); i++) {
                            JSONObject matObj = (JSONObject) materials.get(i);
                            MaterialVO materialVO = new MaterialVO(matObj.getInt("id"), matObj.getString("name"), false);
                            materialVOs.add(materialVO);
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            } else {
                Log.e("JSON Data", "Didn't receive any data from server!");
            }
        }
    }

    private class getProducts extends AsyncTask<String, String, JSONObject> {
        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(getActivity());
            pDialog.setMessage("Getting boats...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }

        @Override
        protected JSONObject doInBackground(String... args) {
            JSONParser jParser = new JSONParser();
            JSONObject json = jParser.getJSONFromUrl(get_products_URL);
            return json;
        }

        @Override
        protected void onPostExecute(JSONObject json) {
            pDialog.dismiss();
            if (json != null) {
                try {
                    if (json != null) {
                        JSONArray products = json.getJSONArray("products");

                        productsVOs = new ArrayList<ProductVO>();
                        processLbls = new ArrayList<String>();

                        for (int i = 0; i < products.length(); i++) {
                            JSONObject pgObj = (JSONObject) products.get(i);
                            ProductVO productVO = new ProductVO(pgObj.getInt("id"), pgObj.getString("name"));
                            productsVOs.add(productVO);
                        }

                        populateProductSpinner();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            } else {
                Log.e("JSON Data", "Didn't receive any data from server!");
            }
        }
    }

    private class HttpAsyncTask extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... urls) {
            Process process = new Process();
            process.setBoatId(productsVOs.get(spinnerProducts.getSelectedItemPosition()).getId());
            process.setName(name.getText().toString());
            process.setProcessGroupId(processVOs.get(spinnerProcessGroups.getSelectedItemPosition()).getId());

            String selectdMaterialIds = "";
            for (int i = 0; i < selectedMaterialIds.size(); i++) {
                selectdMaterialIds += selectedMaterialIds.get(i) + ",";
            }
            process.setMaterialIds(selectdMaterialIds.substring(0, selectdMaterialIds.lastIndexOf(",")));

            SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd hh:mm");
            Calendar calendar = Calendar.getInstance();

            String[] dateParts = startDate.getText().toString().split("-");
            String[] timeParts = startTime.getText().toString().split(":");

            calendar.set(Integer.parseInt(dateParts[2]), Integer.parseInt(dateParts[1]), Integer.parseInt(dateParts[0]), Integer.parseInt(timeParts[0]), Integer.parseInt(timeParts[1]));
            String dateTime = dateFormat.format(calendar.getTime());
            process.setStartTime(dateTime);

            dateParts = endDate.getText().toString().split("-");
            timeParts = endTime.getText().toString().split(":");

            calendar.set(Integer.parseInt(dateParts[2]), Integer.parseInt(dateParts[1]), Integer.parseInt(dateParts[0]), Integer.parseInt(timeParts[0]), Integer.parseInt(timeParts[1]));
            dateTime = dateFormat.format(calendar.getTime());
            process.setEndTime(dateTime);

            process.setCreatedBy(Integer.parseInt(userId));

            return POST(urls[0], process);
        }

        @Override
        protected void onPostExecute(String result) {
            JSONObject json = null;
            try {
                json = new JSONObject(result);
                if (json != null) {
                    if (json.isNull("processId")) {
                        pDialog.setMessage(json.get("error").toString());
                    } else {
                        pDialog.setMessage("Successfully added process");
                        resetFields();
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