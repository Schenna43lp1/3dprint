package it.suedtirol.druck3d;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import it.suedtirol.druck3d.model.PrintRequest;
import it.suedtirol.druck3d.model.VisitorStats;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.time.Duration;
import java.util.List;
import java.util.Map;

public class ApiClient {

    private static final Gson GSON = new Gson();
    private final HttpClient http;

    public ApiClient() {
        this.http = HttpClient.newBuilder()
                .connectTimeout(Duration.ofSeconds(10))
                .build();
    }

    private HttpRequest.Builder base(String path) {
        String url = Config.getServerUrl() + path;
        return HttpRequest.newBuilder()
                .uri(URI.create(url))
                .header("X-API-Key", Config.getApiKey())
                .header("Accept", "application/json")
                .timeout(Duration.ofSeconds(15));
    }

    private String get(String path) throws Exception {
        var req  = base(path).GET().build();
        var resp = http.send(req, HttpResponse.BodyHandlers.ofString());
        if (resp.statusCode() == 401) throw new SecurityException("Ungültiger API-Key");
        if (resp.statusCode() != 200) throw new RuntimeException("Server-Fehler " + resp.statusCode());
        return resp.body();
    }

    private String post(String body) throws Exception {
        var req  = base("/api.php").POST(HttpRequest.BodyPublishers.ofString(body))
                .header("Content-Type", "application/json").build();
        var resp = http.send(req, HttpResponse.BodyHandlers.ofString());
        if (resp.statusCode() == 401) throw new SecurityException("Ungültiger API-Key");
        if (resp.statusCode() != 200) throw new RuntimeException("Server-Fehler " + resp.statusCode());
        return resp.body();
    }

    public List<PrintRequest> fetchRequests() throws Exception {
        String json = get("/api.php?action=requests");
        return GSON.fromJson(json, new TypeToken<List<PrintRequest>>(){}.getType());
    }

    public VisitorStats fetchVisitors() throws Exception {
        String json = get("/api.php?action=visitors");
        return GSON.fromJson(json, VisitorStats.class);
    }

    public void setStatus(String id, String status) throws Exception {
        post(GSON.toJson(Map.of("action", "set_status", "id", id, "status", status)));
    }

    public void ship(String id, String tracking) throws Exception {
        post(GSON.toJson(Map.of("action", "set_status", "id", id,
                "status", "versendet", "tracking", tracking)));
    }

    public void sendQuote(String id, String price, String valid, String note) throws Exception {
        post(GSON.toJson(Map.of("action", "quote", "id", id,
                "price", price, "valid", valid, "note", note)));
    }

    public void sendInvoice(String id, String invNr, String invPrice,
                            String invDue, String invIban, String invNote) throws Exception {
        post(GSON.toJson(Map.of("action", "invoice", "id", id,
                "inv_nr", invNr, "inv_price", invPrice,
                "inv_due", invDue, "inv_iban", invIban, "inv_note", invNote)));
    }

    public void delete(String id) throws Exception {
        post(GSON.toJson(Map.of("action", "delete", "id", id)));
    }
}
