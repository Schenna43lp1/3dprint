package it.suedtirol.druck3d;

import it.suedtirol.druck3d.ui.MainWindow;
import javafx.application.Application;
import javafx.stage.Stage;

public class App extends Application {

    public static void main(String[] args) {
        // AWT für System-Tray aktivieren
        System.setProperty("java.awt.headless", "false");
        launch(args);
    }

    @Override
    public void start(Stage primaryStage) {
        new MainWindow(primaryStage).show();
    }
}
