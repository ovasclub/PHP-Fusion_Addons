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
// Infusion general information
$locale = fusion_get_locale("", BDAY_LOCALE);

$inf_title = $locale['BDAY_000'];
$inf_description = $locale['BDAY_001'];
$inf_version = "1.0";
$inf_developer = "karrak";
$inf_email = "admin@fusionjatek.hu";
$inf_weburl = "http://www.fusionjatek.hu";
$inf_folder = "birthday_panel";
$inf_image = "birthday.svg";

//Administration panel
$inf_adminpanel[] = [
    'title'  => $locale['BDAY_002'],
    'image'  => $inf_image,
    'panel'  => "admin.php",
    'rights' => "BDY",
    'page'   => 5
];

//Multilanguage table for Administration
$inf_mlt[] = [
    'title'  => $inf_title,
    'rights' => "BDY"
];

$inf_newtable[] = DB_BDAY." (
    id       INT(11)     UNSIGNED NOT NULL AUTO_INCREMENT,
    day      VARCHAR(5)           NOT NULL DEFAULT '',
    name     VARCHAR(20)          NOT NULL DEFAULT '',
	language VARCHAR(50)          NOT NULL DEFAULT '".LANGUAGE."',
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci";

$inf_newtable[] = DB_BDAY_ST." (
	nsid          TINYINT(1)    UNSIGNED NOT NULL AUTO_INCREMENT,
	birthday_date INT(11)                NOT NULL DEFAULT '0',
	nameday_date  INT(11)                NOT NULL DEFAULT '0',
	bfrom         TINYINT(1)             NOT NULL DEFAULT '1',
	search        ENUM('0','1')                   DEFAULT '1',
	zodiak        ENUM('0','1')                   DEFAULT '1',
	age           ENUM('0','1')                   DEFAULT '1',
	birthday      TINYINT(1)             NOT NULL DEFAULT '2',
	nameday       TINYINT(1)             NOT NULL DEFAULT '2',
	usernameday   TINYINT(1)             NOT NULL DEFAULT '2',
	nevinfo       TINYINT(1)             NOT NULL DEFAULT '0',
	zodiakimg     TINYINT(1)             NOT NULL DEFAULT '1',
	napszak       ENUM('0','1')          NOT NULL DEFAULT '1',
	nameimg       VARCHAR(100)           NOT NULL DEFAULT '',
	birthimg      VARCHAR(100)           NOT NULL DEFAULT '',
	PRIMARY KEY nsid (nsid)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci";
$ba_mk = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));

$inf_insertdbrow[] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list, panel_restriction, panel_languages) VALUES('".$inf_title."', '".$inf_folder."', '', '1', '3', 'file', '0', '1', '1', '', '3', '".fusion_get_settings('enabled_languages')."')";
$inf_insertdbrow[] = DB_BDAY_ST." VALUES
('1', '".$ba_mk."', '".$ba_mk."', '1', '1', '1', '1', '2', '2', '2', '0', '1', '0', 'http://127.0.0.1/fusion/infusions/birthday_panel/images/csomag.png', 'http://127.0.0.1/fusion/infusions/birthday_panel/images/birthday.png')";

$enabled_languages = makefilelist(LOCALE, ".|..", TRUE, "folders");
if (!empty($enabled_languages)) {
    foreach ($enabled_languages as $language) {
        $locale = fusion_get_locale("", INFUSIONS.$inf_folder."/locale/".$language.".php");
        include INFUSIONS.$inf_folder."/locale/nameday_".$language.".php";
        if (!empty($nday)) {
        	foreach ($nday as $key => $name) {
        		$names = explode(', ', $name);
        		foreach ($names as $bname) {
        			$mlt_insertdbrow[$language][] = DB_BDAY." (day, name, language) VALUES ('".$key."', '".$bname."', '".$language."')";
        		}
        	}
        }
        $mlt_deldbrow[$language][] = DB_BDAY." WHERE language='".$language."'";
    }
} else {
    include INFUSIONS.$inf_folder."/locale/nameday_".LANGUAGE.".php";
    if (!empty($nday)) {
    	foreach ($nday as $key => $name) {
    		$names = explode(', ', $name);
    		foreach ($names as $bname) {
    			$inf_insertdbrow[] = DB_BDAY." (day, name, language) VALUES ('".$key."', '".$bname."', '".LANGUAGE."')";
    		}
    	}
    }
}

$inf_droptable[] = DB_BDAY;
$inf_droptable[] = DB_BDAY_ST;
$inf_deldbrow[] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";
$inf_deldbrow[] = DB_ADMIN." WHERE admin_rights='BDY'";
$inf_deldbrow[] = DB_LANGUAGE_TABLES." WHERE mlt_rights='BDY'";
