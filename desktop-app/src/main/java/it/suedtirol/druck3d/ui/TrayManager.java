package it.suedtirol.druck3d.ui;

import javafx.application.Platform;
import javafx.stage.Stage;

import java.awt.*;
import java.awt.image.BufferedImage;

public class TrayManager {

    private TrayIcon trayIcon;
    private final Stage primaryStage;

    public TrayManager(Stage primaryStage) {
        this.primaryStage = primaryStage;
    }

    public boolean install() {
        if (!SystemTray.isSupported()) return false;

        Platform.setImplicitExit(false);

        Image icon = createIcon();
        trayIcon = new TrayIcon(icon, "3D Druck Südtirol");
        trayIcon.setImageAutoSize(true);

        PopupMenu menu = new PopupMenu();

        MenuItem showItem = new MenuItem("Dashboard öffnen");
        showItem.addActionListener(e -> Platform.runLater(() -> {
            primaryStage.show();
            primaryStage.toFront();
        }));

        MenuItem quitItem = new MenuItem("Beenden");
        quitItem.addActionListener(e -> {
            SystemTray.getSystemTray().remove(trayIcon);
            Platform.exit();
            System.exit(0);
        });

        menu.add(showItem);
        menu.addSeparator();
        menu.add(quitItem);

        trayIcon.setPopupMenu(menu);
        trayIcon.addActionListener(e -> Platform.runLater(() -> {
            primaryStage.show();
            primaryStage.toFront();
        }));

        try {
            SystemTray.getSystemTray().add(trayIcon);
            return true;
        } catch (AWTException e) {
            return false;
        }
    }

    public void notify(String title, String message) {
        if (trayIcon != null) {
            trayIcon.displayMessage(title, message, TrayIcon.MessageType.INFO);
        }
    }

    public void remove() {
        if (trayIcon != null) SystemTray.getSystemTray().remove(trayIcon);
    }

    private Image createIcon() {
        // 16x16 Drucker-Symbol in Cyan auf dunkelblauem Hintergrund
        BufferedImage img = new BufferedImage(16, 16, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = img.createGraphics();
        g.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);
        g.setColor(new Color(0x0a0e1a));
        g.fillRoundRect(0, 0, 16, 16, 4, 4);
        g.setColor(new Color(0x00d4ff));
        // Druckerbett
        g.fillRect(3, 10, 10, 2);
        // Druckerkopf
        g.fillRect(4, 6, 8, 3);
        // Düse
        g.fillRect(7, 9, 2, 3);
        g.dispose();
        return img;
    }
}
