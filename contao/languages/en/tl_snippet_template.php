<?php

declare(strict_types=1);

$GLOBALS['TL_LANG']['tl_snippet_template']['name'] = ['Name', 'Internal name, shown in the content element selection.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['alias'] = ['Alias', 'Unique short name. Leave empty to generate it automatically.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['description'] = ['Description', 'Optional note for editors.'];
$GLOBALS['TL_LANG']['tl_snippet_template']['html'] = ['HTML snippet', 'The HTML with placeholders in double square brackets, e.g. [[text:headline|Headline]].'];
$GLOBALS['TL_LANG']['tl_snippet_template']['imgSize'] = ['Image size', 'Applied to all [[image:…]] placeholders of this template.'];

$GLOBALS['TL_LANG']['tl_snippet_template']['title_legend'] = 'Template';
$GLOBALS['TL_LANG']['tl_snippet_template']['snippet_legend'] = 'HTML snippet';
$GLOBALS['TL_LANG']['tl_snippet_template']['image_legend'] = 'Image settings';

$GLOBALS['TL_LANG']['tl_snippet_template']['syntax'] = [
    'title' => 'Placeholder syntax',
    'rows' => [
        ['[[name]]', 'Single-line text field'],
        ['[[type:name|Label]]', 'Field with type and back end label'],
        ['[[text:…]] / [[textarea:…]]', 'Single-line / multi-line text (line breaks become <br>)'],
        ['[[rte:…]]', 'Rich text with TinyMCE'],
        ['[[html:…]]', 'Raw HTML with code editor'],
        ['[[image:…]]', 'Image from the file manager, rendered as <figure> in the configured size'],
        ['[[url:…]]', 'Link address with page picker'],
        ['[[number:…]]', 'Integer'],
        ['[[checkbox:…]]', 'Yes/no, outputs 1 or empty'],
        ['[[select:name|Label|a,b=Beta,c]]', 'Select field; without "=" value and label are identical'],
        ['[[if:name]] … [[/if]]', 'Output block only if field "name" is filled or checked'],
    ],
    'note' => 'Names consist of lowercase letters, digits and underscore. Insert tags such as {{link_url::12}} keep working. The same placeholder may appear several times and is asked for only once.',
];
