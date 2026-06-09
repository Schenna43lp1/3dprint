package it.suedtirol.druck3d.model;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class VisitorStats {
    @SerializedName("this_month_views")  public int thisMonthViews;
    @SerializedName("this_month_unique") public int thisMonthUnique;
    @SerializedName("last_month_unique") public int lastMonthUnique;
    @SerializedName("today_views")       public int todayViews;
    public List<DayStats> daily;

    public static class DayStats {
        public String date;
        public int views;
        public int unique;
    }
}
