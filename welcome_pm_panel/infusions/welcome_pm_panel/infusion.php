<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
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
//Nyelv betöltése
$locale = fusion_get_locale("", WPM_LOCALE);
// Infusion general information
$inf_title = $locale['WPM_000'];
$inf_description = $locale['WPM_001'];
$inf_version = "1.00";
$inf_developer = "karrak";
$inf_email = "admin@fusionjatek.hu";
$inf_weburl = "http://www.fusionjatek.hu";
$inf_folder = "welcome_pm_panel";
$inf_image = "wpm.png";

$inf_adminpanel[] = [
    'title'  => $locale['WPM_000'],
    'image'  => $inf_image,
    'panel'  => "admin.php",
    'rights' => "WPM",
    'page'   => 5
];

//Többnyelvû tábla adata
$inf_mlt[] = [
    'title'  =>  $inf_title,
    'rights' => "WPM"
];

$inf_newtable[] = DB_WELCOME_PM." (
    wp_id          TINYINT(1)    UNSIGNED NOT NULL AUTO_INCREMENT,
    wp_active      ENUM('0','1')                   DEFAULT '0',
    wp_sbox        ENUM('0','1')                   DEFAULT '0',
    wp_language    VARCHAR(50)            NOT NULL DEFAULT '".LANGUAGE."',
PRIMARY KEY (wp_id)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci";

if (!column_exists(DB_USERS, 'user_welcome')) {
    $inf_altertable[] = DB_USERS." ADD user_welcome INT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER user_language";
}

$inf_insertdbrow[] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list, panel_restriction, panel_languages) VALUES('".$inf_title."', '".$inf_folder."', '', '5', '3', 'file', '".USER_LEVEL_MEMBER."', '0', '1', '', '3', '".fusion_get_settings('enabled_languages')."')";

$enabled_languages = makefilelist(LOCALE, ".|..", TRUE, "folders");
if (!empty($enabled_languages)) {
    foreach($enabled_languages as $language) {
        $locale = fusion_get_locale("", INFUSIONS.$inf_folder."/locale/".$language.".php");
		$mlt_insertdbrow[$language][] = DB_WELCOME_PM." (wp_active, wp_sbox, wp_subject, wp_messages, wp_sb_messages, wp_language) VALUES ('0', '0', '".$language."')";

		$mlt_deldbrow[$language][] = DB_WELCOME_PM." WHERE wp_language='".$language."'";
    }
} else {
	$inf_insertdbrow[] = DB_WELCOME_PM." (wp_active, wp_sbox, wp_subject, wp_messages, wp_sb_messages, wp_language) VALUES ('0', '0', '".LANGUAGE."')";
}

$inf_dropcol[] = ['table' => DB_USERS, 'column' => 'user_welcome'];
$inf_droptable[] = DB_WELCOME_PM;
$inf_deldbrow[] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";
$inf_deldbrow[] = DB_ADMIN." WHERE admin_rights='WPM'";
$inf_deldbrow[] = DB_LANGUAGE_TABLES." WHERE mlt_rights='WPM'";
