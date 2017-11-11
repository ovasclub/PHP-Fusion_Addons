<?php
/*---------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: Terry Broullette (Grimloch)
| Email: webmaster@whisperwillow.com
| Web: http://www.whisperwillow.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

// Check if locale file is available matching the current site locale setting.
$locale = fusion_get_locale("", ASTUDIO_LOCALE);

// Infusion Information
$inf_title = $locale['ast_title'];
$inf_description = $locale['ast_description'];
$inf_version = "3.0";
$inf_developer = "karrak";
$inf_email = "admin@fusionjatek.hu";
$inf_weburl = "http://fusionjatek.hu";
$inf_folder = "avatar_studio";
$inf_image = "avatar.svg";

$enabled_languages = makefilelist(LOCALE, ".|..", TRUE, "folders");
if (!empty($enabled_languages)) {
    foreach ($enabled_languages as $language) {
        $locale = fusion_get_locale("", INFUSIONS.$inf_folder."/locale/".$language.".php");

        // Add
        $mlt_insertdbrow[$language][] = DB_SITE_LINKS." (link_name, link_url, link_visibility, link_position, link_window, link_order, link_status, link_language) VALUES ('".$locale['ast_link']."', 'infusions/avatar_studio/avatar_studio.php', '0', '2', '0', '2', '1', '".$language."')";

        // Delete
        $mlt_deldbrow[$language][] = DB_SITE_LINKS." WHERE link_url='infusions/avatar_studio/avatar_studio.php' AND link_language='".$language."'";
    }
} else {
    $inf_insertdbrow[] = DB_SITE_LINKS." (link_name, link_url, link_visibility, link_position, link_window, link_order, link_status, link_language) VALUES('".$locale['ast_link']."', 'infusions/avatar_studio/avatar_studio.php', '0', '2', '0', '2', '1', '".LANGUAGE."')";
}

$inf_deldbrow[] = DB_SITE_LINKS." WHERE link_url='infusions/avatar_studio/avatar_studio.php'";
