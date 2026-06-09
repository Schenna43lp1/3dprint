package it.suedtirol.druck3d;

import java.util.prefs.Preferences;

public class Config {
    private static final Preferences PREFS = Preferences.userNodeForPackage(Config.class);
    private static final String KEY_URL     = "server_url";
    private static final String KEY_API_KEY = "api_key";
    private static final String KEY_POLL    = "poll_interval";

    public static String getServerUrl()  { return PREFS.get(KEY_URL, "http://localhost:8180"); }
    public static String getApiKey()     { return PREFS.get(KEY_API_KEY, ""); }
    public static int    getPollSeconds(){ return PREFS.getInt(KEY_POLL, 30); }

    public static void setServerUrl(String v)   { PREFS.put(KEY_URL, v.stripTrailing()); }
    public static void setApiKey(String v)       { PREFS.put(KEY_API_KEY, v.strip()); }
    public static void setPollSeconds(int v)     { PREFS.putInt(KEY_POLL, v); }

    public static boolean isConfigured() {
        return !getServerUrl().isBlank() && !getApiKey().isBlank();
    }
}
