<?php

declare(strict_types=1);

namespace Futura\ContaoDynamicSnippets\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Futura\ContaoDynamicSnippets\ContaoDynamicSnippetsBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoDynamicSnippetsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
