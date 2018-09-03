<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion_db.php
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

\PHPFusion\Admins::getInstance()->setAdminPageIcons("BDY", "<i class='admin-ico fa fa-fw fa-commenting'></i>");

if (!defined("DB_BDAY")) {
	define("DB_BDAY", DB_PREFIX."birthday");
}
if (!defined("DB_BDAY_ST")) {
	define("DB_BDAY_ST", DB_PREFIX."birthday_settings");
}
// Paths
if (!defined("BDAY_PATH")) {
    define("BDAY_PATH", INFUSIONS."birthday_panel/");
}
//Added Locale Constant
if (!defined("BDAY_LOCALE")) {
    if (file_exists(BDAY_PATH."locale/".LANGUAGE.".php")) {
        define("BDAY_LOCALE", BDAY_PATH."locale/".LANGUAGE.".php");
    } else {
        define("BDAY_LOCALE", BDAY_PATH."locale/English.php");
    }
}
if (db_exists(DB_BDAY)) {
	include_once BDAY_PATH."autoload.php";
}
