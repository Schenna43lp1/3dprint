package it.suedtirol.druck3d.ui;

import it.suedtirol.druck3d.ApiClient;
import it.suedtirol.druck3d.Config;
import it.suedtirol.druck3d.model.PrintRequest;
import it.suedtirol.druck3d.model.VisitorStats;
import javafx.application.Platform;
import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.chart.BarChart;
import javafx.scene.chart.CategoryAxis;
import javafx.scene.chart.NumberAxis;
import javafx.scene.chart.XYChart;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import javafx.scene.paint.Color;
import javafx.scene.text.Font;
import javafx.scene.text.FontWeight;
import javafx.stage.Stage;

import java.time.Instant;
import java.time.ZoneId;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.concurrent.*;

public class MainWindow {

    private static final String[] ALL_STATUSES = {
        "offen","angebot_gesendet","bestaetigt","bezahlt",
        "in_bearbeitung","druckfertig","abholbereit","versendet","erledigt","storniert"
    };
    private static final Map<String,String> STATUS_LABELS = Map.of(
        "offen","Offen","angebot_gesendet","Angebot gesendet","bestaetigt","Bestätigt",
        "bezahlt","Bezahlt","in_bearbeitung","In Bearbeitung","druckfertig","Druckfertig",
        "abholbereit","Abholbereit","versendet","Versendet","erledigt","Erledigt","storniert","Storniert"
    );
    private static final DateTimeFormatter DATE_FMT =
        DateTimeFormatter.ofPattern("dd.MM.yy HH:mm").withZone(ZoneId.systemDefault());

    private final Stage stage;
    private final ApiClient api = new ApiClient();
    private final TrayManager tray;
    private final ObservableList<PrintRequest> requests = FXCollections.observableArrayList();
    private ScheduledExecutorService scheduler;

    private Label statusBar;
    private Label statTotal, statOpen, statVisitors, statToday;
    private BarChart<String, Number> chart;
    private Set<String> knownIds = new HashSet<>();
    private boolean firstLoad = true;

    public MainWindow(Stage stage) {
        this.stage = stage;
        this.tray  = new TrayManager(stage);
    }

    public void show() {
        if (!Config.isConfigured()) {
            showSettings(true);
        }

        stage.setTitle("3D Druck Südtirol – Dashboard");
        stage.setMinWidth(900);
        stage.setMinHeight(600);
        stage.setWidth(1200);
        stage.setHeight(750);

        BorderPane root = new BorderPane();
        root.setStyle("-fx-background-color: #0a0e1a;");
        root.setTop(buildTopBar());
        root.setCenter(buildMainContent());
        root.setBottom(buildStatusBar());

        Scene scene = new Scene(root);
        scene.getStylesheets().add(Objects.requireNonNull(
            getClass().getResource("/it/suedtirol/druck3d/style.css")).toExternalForm());

        stage.setScene(scene);
        stage.setOnCloseRequest(e -> {
            if (tray.install()) {
                e.consume();
                stage.hide();
            }
        });
        stage.show();

        tray.install();
        startPolling();
        refresh();
    }

    /* ── Top Bar ── */
    private HBox buildTopBar() {
        HBox bar = new HBox(12);
        bar.setAlignment(Pos.CENTER_LEFT);
        bar.setPadding(new Insets(12, 20, 12, 20));
        bar.setStyle("-fx-background-color: #111827; -fx-border-color: rgba(255,255,255,0.07); -fx-border-width: 0 0 1 0;");

        Label brand = new Label("3D Druck Südtirol");
        brand.setFont(Font.font("System", FontWeight.BOLD, 18));
        brand.setTextFill(Color.WHITE);

        Label badge = new Label("Desktop");
        badge.setStyle("-fx-background-color: #00d4ff; -fx-text-fill: #000; -fx-padding: 2 8; -fx-background-radius: 10; -fx-font-size: 11; -fx-font-weight: bold;");

        Region spacer = new Region();
        HBox.setHgrow(spacer, Priority.ALWAYS);

        Button refreshBtn = iconButton("⟳ Aktualisieren", "#00d4ff");
        refreshBtn.setOnAction(e -> refresh());

        Button settingsBtn = iconButton("⚙ Einstellungen", "#8b97b0");
        settingsBtn.setOnAction(e -> showSettings(false));

        bar.getChildren().addAll(brand, badge, spacer, refreshBtn, settingsBtn);
        return bar;
    }

