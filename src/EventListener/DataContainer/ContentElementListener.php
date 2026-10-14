<?php

declare(strict_types=1);

namespace Futura\ContaoDynamicSnippets\EventListener\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;
use Contao\Validator;
use Doctrine\DBAL\Connection;
use Futura\ContaoDynamicSnippets\Snippet\FieldDefinitionFactory;
use Futura\ContaoDynamicSnippets\Snippet\Placeholder;
use Futura\ContaoDynamicSnippets\Snippet\SnippetParser;

/**
 * Registriert für das Inhaltselement "snippet_element" die Felder der gewählten
 * Vorlage dynamisch im DCA und speichert deren Werte gesammelt als JSON in
 * tl_content.snippetData.
 */
class ContentElementListener
{
    public const TYPE = 'snippet_element';

    /** @var array<int, array<string, string>> Geladene JSON-Daten je Datensatz-ID */
    private array $data = [];

    /** @var array<int, array<string, string>> Gesammelte neue Werte je Datensatz-ID */
    private array $pending = [];

    /** @var array<int, bool> Vorlage wurde in diesem Request gewechselt */
    private array $templateSwitched = [];

    public function __construct(
        private readonly Connection $connection,
        private readonly SnippetParser $parser,
        private readonly FieldDefinitionFactory $fieldFactory,
    ) {
    }

    #[AsCallback('tl_content', 'config.onload')]
    public function registerDynamicFields(DataContainer|null $dc = null): void
    {
        if (!$dc || !$dc->id || 'edit' !== Input::get('act')) {
            return;
        }

        $id = (int) $dc->id;

        $row = $this->connection->fetchAssociative(
            'SELECT type, snippetTemplate FROM tl_content WHERE id = ?',
            [$id],
        );

        if (!$row || self::TYPE !== $row['type']) {
            return;
        }

        $templateId = (int) $row['snippetTemplate'];

        // Bei submitOnChange die gerade gewählte Vorlage bevorzugen
        if ('tl_content' === Input::post('FORM_SUBMIT') && null !== Input::post('snippetTemplate')) {
            $posted = (int) Input::post('snippetTemplate');

            if ($posted !== $templateId) {
                $this->templateSwitched[$id] = true;
                $templateId = $posted;
            }
        }

        if ($templateId < 1) {
            return;
        }

        $html = $this->connection->fetchOne('SELECT html FROM tl_snippet_template WHERE id = ?', [$templateId]);

        if (false === $html) {
            return;
        }

        $placeholders = $this->parser->parse((string) $html);

        if ([] === $placeholders) {
            return;
        }

        $fieldNames = [];

        foreach ($placeholders as $placeholder) {
            $fieldName = $placeholder->getFieldName();
            $fieldNames[] = $fieldName;

            $definition = $this->fieldFactory->create($placeholder);
            $definition['load_callback'] = [fn ($value, DataContainer $dc) => $this->loadValue($placeholder, $dc)];
            $definition['save_callback'] = [fn ($value, DataContainer $dc) => $this->saveValue($placeholder, $value, $dc)];

            $GLOBALS['TL_DCA']['tl_content']['fields'][$fieldName] = $definition;
        }

        PaletteManipulator::create()
            ->addLegend('snippet_fields_legend', 'snippet_legend', PaletteManipulator::POSITION_AFTER)
            ->addField($fieldNames, 'snippet_fields_legend', PaletteManipulator::POSITION_APPEND)
            ->applyToPalette(self::TYPE, 'tl_content')
        ;
    }

    #[AsCallback('tl_content', 'config.onsubmit')]
    public function persistSnippetData(DataContainer $dc): void
    {
        $id = (int) $dc->id;

        if (!isset($this->pending[$id]) || !empty($this->templateSwitched[$id])) {
            return;
        }

        $data = array_merge($this->loadData($id), $this->pending[$id]);
        unset($this->pending[$id]);

        $this->connection->update(
            'tl_content',
            ['snippetData' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)],
            ['id' => $id],
        );

        $this->data[$id] = $data;
    }

    private function loadValue(Placeholder $placeholder, DataContainer $dc): mixed
    {
        $value = $this->loadData((int) $dc->id)[$placeholder->name] ?? '';

        if ('image' === $placeholder->type) {
            return Validator::isStringUuid($value) ? StringUtil::uuidToBin($value) : '';
        }

        return $value;
    }

    private function saveValue(Placeholder $placeholder, mixed $value, DataContainer $dc): mixed
    {
        $normalized = match ($placeholder->type) {
            'image' => Validator::isBinaryUuid($value) ? StringUtil::binToUuid($value) : (Validator::isStringUuid((string) $value) ? (string) $value : ''),
            'checkbox' => $value ? '1' : '',
            default => (string) $value,
        };

        $this->pending[(int) $dc->id][$placeholder->name] = $normalized;

        // Den ursprünglich geladenen Wert zurückgeben, damit DC_Table keine
        // (nicht existierende) Spalte aktualisiert. Gespeichert wird im onsubmit.
        return $dc->value;
    }

    /**
     * @return array<string, string>
     */
    private function loadData(int $id): array
    {
        if (!isset($this->data[$id])) {
            $json = $this->connection->fetchOne('SELECT snippetData FROM tl_content WHERE id = ?', [$id]);
            $decoded = json_decode((string) $json, true);

            $this->data[$id] = \is_array($decoded) ? $decoded : [];
        }

        return $this->data[$id];
    }
}
