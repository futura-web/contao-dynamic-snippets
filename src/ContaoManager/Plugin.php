<?php

declare(strict_types=1);

namespace Futura\ContaoSnippetElements\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Futura\ContaoSnippetElements\ContaoSnippetElementsBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoSnippetElementsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
