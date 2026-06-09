<?php
$page_title = 'AGB – 3D Druck Südtirol';
$page_desc  = 'Allgemeine Geschäftsbedingungen von 3D Druck Südtirol.';
require_once 'includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="/">Start</a></li>
                <li class="breadcrumb-item active">AGB</li>
            </ol>
        </nav>
        <h1 class="mt-2 mb-3">Allgemeine Geschäftsbedingungen</h1>
        <p class="text-muted small">Stand: <?= date('d.m.Y') ?></p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card fade-in-up">

                    <h3 style="font-size:1.2rem">§ 1 Geltungsbereich</h3>
                    <p>
                        Diese Allgemeinen Geschäftsbedingungen (AGB) gelten für alle Aufträge zwischen
                        <strong>Markus Stufer</strong>, Schennastraße 81, 39017 Schenna (Scena), Südtirol, Italien
                        (nachfolgend „Anbieter") und dem Auftraggeber (nachfolgend „Kunde")
                        in Bezug auf 3D-Druckleistungen, die über die Website <em>3ddruck-suedtirol.it</em> oder
                        per E-Mail beauftragt werden.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 2 Vertragsschluss</h3>
                    <p>
                        Das Absenden einer Druckanfrage (über das Online-Formular oder per E-Mail) stellt ein
                        unverbindliches Angebot des Kunden dar. Der Vertrag kommt erst durch die schriftliche
                        Auftragsbestätigung des Anbieters (per E-Mail) zustande.
                        Der Anbieter behält sich vor, Aufträge ohne Angabe von Gründen abzulehnen.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 3 Leistungsumfang</h3>
                    <p>
                        Der Anbieter druckt vom Kunden bereitgestellte 3D-Dateien (STL, 3MF, OBJ o. ä.) mit FDM-Drucktechnik.
                        Druckparameter (Material, Schichthöhe, Füllung, Farbe) werden vor Auftragserteilung per E-Mail abgestimmt.
                        Maß- und Qualitätsschwankungen, die drucktechnisch unvermeidbar sind (±0,5 mm), gelten als vertragsgemäß.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 4 Kundenpflichten und Dateiqualität</h3>
                    <p>
                        Der Kunde ist dafür verantwortlich, druckfähige Dateien einzureichen. Der Anbieter führt eine
                        einfache Sichtprüfung durch, übernimmt jedoch keine Haftung für Druckfehler, die auf mangelhaften
                        oder fehlerhaften Eingabedateien beruhen. Der Kunde versichert, über alle nötigen Rechte an den
                        eingereichten Dateien zu verfügen und keine urheberrechtlich geschützten Designs ohne Genehmigung
                        einzureichen.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 5 Preise und Zahlung</h3>
                    <p>
                        Preise werden individuell nach Aufwand (Material, Druckzeit, Nachbearbeitung) kalkuliert und
                        im Angebot per E-Mail mitgeteilt. Die Zahlung erfolgt per Überweisung oder nach Vereinbarung
                        vor Versand / Abholung. Bei Aufträgen über 50 € kann eine Anzahlung von 50 % verlangt werden.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 6 Lieferung und Abholung</h3>
                    <p>
                        Fertige Objekte können in Schenna persönlich abgeholt oder per Post/Kurier versendet werden.
                        Versandkosten trägt der Kunde. Lieferfristen sind Richtwerte und nicht verbindlich, sofern
                        nicht ausdrücklich schriftlich vereinbart.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 7 Gewährleistung und Haftung</h3>
                    <p>
                        Bei nachweisbaren Druckfehlern, die nicht auf der Kundendatei beruhen, bietet der Anbieter
                        kostenlosen Nachdruck oder Rückerstattung an. Die Haftung ist auf den Auftragswert begrenzt.
                        Eine Haftung für Folgeschäden ist ausgeschlossen, soweit gesetzlich zulässig.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 8 Urheberrecht und Nutzungsrechte</h3>
                    <p>
                        Der Anbieter behält sich vor, gedruckte Objekte (ohne erkennbare Personenbezüge) für
                        Referenz- und Marketingzwecke zu fotografieren und zu veröffentlichen.
                        Der Kunde kann dem widersprechen, indem er dies bei der Auftragserteilung schriftlich mitteilt.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 9 Datenschutz</h3>
                    <p>
                        Die Verarbeitung personenbezogener Daten erfolgt gemäß unserer
                        <a href="/datenschutz.php" class="text-accent">Datenschutzerklärung</a>.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 10 Anzuwendendes Recht und Gerichtsstand</h3>
                    <p>
                        Es gilt ausschließlich italienisches Recht. Gerichtsstand ist Bozen (Bolzano), Italien.
                        Bei Verbrauchern gelten die gesetzlich vorgeschriebenen Gerichtsstände.
                    </p>

                    <h3 class="mt-4" style="font-size:1.2rem">§ 11 Salvatorische Klausel</h3>
                    <p>
                        Sollten einzelne Bestimmungen dieser AGB unwirksam sein, bleibt die Wirksamkeit der
                        übrigen Bestimmungen unberührt.
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once 'includes/footer.php'; ?>
