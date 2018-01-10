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
//Admin icon megjelen�t�se
\PHPFusion\Admins::getInstance()->setAdminPageIcons("WPM", "<i class='fa fa-commenting fa-lg'></i>");

if (!defined("DB_WELCOME_PM")) {
	define("DB_WELCOME_PM", DB_PREFIX."welcome_pm");
}
//Nyelvi file bet�lt�se, ez k�l�n k�nyt�rb�l a felhaszn�l�knak
if (!defined("WPM_LOCALE")) {
    if (file_exists(INFUSIONS."welcome_pm_panel/locale/".LANGUAGE.".php")) {
        define("WPM_LOCALE", INFUSIONS."welcome_pm_panel/locale/".LANGUAGE.".php");
    } else {
        define("WPM_LOCALE", INFUSIONS."welcome_pm_panel/locale/Hungarian.php");
    }
}
