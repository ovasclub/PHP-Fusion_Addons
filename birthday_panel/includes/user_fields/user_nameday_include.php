<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_nameday_include.php
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

// Display user field input
if ($profile_method == "input") {
    if (isset($field_value) && $field_value != "1900-01-01") {
        $userNameday = date('Y-m-d', strtotime($field_value));
    } else {
        $userNameday = date('Y-m-d', strtotime('today'));
    }

    $options += [
        'inline'          => TRUE,
        'type'            => 'date',
        'width'           => '250px',
        'error_text'      => $locale['uf_nameday_error'],
        'showTime'        => FALSE,
        'date_format_js'  => 'YYYY-M-DD',
        'date_format_php' => 'Y-m-d',
    ];

	$user_fields = form_datepicker('user_nameday', $locale['uf_nameday'], $userNameday, $options);

// Display in profile
} elseif ($profile_method == "display") {
    if ($field_value != "1900-01-01" && $field_value != "") {

        $userNameday = explode("-", $field_value);
        $lastday = mktime(0, 0, 0, $userNameday[1], $userNameday[2], date("Y"));
        $dayDay = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

        $day_1 = ($dayDay == $lastday ? 0 : ($dayDay > $lastday ? 1 : 2));
        $user_fields = [
            'title' => $locale['uf_nameday'],
            'value' => showdate($locale['uf_nameday_date'], $lastday).$locale['uf_nameday_day'][$day_1].(date('m') == $userNameday['1'] && date('d') == $userNameday['2'] ? $locale['uf_greet'] : "")
        ];

    }
}
