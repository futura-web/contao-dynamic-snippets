<?php

declare(strict_types=1);

namespace Futura\ContaoDynamicSnippets\Snippet;

/**
 * Erkennt Platzhalter im Snippet-HTML und ersetzt sie beim Rendern.
 *
 * Syntax:
 *   [[name]]                         Textfeld
 *   [[typ:name]]                     Feld vom Typ "typ"
 *   [[typ:name.w50]]                 mit Contao-Breitenklasse im Backend-Formular
 *   [[typ:name|Label]]               mit Beschriftung im Backend
 *   [[select:name|Label|a,b=Beta]]   Auswahl mit Optionen
 *   [[if:name]] ... [[/if]]          Block nur ausgeben, wenn Wert gesetzt
 */
final class SnippetParser
{
    private const PLACEHOLDER = '/\[\[(?:(?<type>[a-z]+):)?(?<name>[a-z][a-z0-9_]*)(?:\.(?<cssclass>[a-z0-9+]+))?(?:\|(?<label>[^|\]]*))?(?:\|(?<options>[^\]]*))?\]\]/i';

    private const CONDITION = '/\[\[if:(?<name>[a-z][a-z0-9_]*)\]\](?<body>.*?)\[\[\/if\]\]/is';

    /**
     * @return array<string, Placeholder> Platzhalter, indiziert nach Name, in Reihenfolge des Auftretens
     */
    public function parse(string $html): array
    {
        $placeholders = [];

        preg_match_all(self::PLACEHOLDER, $html, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $type = strtolower($match['type'] ?? '');

            if ('if' === $type) {
                continue;
            }

            if (!\in_array($type, Placeholder::TYPES, true)) {
                $type = 'text';
            }

            $name = strtolower($match['name']);

            if (isset($placeholders[$name])) {
                continue;
            }

            $label = trim($match['label'] ?? '');

            if ('' === $label) {
                $label = ucfirst(str_replace('_', ' ', $name));
            }

            $cssClass = trim($match['cssclass'] ?? '');

            $placeholders[$name] = new Placeholder(
                $name,
                $type,
                $label,
                $this->parseOptions($match['options'] ?? ''),
                '' === $cssClass ? null : strtolower($cssClass),
            );
        }

        // Bedingungen ohne eigenes Feld bekommen eine Checkbox
        preg_match_all(self::CONDITION, $html, $conditions, PREG_SET_ORDER);

        foreach ($conditions as $condition) {
            $name = strtolower($condition['name']);

            if (!isset($placeholders[$name])) {
                $placeholders[$name] = new Placeholder($name, 'checkbox', ucfirst(str_replace('_', ' ', $name)));
            }
        }

        return $placeholders;
    }

    /**
     * @param array<string, string> $rendered Fertig gerenderter HTML-Wert je Platzhaltername
     * @param array<string, bool>   $isEmpty  Ob der Wert als "leer" gilt (für [[if:…]])
     */
    public function render(string $html, array $rendered, array $isEmpty): string
    {
        $html = preg_replace_callback(
            self::CONDITION,
            static fn (array $m): string => ($isEmpty[strtolower($m['name'])] ?? true) ? '' : $m['body'],
            $html,
        );

        $html = preg_replace_callback(
            self::PLACEHOLDER,
            static function (array $m) use ($rendered): string {
                if ('if' === strtolower($m['type'] ?? '')) {
                    return '';
                }

                return $rendered[strtolower($m['name'])] ?? '';
            },
            $html,
        );

        // Verwaiste schließende Tags entfernen
        return str_replace('[[/if]]', '', $html);
    }

    /**
     * @return array<string, string>
     */
    private function parseOptions(string $raw): array
    {
        $options = [];

        foreach (explode(',', $raw) as $option) {
            $option = trim($option);

            if ('' === $option) {
                continue;
            }

            [$key, $label] = array_pad(explode('=', $option, 2), 2, null);
            $key = trim($key);
            $options[$key] = trim($label ?? $key);
        }

        return $options;
    }
}
