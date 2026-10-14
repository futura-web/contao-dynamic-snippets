<?php

declare(strict_types=1);

namespace Futura\ContaoSnippetElements;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoSnippetElementsBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
