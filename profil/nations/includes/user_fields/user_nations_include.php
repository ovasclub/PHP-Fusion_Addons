<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_nations_include.php
| Author: karrak
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

function show_flag($item) {
    $icon = IMAGES.'flags/flag_'.lcfirst($item).'.png';
    $icname = translate_country_names($item);
    return "<img style='float:left; margin-right:5px; margin-top:3px;' src=".$icon." title='".$icname."'>";
}

if ($profile_method == "input") {

	$options = [
        'inline'      => TRUE,
        'options'     => $locale['nations'],
        'inner_width' => '200px',
        'error_text'  => $locale['uf_nations_error']
    ] + $options;

    $user_fields = form_select('user_nations', $locale['uf_nations'], $field_value, $options);

} elseif ($profile_method == "display") {
    if ($field_value) {
        $user_fields = [
            'title' => $locale['uf_nations'],
            'value' => show_flag($locale['nations'][$field_value])." ".translate_country_names($locale['nations'][$field_value])
        ];
    }
}
