package net.techgalore.harborguardboats;

import android.app.Activity;
import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.view.Gravity;
import android.widget.DatePicker;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import java.util.Calendar;

/**
 * Created by Mahesh on 28/07/15.
 */
public class GeneralUtility {

    // Variable for storing current date and time
    private static int mYear, mMonth, mDay, mHour, mMinute;

    public static void showToast(Activity act, String msg) {
        Toast toast = Toast.makeText(act, msg, Toast.LENGTH_LONG);
        toast.setGravity(Gravity.CENTER, 0, 0);
        toast.show();
    }

    public static void getDate(TextView ele, Activity activity) {
        // Process to get Current Date
        final Calendar c = Calendar.getInstance();
        final TextView textView = ele;
        mYear = c.get(Calendar.YEAR);
        mMonth = c.get(Calendar.MONTH);
        mDay = c.get(Calendar.DAY_OF_MONTH);

        // Launch Date Picker Dialog
        DatePickerDialog dpd = new DatePickerDialog(activity, new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
                // Display Selected date in textbox
                String day = dayOfMonth < 10 ? "0" + dayOfMonth : "" + dayOfMonth;
                int mm = monthOfYear + 1;
                String month = mm < 10 ? "0" + mm : "" + mm;
                textView.setText(day + "-" + month + "-" + year);
            }
        }, mYear, mMonth, mDay);
        dpd.show();
    }

    public static void getTime(TextView ele, Activity activity) {
        // Process to get Current Time
        final Calendar c = Calendar.getInstance();
        final TextView textView = ele;
        mHour = c.get(Calendar.HOUR_OF_DAY);
        mMinute = c.get(Calendar.MINUTE);

        // Launch Time Picker Dialog
        TimePickerDialog tpd = new TimePickerDialog(activity, new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker view, int hourOfDay, int minute) {
                // Display Selected time in textbox
                String hour = hourOfDay < 10 ? "0" + hourOfDay : "" + hourOfDay;
                String min = minute < 10 ? "0" + minute : "" + minute;
                textView.setText(hour + ":" + min);
            }
        }, mHour, mMinute, false);
        tpd.show();
    }
}
