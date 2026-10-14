<?php

declare(strict_types=1);

namespace Futura\ContaoSnippetElements\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Contao\Validator;
use Doctrine\DBAL\Connection;
use Futura\ContaoSnippetElements\Snippet\Placeholder;
use Futura\ContaoSnippetElements\Snippet\SnippetParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

#[AsContentElement(type: 'snippet_element', category: 'includes', template: 'content_element/snippet_element')]
class SnippetElementController extends AbstractContentElementController
{
    public const TYPE = 'snippet_element';

    public function __construct(
        private readonly Connection $connection,
        private readonly SnippetParser $parser,
        private readonly Studio $studio,
        private readonly Environment $twig,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $snippet = $this->connection->fetchAssociative(
            'SELECT id, name, alias, html, imgSize FROM tl_snippet_template WHERE id = ?',
            [(int) $model->snippetTemplate],
        );

        if (!$snippet) {
            return new Response();
        }

        $data = json_decode((string) $model->snippetData, true);
        $data = \is_array($data) ? $data : [];

        $rendered = [];
        $isEmpty = [];

        foreach ($this->parser->parse((string) $snippet['html']) as $name => $placeholder) {
            $value = (string) ($data[$name] ?? '');
            $rendered[$name] = $this->renderValue($placeholder, $value, $snippet);
            $isEmpty[$name] = '' === trim($value);
        }

        $html = $this->parser->render((string) $snippet['html'], $rendered, $isEmpty);
        $html = $this->insertTagParser->replaceInline($html);

        $template->set('html', $html);
        $template->set('snippet', [
            'id' => (int) $snippet['id'],
            'name' => $snippet['name'],
            'alias' => $snippet['alias'],
        ]);
        $template->set('values', $data);

        $this->tagResponse(['contao.db.tl_snippet_template.'.$snippet['id']]);

        return $template->getResponse();
    }

    /**
     * @param array<string, mixed> $snippet
     */
    private function renderValue(Placeholder $placeholder, string $value, array $snippet): string
    {
        if ('' === $value) {
            return '';
        }

        return match ($placeholder->type) {
            'html', 'rte' => $value,
            'textarea' => nl2br(StringUtil::specialchars($value)),
            'image' => $this->renderImage($value, $snippet['imgSize']),
            default => StringUtil::specialchars($value),
        };
    }

    private function renderImage(string $uuid, mixed $imgSize): string
    {
        if (!Validator::isStringUuid($uuid)) {
            return '';
        }

        $size = StringUtil::deserialize($imgSize);

        $figure = $this->studio
            ->createFigureBuilder()
            ->fromUuid($uuid)
            ->setSize($size ?: null)
            ->buildIfResourceExists()
        ;

        if (null === $figure) {
            return '';
        }

        return $this->twig->render('@Contao/component/_figure.html.twig', ['figure' => $figure]);
    }
}
