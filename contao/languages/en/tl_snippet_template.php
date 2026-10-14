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
        ['[[title]]', 'Single-line text field named "title"'],
        ['[[text:title|Headline]]', 'The same, labelled "Headline" in the back end'],
        ['[[textarea:intro|Introduction]]', 'Multi-line text, line breaks become <br>'],
        ['[[rte:content|Body text]]', 'Rich text with TinyMCE'],
        ['[[html:code|Embed code]]', 'Raw HTML with code editor'],
        ['[[image:picture|Picture]]', 'Image from the file manager, rendered as <figure> in the configured size'],
        ['[[url:link|Link target]]', 'Link address with page and file picker'],
        ['[[number:count|Count]]', 'Integer'],
        ['[[checkbox:border|With border]]', 'Yes/no, outputs 1 or empty'],
        ['[[select:variant|Variant|light,dark]]', 'Select field with the options light and dark'],
        ['[[select:color|Color|red=Red,blue=Blue]]', 'Select field; value before the =, label after it'],
        ['[[if:link]] … [[/if]]', 'Output block only if field "link" is filled or checked'],
    ],
    'note' => 'Structure: [[type:name|Label]]. Type and label are optional, without a type a text field is created. Names consist of lowercase letters, digits and underscore. Insert tags such as {{link_url::12}} keep working. The same placeholder may appear several times and is asked for only once.',
];

$GLOBALS['TL_LANG']['tl_snippet_template']['deleteBlocked'] = 'The template "%s" is still used by %d content element(s) and cannot be deleted.';
