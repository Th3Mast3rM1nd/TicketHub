<!doctype html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datenschutzerklärung &mdash; Ticketsystem</title>
  <link rel="stylesheet" href="/public/css/app.css">
  <script src="/public/js/theme.js"></script>
  <link rel="stylesheet" href="/public/css/landing.css">
</head>
<body class="legal-page">

<nav class="nav" id="lp-nav">
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <span class="nav-logo-mark">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block"><path d="M3 4 L9 4 Q12 7 15 4 L21 4 Q23 4 23 6 L23 18 Q23 20 21 20 L15 20 Q12 17 9 20 L3 20 Q1 20 1 18 L1 6 Q1 4 3 4 Z"/><polyline points="8,13 11,16 16.5,9"/></svg>
      </span>
      Ticketsystem
    </a>
    <div class="nav-actions nav-pill">
      <a href="/auth/login.php" class="btn btn-ghost">Sign in</a>
      <a href="/auth/register.php" class="btn btn-primary">Get started &rarr;</a>
    </div>
  </div>
</nav>

<main>
  <div class="legal-wrap">
    <h1>Datenschutzerklärung</h1>
    <span class="legal-meta">Stand: Juni 2026 &mdash; Ticketsystem, Modul 151</span>

    <h2>1. Verantwortliche Stelle</h2>
    <p>
      Youssef Khouda<br>
      IT-Lernender, Betriebsinformatik<br>
      BBZ Baselland, Schweiz<br>
      E-Mail: <a href="mailto:youssef.khouda@bbzbl-it.ch">youssef.khouda@bbzbl-it.ch</a>
    </p>

    <h2>2. Erhobene Daten</h2>
    <p>Beim Betrieb von Ticketsystem werden folgende personenbezogene Daten verarbeitet:</p>
    <ul>
      <li><strong>Benutzername</strong> &mdash; frei wählbar, wird im System angezeigt</li>
      <li><strong>E-Mail-Adresse</strong> &mdash; zur Anmeldung und Kontaktaufnahme</li>
      <li><strong>Passwort</strong> &mdash; ausschliesslich als bcrypt-Hash gespeichert, nie im Klartext</li>
      <li><strong>Ticketinhalte</strong> &mdash; Titel, Beschreibung, Status, Priorität und Kommentare zu erfassten Tickets</li>
      <li><strong>Server-Logdaten</strong> &mdash; IP-Adresse, Zeitstempel, Browser-Typ (Apache Access Log); werden maximal 30 Tage aufbewahrt</li>
    </ul>

    <h2>3. Zweck der Datenverarbeitung</h2>
    <p>Die erhobenen Daten werden ausschliesslich für folgende Zwecke verwendet:</p>
    <ul>
      <li>Betrieb und Bereitstellung des Ticketverwaltungssystems</li>
      <li>Authentifizierung und Zugriffskontrolle (Benutzer-/Admin-Rollen)</li>
      <li>Bearbeitung und Nachverfolgung von Supportanfragen</li>
      <li>Sicherstellung der Systemsicherheit und -stabilität</li>
    </ul>

    <h2>4. Rechtsgrundlage</h2>
    <p>
      Die Verarbeitung personenbezogener Daten stützt sich auf Art. 31 des Schweizerischen Datenschutzgesetzes (nDSG) &mdash;
      berechtigte Interessen des Verantwortlichen, soweit diese die Interessen der betroffenen Personen überwiegen.
      Für Nutzer aus dem EU/EWR-Raum gilt ergänzend Art. 6 Abs. 1 lit. b DSGVO (Vertragserfüllung).
    </p>

    <h2>5. Datenspeicherung und Datenübertragung</h2>
    <p>
      Alle Daten werden auf AWS-Infrastruktur in der Region <strong>eu-central-1 (Frankfurt)</strong> gespeichert.
      Es findet kein Transfer in Drittländer ausserhalb der Schweiz oder des Europäischen Wirtschaftsraums statt.
      Die Datenbank (MySQL 8.0) befindet sich in einem privaten Subnetz ohne öffentliche IP-Adresse und ist
      ausschliesslich über die Applikationsschicht erreichbar.
    </p>

    <h2>6. KI-Verarbeitung (Ollama)</h2>
    <p>
      Ticketsystem integriert einen KI-Assistenten auf Basis von <strong>Ollama</strong> &mdash; einer lokalen
      LLM-Laufzeitumgebung. Ollama läuft vollständig auf der eigenen AWS-Infrastruktur des Betreibers.
      <strong>Es werden keinerlei Ticketinhalte, Benutzerdaten oder andere personenbezogene Informationen
      an externe KI-Dienste (z.B. OpenAI, Google, Anthropic) übermittelt.</strong>
      Die Datenverarbeitung durch den KI-Assistenten erfolgt ausschliesslich lokal.
    </p>

    <h2>7. Datensicherheit</h2>
    <p>
      Passwörter werden mit bcrypt (PHP <code>PASSWORD_BCRYPT</code>) gehasht und nie im Klartext gespeichert.
      Alle Datenbankabfragen werden über PDO mit Prepared Statements ausgeführt (kein direktes SQL-Injection-Risiko).
      SSL/TLS-Verschlüsselung wird über Let&rsquo;s Encrypt auf dem Apache-Webserver bereitgestellt.
      Sitzungen werden nach erfolgreicher Anmeldung mittels <code>session_regenerate_id(true)</code> neu generiert.
    </p>

    <h2>8. Betroffenenrechte</h2>
    <p>Sie haben das Recht auf:</p>
    <ul>
      <li><strong>Auskunft</strong> (Art. 25 nDSG) &mdash; Welche Daten über Sie gespeichert sind</li>
      <li><strong>Berichtigung</strong> &mdash; Korrektur unrichtiger Daten</li>
      <li><strong>Löschung</strong> &mdash; Löschung Ihrer Daten, sofern keine Aufbewahrungspflicht besteht</li>
      <li><strong>Einschränkung der Verarbeitung</strong> &mdash; Beschränkung der Datennutzung</li>
      <li><strong>Datenübertragbarkeit</strong> &mdash; Herausgabe Ihrer Daten in einem gängigen Format</li>
      <li><strong>Beschwerde</strong> &mdash; Beim Eidgenössischen Datenschutz- und Öffentlichkeitsbeauftragten (EDÖB), <a href="https://www.edoeb.admin.ch" target="_blank" rel="noopener noreferrer">www.edoeb.admin.ch</a></li>
    </ul>

    <h2>9. Kontakt</h2>
    <p>
      Bei Fragen zur Verarbeitung Ihrer personenbezogenen Daten wenden Sie sich an:<br>
      <a href="mailto:youssef.khouda@bbzbl-it.ch">youssef.khouda@bbzbl-it.ch</a>
    </p>
  </div>
