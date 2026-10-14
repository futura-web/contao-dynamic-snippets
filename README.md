# Contao Dynamic Snippets

Wiederverwendbare HTML-Snippets mit Platzhaltern als Inhaltselemente für **Contao 5**.

Ein Admin legt einmal eine Vorlage an, z. B. einen Embed-Code, eine Karte oder ein Layout-Fragment, und markiert die veränderlichen Stellen mit Platzhaltern. Redakteure wählen die Vorlage danach als normales Inhaltselement aus und füllen nur noch die Felder aus. HTML-Kenntnisse sind dafür nicht nötig.

## Installation

Als lokales Paket (z. B. in `packages/contao-dynamic-snippets` der Contao-Installation):

```json
"repositories": [
    { "type": "path", "url": "packages/contao-dynamic-snippets" }
]
```

```bash
composer require futura/contao-dynamic-snippets:@dev
vendor/bin/contao-console contao:migrate
vendor/bin/contao-console cache:clear
```

Alternativ das Paket in ein Git-Repository legen und über Packagist oder ein VCS-Repository einbinden.

## Benutzung

1. Backend → **Inhalte → Snippet-Vorlagen** → neue Vorlage anlegen.
2. Einen sprechenden **Namen** vergeben, z. B. "Teaser-Kachel" oder "YouTube-Embed". Unter diesem Namen erscheint die Vorlage später in der Auswahl. Optional eine Beschreibung als Notiz für Redakteure ergänzen.
3. HTML einfügen und Platzhalter setzen.
4. Im Artikel ein Inhaltselement vom Typ **Snippet-Element** anlegen, im Dropdown die Vorlage nach Namen wählen, Felder befüllen.

### Platzhalter-Syntax

**Aufbau:** `[[typ:name|Beschriftung]]`

| Bestandteil | Bedeutung | Pflicht |
| --- | --- | --- |
| `typ` | Feldtyp: text, textarea, rte, html, image, url, number, checkbox, select | nein, Standard ist text |
| `name` | Technischer Feldname: Kleinbuchstaben, Ziffern, Unterstrich | ja |
| `Beschriftung` | Text über dem Eingabefeld im Backend | nein, Standard ist der Name |

**Beispiele:**

| Beispiel | Bedeutung |
| --- | --- |
| `[[titel]]` | Einzeiliges Textfeld namens `titel` |
| `[[text:titel\|Überschrift]]` | Dasselbe, im Backend beschriftet mit "Überschrift" |
| `[[textarea:intro\|Einleitung]]` | Mehrzeiliger Text, Zeilenumbrüche werden zu `<br>` |
| `[[rte:inhalt\|Fließtext]]` | Formatierter Text mit TinyMCE |
| `[[html:code\|Embed-Code]]` | Freies HTML mit Code-Editor |
| `[[image:bild\|Bild]]` | Bild aus der Dateiverwaltung, Ausgabe als `<figure>` in der Bildgröße der Vorlage |
| `[[url:link\|Link-Ziel]]` | Link-Adresse mit Seiten- und Dateiauswahl |
| `[[number:anzahl\|Anzahl]]` | Ganzzahl |
| `[[checkbox:rahmen\|Mit Rahmen]]` | Ja/Nein, Ausgabe `1` oder leer |
| `[[select:variante\|Variante\|hell,dunkel]]` | Auswahlfeld mit den Optionen hell und dunkel |
| `[[select:farbe\|Farbe\|red=Rot,blue=Blau]]` | Auswahlfeld, vor dem `=` steht der Wert, dahinter das Label |
| `[[if:link]] Inhalt [[/if]]` | Block nur ausgeben, wenn `link` befüllt oder angehakt ist |

Namen bestehen aus Kleinbuchstaben, Ziffern und Unterstrich. Derselbe Platzhalter darf mehrfach vorkommen und wird im Backend nur einmal abgefragt. Contao-Insert-Tags wie `{{link_url::12}}` funktionieren weiterhin, sowohl in der Vorlage als auch in den eingegebenen Werten.

### Beispiel: Teaser-Kachel

```html
<div class="teaser teaser--[[select:variante|Variante|hell,dunkel]]">
  [[image:bild|Bild]]
  <h3>[[text:titel|Titel]]</h3>
  <p>[[textarea:intro|Einleitung]]</p>
  [[if:link]]<a class="button" href="[[url:link|Link-Ziel]]">[[text:linktext|Linktext]]</a>[[/if]]
</div>
```

### Beispiel: YouTube-Embed

```html
<div class="video-wrapper">
  <iframe src="https://www.youtube-nocookie.com/embed/[[text:video_id|YouTube-Video-ID]]"
          title="[[text:titel|Titel]]" loading="lazy" allowfullscreen></iframe>
</div>
```

## Ausgabe

Werte werden je nach Typ behandelt:

- `text`, `url`, `number`, `select`, `checkbox`: HTML-Sonderzeichen werden maskiert.
- `textarea`: maskiert plus `nl2br`.
- `html`, `rte`: unverändert ausgegeben.
- `image`: über Contao Image Studio als `<figure>` mit `srcset`, Alt-Text und Bildunterschrift aus den Datei-Metadaten.

Das Twig-Template `content_element/snippet_element.html.twig` kann wie üblich überschrieben werden. Verfügbare Variablen: `html` (fertiges Snippet), `snippet` (`id`, `name`, `alias`) und `values` (Rohwerte).

## Rechte

Das Backend-Modul **Snippet-Vorlagen** wird über die Benutzergruppen freigegeben. Da Vorlagen beliebiges HTML enthalten, sollte nur Admins oder vertrauenswürdigen Gruppen Zugriff gegeben werden. Redakteure benötigen nur Zugriff auf das Inhaltselement.

## Technik

- Vorlagen liegen in `tl_snippet_template`.
- Das Inhaltselement speichert die Vorlagen-ID in `tl_content.snippetTemplate` und alle Feldwerte als JSON in `tl_content.snippetData`. Neue Platzhalter erfordern deshalb keine Datenbankmigration.
- Die Felder werden im `config.onload` von `tl_content` dynamisch registriert und per `PaletteManipulator` eingehängt.
- Die Frontend-Ausgabe wird mit `contao.db.tl_snippet_template.<id>` getaggt, damit der HTTP-Cache bei Änderungen an der Vorlage invalidiert wird.

## Löschschutz

Eine Vorlage, die noch von Inhaltselementen verwendet wird, kann nicht gelöscht werden. Das Löschen-Icon ist dann ausgegraut, und ein Löschversuch über "Mehrere bearbeiten" wird mit einer Fehlermeldung abgebrochen.

## Bekannte Grenzen

- Bedingungsblöcke mit `[[if:name]]` können nicht verschachtelt werden.
- Beim Wechsel der Vorlage bleiben bereits eingegebene Werte gleichnamiger Felder erhalten.
