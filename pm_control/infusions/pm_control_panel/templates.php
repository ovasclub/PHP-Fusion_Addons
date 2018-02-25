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

if (version_compare(9.0, fusion_get_settings('version'), ">")) {

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
                    'pmtag'    => $data['pmtag']
                ]);

            }
            echo $html->get_output();
        }
    }
} else {
    if (!function_exists("DisplayPmControl")) {
        function DisplayPmControl($info) {

            openside($info['openside']);
            echo "<div class='m-b-10 text-center'>".$info['info0']."</div>
            <div class='table-responsive'><table class='table table-bordered clear'>
            <thead>
                <tr>
                    <th><small class='text-uppercase strong text-lighter'>".$info['locale'][0]."</small></th>
                    <th><small class='text-uppercase strong text-lighter'>".$info['locale'][1]."</small></th>
                    <th><small class='text-uppercase strong text-lighter'>".$info['locale'][2]."</small></th>
                </tr>
            </thead>
            <tbody>";
            foreach ($info['item'] as $message_id => $data) {
                echo "<tr>
                <td>
                    <div class='clearfix'>
                        <div class='pull-left m-r-10'>".$data['avatar']."</div>
                        ".$data['profile']."
                    </div>
                </td>
                <td>".$data['pmtag']."</td>
                <td>".$data['dates']."</td>
                </tr>";
            }
            echo "</tbody>
            </table></div>";
            echo "<div class='bg-info text-center'>".$info['info']."</div>";
    	    closeside();
        }
    }
}