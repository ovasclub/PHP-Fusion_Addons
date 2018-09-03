<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: autoload.php
| Author: PHP-Fusion Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
spl_autoload_register(function ($className) {

    $autoload_register_paths = [
        'PHPFusion\\Bday\\BdayServer' => BDAY_PATH."classes/server.php",
        'PHPFusion\\Bday\\BdayPanel'  => BDAY_PATH."classes/bday/bdaypanel.php",
        'PHPFusion\\Bday\\Bdaysearch' => BDAY_PATH."classes/bday/search.php",
        'PHPFusion\\Bday\\Bdayadmin'  => BDAY_PATH."classes/admin/admin.php"
    ];

    if (isset($autoload_register_paths[$className])) {
        $fullPath = $autoload_register_paths[$className];
        if (is_file($fullPath)) {
            require $fullPath;
        }
    }
});