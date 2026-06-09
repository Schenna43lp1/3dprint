module it.suedtirol.druck3d {
    requires javafx.controls;
    requires javafx.fxml;
    requires com.google.gson;
    requires java.net.http;
    requires java.prefs;
    requires java.desktop;

    opens it.suedtirol.druck3d to javafx.fxml;
    opens it.suedtirol.druck3d.model to com.google.gson;
    opens it.suedtirol.druck3d.ui to javafx.fxml;

    exports it.suedtirol.druck3d;
}
