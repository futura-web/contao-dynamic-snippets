<?php

declare(strict_types=1);

use Contao\ArrayUtil;

// Backend-Modul "Snippet-Vorlagen" direkt nach "Artikel" einhängen
ArrayUtil::arrayInsert($GLOBALS['BE_MOD']['content'], 1, [
    'snippet_templates' => [
        'tables' => ['tl_snippet_template'],
    ],
]);
