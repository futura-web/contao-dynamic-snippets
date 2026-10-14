<?php

declare(strict_types=1);

namespace Futura\ContaoDynamicSnippets\Snippet;

/**
 * Baut aus einem Platzhalter die DCA-Felddefinition für tl_content.
 */
final class FieldDefinitionFactory
{
    /**
     * @return array<string, mixed>
     */
    public function create(Placeholder $placeholder): array
    {
        $definition = match ($placeholder->type) {
            'textarea' => [
                'inputType' => 'textarea',
                'eval' => ['tl_class' => 'clr'],
            ],
            'html' => [
                'inputType' => 'textarea',
                'eval' => [
                    'allowHtml' => true,
                    'preserveTags' => true,
                    'decodeEntities' => true,
                    'rte' => 'ace|html',
                    'class' => 'monospace',
                    'tl_class' => 'clr',
                ],
            ],
            'rte' => [
                'inputType' => 'textarea',
                'eval' => [
                    'rte' => 'tinyMCE',
                    'helpwizard' => true,
                    'tl_class' => 'clr',
                ],
                'explanation' => 'insertTags',
            ],
            'image' => [
                'inputType' => 'fileTree',
                'eval' => [
                    'filesOnly' => true,
                    'fieldType' => 'radio',
                    'extensions' => '%contao.image.valid_extensions%',
                    'tl_class' => 'clr',
                ],
            ],
            'url' => [
                'inputType' => 'text',
                'eval' => [
                    'rgxp' => 'url',
                    'decodeEntities' => true,
                    'maxlength' => 2048,
                    'dcaPicker' => true,
                    'tl_class' => 'w50',
                ],
            ],
            'select' => [
                'inputType' => 'select',
                'options' => $placeholder->options,
                'eval' => [
                    'includeBlankOption' => true,
                    'chosen' => true,
                    'tl_class' => 'w50',
                ],
            ],
            'checkbox' => [
                'inputType' => 'checkbox',
                'eval' => ['tl_class' => 'w50 m12'],
            ],
            'number' => [
                'inputType' => 'text',
                'eval' => [
                    'rgxp' => 'digit',
                    'maxlength' => 32,
                    'tl_class' => 'w50',
                ],
            ],
            default => [
                'inputType' => 'text',
                'eval' => [
                    'maxlength' => 255,
                    'tl_class' => 'w50',
                ],
            ],
        };

        $definition['label'] = [$placeholder->label, ''];
        $definition['exclude'] = true;

        return $definition;
    }
}
