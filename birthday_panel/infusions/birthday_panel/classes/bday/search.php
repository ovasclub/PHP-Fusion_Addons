<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: birthday_panel/classes/dbay/search.php
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
namespace PHPFusion\Bday;

class Bdaysearch extends BdayServer {
    private static $instance = NULL;

    public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function display() {
        add_to_title(self::$locale['global_200'].self::$locale['BDAY_040']);

        if(isset($_POST['search_name'])) {
            $fname = form_sanitizer($_POST['sname'], '', 'sname');

            if (\defender::safe()) {
            	$result = dbquery("SELECT day, name FROM ".DB_BDAY." WHERE name LIKE '".$fname."'".(multilang_table("BDY") ? " AND language = '".LANGUAGE."'" : ""));
            	$infom = [
            	    'ncount' => dbrows($result),
            	    'name'   => $fname
            	];

            	while ($dat = dbarray($result)) {
            		$dy = explode("-", $dat['day']);
            		$td = mktime (0, 0, 0, $dy[0], $dy[1], date("Y"));
            		$infom['ittem'][] = showdate(self::$locale['BDAY_day'], $td);
            	}
            }
        }

        $info = [
            'opentable'  => "<i class='fa fa-search fa-lg m-r-10'></i>".self::$locale['BDAY_040'],
            'searchform' => self::searchform(),
            'namedat'    => isset($infom) ? $infom : '',
        ];

        Displaysearch($info);
    }

    private function searchform() {

    	$info = [
    	    'openform'  => openform('search_form', 'post', FUSION_REQUEST, ['class' => 'm-t-1']),
    	    'closeform' => closeform(),
    	    'sname'     => form_text('sname', self::$locale['BDAY_041'], '', [
                'required'           => TRUE,
                'append_button'      => TRUE,
                'append_type'        => "submit",
                'append_form_value'  => 'search',
                'append_value'       => "<i class='fa fa-search'></i>",
                'append_button_name' => 'search_name',
                'width'              => '100%',
    	        'max_length'         => 15,
                'class'              => 'no-border'
    	    ]),
    	];
        return $info;
    }
}