<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: pm_control/templates.php
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

if (!function_exists("DisplayPmControl")) {
    function DisplayPmControl($info) {
        $html = \PHPFusion\Template::getInstance('pmcontrolform');
        $html->set_template(PMC_CLASS.'templates/pmcontrol.html');
        $html->set_tag('openside', fusion_get_function('openside', $info['openside']));
        $html->set_tag('closeside', fusion_get_function('closeside'));

        $html->set_tag('title1', $info['locale'][0]);
        $html->set_tag('title2', $info['locale'][1]);
        $html->set_tag('title3', $info['locale'][2]);
        $html->set_tag('info0', $info['info0']);
        $html->set_tag('info', $info['info']);
        foreach ($info['item'] as $message_id => $data) {
            $html->set_block('pmcontrol', [
                'avatar'   => $data['avatar'],
                'profile'  => $data['profile'],
                'dates'    => $data['dates'],
                'pmtag'    => $data['pmtag'],
            ]);

        }
        echo $html->get_output();
    }
}
