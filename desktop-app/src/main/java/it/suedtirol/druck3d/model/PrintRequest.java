package it.suedtirol.druck3d.model;

import com.google.gson.annotations.SerializedName;

public class PrintRequest {
    public String id;
    public long   ts;
    public String name;
    public String email;
    public String phone;
    public String material;
    public String color;
    public int    quantity;
    public String description;
    public String status;
    public boolean done;

    @SerializedName("file_original") public String fileOriginal;
    @SerializedName("file_stored")   public String fileStored;
    public String tracking;

    @SerializedName("quote_price") public String quotePrice;
    @SerializedName("quote_note")  public String quoteNote;
    @SerializedName("quote_valid") public String quoteValid;

    @SerializedName("inv_nr")    public String invNr;
    @SerializedName("inv_price") public String invPrice;
    @SerializedName("inv_due")   public String invDue;
    @SerializedName("inv_note")  public String invNote;
    @SerializedName("inv_iban")  public String invIban;

    public String getStatus() {
        if (status != null && !status.isBlank()) return status;
        return done ? "erledigt" : "offen";
    }

    public String getStatusLabel() {
        return switch (getStatus()) {
            case "offen"            -> "Offen";
            case "angebot_gesendet" -> "Angebot gesendet";
            case "bestaetigt"       -> "Bestätigt";
            case "bezahlt"          -> "Bezahlt";
            case "in_bearbeitung"   -> "In Bearbeitung";
            case "druckfertig"      -> "Druckfertig";
            case "abholbereit"      -> "Abholbereit";
            case "versendet"        -> "Versendet";
            case "erledigt"         -> "Erledigt";
            case "storniert"        -> "Storniert";
            default                 -> status;
        };
    }

    public boolean hasFile() { return fileStored != null && !fileStored.isBlank(); }
}