</main>

<footer class="lp-footer">
  <div class="lp-footer-inner">
    <div class="footer-brand">
      <a href="/" class="nav-logo">
        <span class="nav-logo-mark">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block"><path d="M3 4 L9 4 Q12 7 15 4 L21 4 Q23 4 23 6 L23 18 Q23 20 21 20 L15 20 Q12 17 9 20 L3 20 Q1 20 1 18 L1 6 Q1 4 3 4 Z"/><polyline points="8,13 11,16 16.5,9"/></svg>
        </span>
        Ticketsystem
      </a>
      <p class="footer-tagline">An open-source ticket management system with a built-in AI assistant. Self-hosted, privacy-first.</p>
      <p class="footer-copy">&copy; 2026 Ticketsystem. MIT License.</p>
    </div>
    <div class="footer-col">
      <h4>Product</h4>
      <ul>
        <li><a href="/#features">Features</a></li>
        <li><a href="/#ai">AI Assistant</a></li>
        <li><a href="/#how-it-works">How it works</a></li>
        <li><a href="/#faq">FAQ</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Legal</h4>
      <ul>
        <li><a href="/legal/datenschutz.php">Datenschutz</a></li>
        <li><a href="/legal/impressum.php">Impressum</a></li>
        <li><a href="/legal/nutzungsbedingungen.php">Nutzungsbedingungen</a></li>
      </ul>
    </div>
  </div>
</footer>

<script src="/public/js/landing.js"></script>
</body>
</html>
