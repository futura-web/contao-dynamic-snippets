<?php

declare(strict_types=1);

namespace Futura\ContaoDynamicSnippets\Snippet;

/**
 * Ein Platzhalter im Snippet-HTML, z. B. [[image:bild|Bild]].
 */
final class Placeholder
{
    public const TYPES = ['text', 'textarea', 'html', 'rte', 'image', 'url', 'select', 'checkbox', 'number'];

    public const FIELD_PREFIX = 'snippet_';

    /**
     * @param array<string, string> $options  Schlüssel => Label (nur für select)
     * @param string|null           $cssClass Contao-Breitenklasse(n) fürs Backend-Formular, z. B. "w50" oder "w33+clr"
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $label,
        public readonly array $options = [],
        public readonly string|null $cssClass = null,
    ) {
    }

    public function getFieldName(): string
    {
        return self::FIELD_PREFIX.$this->name;
    }
}
