<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_snippet_template']['name'] = ['Name', 'Interner Name der Vorlage, erscheint in der Auswahl im Inhaltselement.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['alias'] = ['Alias', 'Eindeutiger Kurzname. Leer lassen, um ihn automatisch zu erzeugen.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['description'] = ['Beschreibung', 'Optionale Notiz für Redakteure.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['html'] = ['HTML-Snippet', 'Das HTML mit Platzhaltern in doppelten eckigen Klammern, z. B. [[text:headline|Überschrift]].'];
$GLOBALS['TL_LANG']['tl_snippet_template']['imgSize'] = ['Bildgröße', 'Wird auf alle Bild-Platzhalter (Typ image) dieser Vorlage angewendet.'];

$GLOBALS['TL_LANG']['tl_snippet_template']['title_legend'] = 'Vorlage';
$GLOBALS['TL_LANG']['tl_snippet_template']['snippet_legend'] = 'HTML-Snippet';
$GLOBALS['TL_LANG']['tl_snippet_template']['image_legend'] = 'Bildeinstellungen';

$GLOBALS['TL_LANG']['tl_snippet_template']['syntax'] = [
    'title' => 'Platzhalter-Syntax',
    'rows' => [
        ['[[titel]]', 'Einzeiliges Textfeld namens "titel"'],
        ['[[text:titel|Überschrift]]', 'Dasselbe, im Backend beschriftet mit "Überschrift"'],
        ['[[textarea:intro|Einleitung]]', 'Mehrzeiliger Text, Zeilenumbrüche werden zu <br>'],
        ['[[rte:inhalt|Fließtext]]', 'Formatierter Text mit TinyMCE'],
        ['[[html:code|Embed-Code]]', 'Freies HTML mit Code-Editor'],
        ['[[image:bild|Bild]]', 'Bild aus der Dateiverwaltung, Ausgabe als <figure> in der eingestellten Bildgröße'],
        ['[[url:link|Link-Ziel]]', 'Link-Adresse mit Seiten- und Dateiauswahl'],
        ['[[number:anzahl|Anzahl]]', 'Ganzzahl'],
        ['[[checkbox:rahmen|Mit Rahmen]]', 'Ja/Nein, Ausgabe 1 oder leer'],
        ['[[select:variante|Variante|hell,dunkel]]', 'Auswahlfeld mit den Optionen hell und dunkel'],
        ['[[select:farbe|Farbe|red=Rot,blue=Blau]]', 'Auswahlfeld; vor dem = steht der Wert, dahinter das Label'],
        ['[[if:link]] Inhalt [[/if]]', 'Block nur ausgeben, wenn das Feld "link" befüllt bzw. angehakt ist'],
    ],
    'note' => 'Aufbau: [[typ:name|Beschriftung]]. Typ und Beschriftung sind optional, ohne Typ entsteht ein Textfeld. Namen bestehen aus Kleinbuchstaben, Ziffern und Unterstrich. Insert-Tags wie {{link_url::12}} funktionieren weiterhin. Derselbe Platzhalter darf mehrfach vorkommen und wird nur einmal abgefragt.',
];

$GLOBALS['TL_LANG']['tl_snippet_template']['deleteBlocked'] = 'Die Vorlage "%s" wird noch von %d Inhaltselement(en) verwendet und kann nicht gelöscht werden.';
