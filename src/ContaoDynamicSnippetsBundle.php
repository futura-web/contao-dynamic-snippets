<?php

declare(strict_types=1);

namespace Futura\ContaoDynamicSnippets;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoDynamicSnippetsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
