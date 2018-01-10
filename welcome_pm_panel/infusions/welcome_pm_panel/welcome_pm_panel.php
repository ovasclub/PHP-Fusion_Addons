<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: welcome_pm_panel.php
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

if (iMEMBER) {

    if (infusion_exists('welcome_pm_panel')) {
        $locale = fusion_get_locale("", WPM_LOCALE);

        $wpsettings = dbarray(dbquery("SELECT * FROM ".DB_WELCOME_PM.(multilang_table("WPM") ? " WHERE wp_language='".LANGUAGE."'" : '')." "));

        if ($wpsettings['wp_active'] == 1 && fusion_get_userdata('user_welcome') == 0) {

		    $sender_user_id = 1;
		    $subject = $wpsettings['wp_subject'];
		    $message = $wpsettings['wp_messages'];
		    send_pm(fusion_get_userdata('user_id'), $sender_user_id, $subject, $message);

        if ($wpsettings['wp_sbox'] == 1) {
             $message = str_replace(
                 ["[USERNAME]"],
                 [fusion_get_userdata('user_name')],
                 $locale['wel001e']
             );

            dbquery("INSERT INTO ".DB_SHOUTBOX." (shout_name, shout_message, shout_datestamp, shout_language) VALUES ('Admin', '".$message."', '".time()."', '".LANGUAGE."')");

        }

        dbquery("UPDATE ".DB_USERS." SET user_welcome='1' WHERE user_id='".fusion_get_userdata('user_id')."' LIMIT 1");

        }
    }
}