    /* ── Main Content ── */
    private TabPane buildMainContent() {
        TabPane tabs = new TabPane();
        tabs.setTabClosingPolicy(TabPane.TabClosingPolicy.UNAVAILABLE);
        tabs.setStyle("-fx-background-color: #0a0e1a;");

        Tab ordersTab = new Tab("📋  Anfragen", buildOrdersTab());
        Tab statsTab  = new Tab("📊  Statistiken", buildStatsTab());

        tabs.getTabs().addAll(ordersTab, statsTab);
        return tabs;
    }

    /* ── Orders Tab ── */
    private VBox buildOrdersTab() {
        VBox box = new VBox(12);
        box.setPadding(new Insets(16));
        box.setStyle("-fx-background-color: #0a0e1a;");

        // Stat-Karten
        statTotal    = statCard("0", "Anfragen gesamt");
        statOpen     = statCard("0", "Offen / In Arbeit");
        statVisitors = statCard("0", "Besucher diesen Monat");
        statToday    = statCard("0", "Heute");

        HBox cards = new HBox(12, statTotal, statOpen, statVisitors, statToday);
        cards.setAlignment(Pos.CENTER_LEFT);

        // Tabelle
        TableView<PrintRequest> table = new TableView<>(requests);
        table.setStyle("-fx-background-color: #111827; -fx-border-color: rgba(255,255,255,0.07);");
        table.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY);
        VBox.setVgrow(table, Priority.ALWAYS);

        TableColumn<PrintRequest, String> colStatus = new TableColumn<>("Status");
        colStatus.setCellValueFactory(c -> new SimpleStringProperty(c.getValue().getStatusLabel()));
        colStatus.setPrefWidth(140);
        colStatus.setCellFactory(col -> new TableCell<>() {
            @Override protected void updateItem(String item, boolean empty) {
                super.updateItem(item, empty);
                if (empty || item == null) { setText(null); setStyle(""); return; }
                setText(item);
                PrintRequest r = getTableView().getItems().get(getIndex());
                String color = statusColor(r.getStatus());
                setStyle("-fx-text-fill: " + color + "; -fx-font-weight: bold; -fx-font-size: 11;");
            }
        });

        TableColumn<PrintRequest, String> colDate = new TableColumn<>("Datum");
        colDate.setCellValueFactory(c -> {
            long ts = c.getValue().ts;
            return new SimpleStringProperty(ts > 0 ? DATE_FMT.format(Instant.ofEpochSecond(ts)) : "");
        });
        colDate.setPrefWidth(110);

        TableColumn<PrintRequest, String> colName = new TableColumn<>("Kunde");
        colName.setCellValueFactory(c -> new SimpleStringProperty(
            c.getValue().name + "\n" + (c.getValue().email != null ? c.getValue().email : "")));
        colName.setPrefWidth(200);

        TableColumn<PrintRequest, String> colMaterial = new TableColumn<>("Material");
        colMaterial.setCellValueFactory(c -> new SimpleStringProperty(
            c.getValue().material + " / " + c.getValue().color));
        colMaterial.setPrefWidth(130);

        TableColumn<PrintRequest, String> colQty = new TableColumn<>("Menge");
        colQty.setCellValueFactory(c -> new SimpleStringProperty(c.getValue().quantity + "×"));
        colQty.setPrefWidth(60);

        TableColumn<PrintRequest, String> colActions = new TableColumn<>("Aktionen");
        colActions.setCellFactory(col -> new TableCell<>() {
            private final ComboBox<String> statusBox = new ComboBox<>();
            private final Button delBtn = new Button("🗑");
            private final HBox cell = new HBox(6, statusBox, delBtn);
            {
                statusBox.getItems().addAll(STATUS_LABELS.values());
                statusBox.setStyle("-fx-background-color: #161d2e; -fx-text-fill: white;");
                delBtn.setStyle("-fx-background-color: rgba(248,113,113,0.15); -fx-text-fill: #f87171; -fx-border-color: rgba(248,113,113,0.3); -fx-border-radius: 6; -fx-background-radius: 6; -fx-cursor: hand;");
                cell.setAlignment(Pos.CENTER_LEFT);
            }
            @Override protected void updateItem(String item, boolean empty) {
                super.updateItem(item, empty);
                if (empty) { setGraphic(null); return; }
                PrintRequest r = getTableView().getItems().get(getIndex());
                statusBox.setValue(r.getStatusLabel());

                statusBox.setOnAction(e -> {
                    String selected = statusBox.getValue();
                    String key = STATUS_LABELS.entrySet().stream()
                        .filter(en -> en.getValue().equals(selected))
                        .map(Map.Entry::getKey).findFirst().orElse(null);
                    if (key == null || key.equals(r.getStatus())) return;

                    if ("versendet".equals(key)) {
                        showTrackingDialog(r);
                    } else {
                        runAsync(() -> api.setStatus(r.id, key), "Status geändert");
                    }
                });
                delBtn.setOnAction(e -> {
                    Alert confirm = new Alert(Alert.AlertType.CONFIRMATION,
                        "Anfrage von " + r.name + " wirklich löschen?",
                        ButtonType.YES, ButtonType.NO);
                    confirm.showAndWait().filter(bt -> bt == ButtonType.YES)
                        .ifPresent(bt -> runAsync(() -> api.delete(r.id), "Anfrage gelöscht"));
                });
                setGraphic(cell);
            }
        });
        colActions.setPrefWidth(220);

