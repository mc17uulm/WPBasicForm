# WPBasicForm

#### Allgemeine Informationen ####
Um das Formular nutzen zu können, müssen Sie auf einer Wordpress-Seite den `wbf` Shortcode einfügen:

`wbf mail="" from=""`

In das `mail` Attribut fügen Sie die E-Mail-Adresse ein, an welche Sie benachrichtigt sein möchten, wenn eine neue  Checkliste generiert wurde.

In das `from`Attribut fügen Sie Ihren Namen, oder den Namen Ihres Unternehmens ein.

Haben Sie den Shortcode richtig eingefügt, wird Ihnen auf der gewünschten Seite das Formular angezeigt

#### Customization ####

**Formular:**

Um Änderungen am Formular vorzunehmen, müssen Sie das Dokument `objects/form.html` öffnen. Hier können Sie das dargestellte HTML-Gerüst bearbeiten.

**PDF:**
Das pdf Template finden Sie unter: `objects/template.php`.

**E-Mail:**
Das HTML-Template für die E-Mail finden Sie unter: `objects/email_template.php`

**Fehlermeldungen:**
Die Fehlermeldungen werden mittels Javascript generiert. Änderungen können hier am Dokument `js/wbf.js` vorgenommen werden.

**WICHTIG:**
**Alle Änderungen, welche Sie durchführen können die Funktionsweise des Plugins beeinträchtigen. Ändern Sie nur dort den Quellcode, wo Sie sich über die Auswirkungen sicher sind.**

### Fehler ###

> Change permissions for plugin. See documentation.

Die Zugriffberechtigungen sind nicht ausreichend. Die Ordner `tmp` und die Datei `objects/config.json` brauchen Lese- und 
Schreibberechtigung für `www-data`.

> Shortcode invalid

Der Shortcode wurde nicht richtig verwendet. (Funktionsweise siehe unter **Allgemeine Informationen**).

> Leider gabe es einen Server-Fehler. Wenden Sie sich bitte an den Administrator

Im PHP-Skript gab es einen Fehler. Bitte an mich wenden.