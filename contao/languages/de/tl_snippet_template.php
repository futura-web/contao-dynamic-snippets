<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_snippet_template']['name'] = ['Name', 'Interner Name der Vorlage, erscheint in der Auswahl im Inhaltselement.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['alias'] = ['Alias', 'Eindeutiger Kurzname. Leer lassen, um ihn automatisch zu erzeugen.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['description'] = ['Beschreibung', 'Optionale Notiz für Redakteure.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['html'] = ['HTML-Snippet', 'Das HTML mit Platzhaltern in doppelten eckigen Klammern, z. B. [[text:headline|Überschrift]].'];
$GLOBALS['TL_LANG']['tl_snippet_template']['imgSize'] = ['Bildgröße', 'Wird auf alle [[image:…]]-Platzhalter dieser Vorlage angewendet.'];

$GLOBALS['TL_LANG']['tl_snippet_template']['title_legend'] = 'Vorlage';
$GLOBALS['TL_LANG']['tl_snippet_template']['snippet_legend'] = 'HTML-Snippet';
$GLOBALS['TL_LANG']['tl_snippet_template']['image_legend'] = 'Bildeinstellungen';

$GLOBALS['TL_LANG']['tl_snippet_template']['syntax'] = [
    'title' => 'Platzhalter-Syntax',
    'rows' => [
        ['[[name]]', 'Einzeiliges Textfeld'],
        ['[[typ:name|Beschriftung]]', 'Feld mit Typ und Beschriftung im Backend'],
        ['[[text:…]] / [[textarea:…]]', 'Text einzeilig / mehrzeilig (Zeilenumbrüche werden zu <br>)'],
        ['[[rte:…]]', 'Formatierter Text mit TinyMCE'],
        ['[[html:…]]', 'Freies HTML mit Code-Editor'],
        ['[[image:…]]', 'Bild aus der Dateiverwaltung, Ausgabe als <figure> in der eingestellten Bildgröße'],
        ['[[url:…]]', 'Link-Adresse mit Seitenauswahl'],
        ['[[number:…]]', 'Ganzzahl'],
        ['[[checkbox:…]]', 'Ja/Nein, Ausgabe 1 oder leer'],
        ['[[select:name|Label|a,b=Beta,c]]', 'Auswahlfeld; ohne "=" ist Wert und Label gleich'],
        ['[[if:name]] … [[/if]]', 'Block nur ausgeben, wenn das Feld "name" befüllt bzw. angehakt ist'],
    ],
    'note' => 'Namen bestehen aus Kleinbuchstaben, Ziffern und Unterstrich. Insert-Tags wie {{link_url::12}} funktionieren weiterhin. Derselbe Platzhalter darf mehrfach vorkommen und wird nur einmal abgefragt.',
];

$GLOBALS['TL_LANG']['tl_snippet_template']['deleteBlocked'] = 'Die Vorlage "%s" wird noch von %d Inhaltselement(en) verwendet und kann nicht gelöscht werden.';