        table.getColumns().addAll(colStatus, colDate, colName, colMaterial, colQty, colActions);

        // Kontext-Menu
        table.setRowFactory(tv -> {
            TableRow<PrintRequest> row = new TableRow<>();
            ContextMenu ctx = new ContextMenu();

            MenuItem quoteItem   = new MenuItem("💰  Angebot senden");
            MenuItem invoiceItem = new MenuItem("🧾  Rechnung senden");

            quoteItem.setOnAction(e -> { if (!row.isEmpty()) showQuoteDialog(row.getItem()); });
            invoiceItem.setOnAction(e -> { if (!row.isEmpty()) showInvoiceDialog(row.getItem()); });

            ctx.getItems().addAll(quoteItem, invoiceItem);
            row.setContextMenu(ctx);
            return row;
        });

        box.getChildren().addAll(cards, table);
        return box;
    }

    /* ── Stats Tab ── */
    private VBox buildStatsTab() {
        VBox box = new VBox(16);
        box.setPadding(new Insets(16));
        box.setStyle("-fx-background-color: #0a0e1a;");

        Label title = new Label("Besucher – letzte 14 Tage");
        title.setFont(Font.font("System", FontWeight.BOLD, 15));
        title.setTextFill(Color.web("#00d4ff"));

        CategoryAxis xAxis = new CategoryAxis();
        NumberAxis   yAxis = new NumberAxis();
        xAxis.setTickLabelFill(Color.web("#8b97b0"));
        yAxis.setTickLabelFill(Color.web("#8b97b0"));

        chart = new BarChart<>(xAxis, yAxis);
        chart.setStyle("-fx-background-color: #111827;");
        chart.setLegendVisible(true);
        chart.setAnimated(false);
        VBox.setVgrow(chart, Priority.ALWAYS);

        box.getChildren().addAll(title, chart);
        return box;
    }

    /* ── Status Bar ── */
    private HBox buildStatusBar() {
        HBox bar = new HBox();
        bar.setAlignment(Pos.CENTER_LEFT);
        bar.setPadding(new Insets(6, 16, 6, 16));
        bar.setStyle("-fx-background-color: #111827; -fx-border-color: rgba(255,255,255,0.07); -fx-border-width: 1 0 0 0;");
        statusBar = new Label("Verbinde...");
        statusBar.setTextFill(Color.web("#8b97b0"));
        statusBar.setFont(Font.font(11));
        bar.getChildren().add(statusBar);
        return bar;
    }

    /* ── Polling ── */
    private void startPolling() {
        if (scheduler != null) scheduler.shutdownNow();
        scheduler = Executors.newSingleThreadScheduledExecutor(r -> {
            Thread t = new Thread(r, "poll");
            t.setDaemon(true);
            return t;
        });
        scheduler.scheduleAtFixedRate(this::refresh, Config.getPollSeconds(),
                Config.getPollSeconds(), TimeUnit.SECONDS);
    }

    private void refresh() {
        CompletableFuture.runAsync(() -> {
            try {
                List<PrintRequest> list = api.fetchRequests();
                VisitorStats stats = api.fetchVisitors();

                // Neue Anfragen erkennen
                if (!firstLoad) {
                    list.stream()
                        .filter(r -> !knownIds.contains(r.id))
                        .forEach(r -> tray.notify("Neue Druckanfrage!", "Von " + r.name + " – " + r.material));
                }
                knownIds.clear();
                list.forEach(r -> knownIds.add(r.id));
                firstLoad = false;

                long open = list.stream().filter(r ->
                    !List.of("erledigt","storniert").contains(r.getStatus())).count();

                Platform.runLater(() -> {
                    requests.setAll(list);
                    statTotal.setText(String.valueOf(list.size()));
                    statOpen.setText(String.valueOf(open));
                    statVisitors.setText(String.valueOf(stats.thisMonthUnique));
                    statToday.setText(String.valueOf(stats.todayViews));
                    updateChart(stats);
                    statusBar.setText("Zuletzt aktualisiert: " +
                        DateTimeFormatter.ofPattern("HH:mm:ss").withZone(ZoneId.systemDefault())
                            .format(Instant.now()) + "  •  Server: " + Config.getServerUrl());
                    statusBar.setTextFill(Color.web("#4ade80"));
                });
            } catch (Exception ex) {
                Platform.runLater(() -> {
                    statusBar.setText("Fehler: " + ex.getMessage());
                    statusBar.setTextFill(Color.web("#f87171"));
                });
            }
        });
    }

    private void updateChart(VisitorStats stats) {
        if (stats.daily == null) return;
        chart.getData().clear();
        XYChart.Series<String, Number> views  = new XYChart.Series<>();
        XYChart.Series<String, Number> unique = new XYChart.Series<>();
        views.setName("Seitenaufrufe");
        unique.setName("Unique Besucher");
        for (VisitorStats.DayStats d : stats.daily) {
            String label = d.date.substring(5).replace("-", ".");
            views.getData().add(new XYChart.Data<>(label, d.views));
            unique.getData().add(new XYChart.Data<>(label, d.unique));
        }
        chart.getData().addAll(views, unique);
    }

    /* ── Dialoge ── */
    private void showSettings(boolean required) {
        Dialog<Void> dlg = new Dialog<>();
        dlg.setTitle("Einstellungen");
        dlg.setHeaderText("Server-Verbindung konfigurieren");
        dlg.getDialogPane().getButtonTypes().addAll(ButtonType.OK, ButtonType.CANCEL);

        GridPane grid = new GridPane();
        grid.setHgap(12); grid.setVgap(10); grid.setPadding(new Insets(16));

        TextField urlField = new TextField(Config.getServerUrl());
        PasswordField keyField = new PasswordField();
        keyField.setText(Config.getApiKey());
        Spinner<Integer> pollSpinner = new Spinner<>(10, 300, Config.getPollSeconds(), 10);

        grid.add(new Label("Server-URL:"),   0, 0); grid.add(urlField,    1, 0);
        grid.add(new Label("API-Key:"),      0, 1); grid.add(keyField,    1, 1);
        grid.add(new Label("Polling (s):"),  0, 2); grid.add(pollSpinner, 1, 2);

        dlg.getDialogPane().setContent(grid);
        dlg.setResultConverter(bt -> {
            if (bt == ButtonType.OK) {
                Config.setServerUrl(urlField.getText());
                Config.setApiKey(keyField.getText());
                Config.setPollSeconds(pollSpinner.getValue());
                startPolling();
                refresh();
            }
            return null;
        });
        dlg.showAndWait();
    }

    private void showTrackingDialog(PrintRequest r) {
        TextInputDialog dlg = new TextInputDialog();
        dlg.setTitle("Paket versendet");
        dlg.setHeaderText("Tracking-Nummer für " + r.name);
        dlg.setContentText("Tracking-Nr. (optional):");
        dlg.showAndWait().ifPresent(tracking ->
            runAsync(() -> api.ship(r.id, tracking), "Als versendet markiert"));
    }

    private void showQuoteDialog(PrintRequest r) {
        Dialog<Void> dlg = new Dialog<>();
        dlg.setTitle("Angebot senden");
        dlg.setHeaderText("Angebot an " + r.name);
        dlg.getDialogPane().getButtonTypes().addAll(ButtonType.OK, ButtonType.CANCEL);

        GridPane grid = formGrid();
        TextField price = new TextField();  price.setPromptText("z.B. 24,50 €");
        TextField valid = new TextField();  valid.setPromptText("JJJJ-MM-TT");
        TextArea  note  = new TextArea();   note.setPrefRowCount(3);

        grid.add(new Label("Preis *:"),     0, 0); grid.add(price, 1, 0);
        grid.add(new Label("Gültig bis:"),  0, 1); grid.add(valid, 1, 1);
        grid.add(new Label("Hinweis:"),     0, 2); grid.add(note,  1, 2);

        dlg.getDialogPane().setContent(grid);
        dlg.setResultConverter(bt -> {
            if (bt == ButtonType.OK && !price.getText().isBlank())
                runAsync(() -> api.sendQuote(r.id, price.getText(), valid.getText(), note.getText()),
                    "Angebot gesendet");
            return null;
        });
        dlg.showAndWait();
    }

    private void showInvoiceDialog(PrintRequest r) {
        Dialog<Void> dlg = new Dialog<>();
        dlg.setTitle("Rechnung senden");
        dlg.setHeaderText("Rechnung an " + r.name);
        dlg.getDialogPane().getButtonTypes().addAll(ButtonType.OK, ButtonType.CANCEL);

        GridPane grid = formGrid();
        TextField nr    = new TextField();  nr.setPromptText("2024-001");
        TextField price = new TextField(r.quotePrice != null ? r.quotePrice : "");
        price.setPromptText("z.B. 24,50 €");
        TextField due   = new TextField();  due.setPromptText("JJJJ-MM-TT");
        TextField iban  = new TextField();  iban.setPromptText("IT60 ...");
        TextArea  note  = new TextArea();   note.setPrefRowCount(2);

        grid.add(new Label("Rechnungs-Nr.:"), 0, 0); grid.add(nr,    1, 0);
        grid.add(new Label("Betrag *:"),      0, 1); grid.add(price, 1, 1);
        grid.add(new Label("Zahlungsziel:"),  0, 2); grid.add(due,   1, 2);
        grid.add(new Label("IBAN:"),          0, 3); grid.add(iban,  1, 3);
        grid.add(new Label("Hinweis:"),       0, 4); grid.add(note,  1, 4);

        dlg.getDialogPane().setContent(grid);
        dlg.setResultConverter(bt -> {
            if (bt == ButtonType.OK && !price.getText().isBlank())
                runAsync(() -> api.sendInvoice(r.id, nr.getText(), price.getText(),
                    due.getText(), iban.getText(), note.getText()), "Rechnung gesendet");
            return null;
        });
        dlg.showAndWait();
    }

    /* ── Hilfsmethoden ── */
    private void runAsync(CheckedRunnable action, String successMsg) {
        CompletableFuture.runAsync(() -> {
            try {
                action.run();
                Platform.runLater(() -> {
                    statusBar.setText(successMsg + " – " + java.time.LocalTime.now()
                        .format(java.time.format.DateTimeFormatter.ofPattern("HH:mm:ss")));
                    statusBar.setTextFill(Color.web("#4ade80"));
                    refresh();
                });
            } catch (Exception ex) {
                Platform.runLater(() -> {
                    new Alert(Alert.AlertType.ERROR, "Fehler: " + ex.getMessage()).showAndWait();
                });
            }
        });
    }

    private Label statCard(String value, String label) {
        VBox card = new VBox(2);
        card.setPadding(new Insets(12, 16, 12, 16));
        card.setStyle("-fx-background-color: #111827; -fx-border-color: rgba(255,255,255,0.07); -fx-border-radius: 10; -fx-background-radius: 10; -fx-min-width: 140;");
        Label val = new Label(value);
        val.setFont(Font.font("System", FontWeight.BOLD, 26));
        val.setTextFill(Color.web("#00d4ff"));
        Label lbl = new Label(label);
        lbl.setTextFill(Color.web("#8b97b0"));
        lbl.setFont(Font.font(11));
        card.getChildren().addAll(val, lbl);
        // return the value label so we can update it
        val.setUserData(card);
        HBox.setHgrow(card, Priority.ALWAYS);
        return val;
    }

    private Button iconButton(String text, String color) {
        Button btn = new Button(text);
        btn.setStyle(String.format(
            "-fx-background-color: transparent; -fx-text-fill: %s; -fx-border-color: rgba(255,255,255,0.1); -fx-border-radius: 8; -fx-background-radius: 8; -fx-padding: 6 14; -fx-cursor: hand;", color));
        return btn;
    }

    private GridPane formGrid() {
        GridPane g = new GridPane();
        g.setHgap(12); g.setVgap(10); g.setPadding(new Insets(10));
        g.getColumnConstraints().addAll(
            new ColumnConstraints(110),
            columnCC(true)
        );
        return g;
    }

    private ColumnConstraints columnCC(boolean grow) {
        ColumnConstraints cc = new ColumnConstraints();
        if (grow) cc.setHgrow(Priority.ALWAYS);
        return cc;
    }

    private String statusColor(String status) {
        return switch (status) {
            case "offen"            -> "#f59e0b";
            case "angebot_gesendet" -> "#fb923c";
            case "bestaetigt"       -> "#60a5fa";
            case "bezahlt"          -> "#facc15";
            case "in_bearbeitung"   -> "#a78bfa";
            case "druckfertig"      -> "#34d399";
            case "abholbereit"      -> "#4ade80";
            case "versendet"        -> "#00d4ff";
            case "erledigt"         -> "#86efac";
            case "storniert"        -> "#f87171";
            default                 -> "#8b97b0";
        };
    }

    @FunctionalInterface
    interface CheckedRunnable { void run() throws Exception; }
}
