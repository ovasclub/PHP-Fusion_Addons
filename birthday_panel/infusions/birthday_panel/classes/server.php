<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: birthday_panel/classes/server.php
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

abstract class BdayServer {
    protected static $locale = [];
    private static $oneday = 0;

    public function __construct() {
        require_once BDAY_PATH."templates.php";
        self::$locale = fusion_get_locale("", BDAY_LOCALE);
        self::$oneday = mktime(0, 0, 0, date("m"), date("d")+1, date("Y"));
    }

	public static function CurrentSetup() {

        $settings = dbarray(dbquery("SELECT * FROM ".DB_BDAY_ST." LIMIT 0,1"));

        return $settings;
	}
    /**
     * $filter = int 0-1-2
     * 0 = disabled, 1 = day nameday, 2 = day and tomorrow nameday,
     * @return array
     */
	public static function Nameday($filter = NULL) {
        if (!db_exists(DB_BDAY)) { return NULL; }

        $condition = "day='".date("m-d")."'";
        if (!$filter) {
            return NULL;
        } else if ($filter == 2) {
            $tomorrow = strftime("%m", self::$oneday).'-'.strftime("%d", self::$oneday);
            $condition .= " || day='".$tomorrow."'";
        }

        $result = dbquery("SELECT day, name FROM ".DB_BDAY." WHERE $condition".(multilang_table("BDY") ? " AND language = '".LANGUAGE."'" : ""));
        $info = [];
        while ($dat = dbarray($result)) {
            $key = $dat['day'] == date("m-d") ? "day" : "tomorow";
            $info[$key][] = $dat['name'];
        }

        return $info;
	}

	public static function Birthdate($filter = NULL, $years = NULL) {
		$years = $years ? "(YEAR(CURDATE())-YEAR(user_birthdate)) AS bday," : '';
        $condition = "(MONTH(user_birthdate)='".date("m")."' AND DAY(user_birthdate)='".date("d")."')";

        if (!$filter) {
            return NULL;
        } else if ($filter == 2) {
            $dmonth = strftime("%m", self::$oneday);
            $dday = strftime("%d", self::$oneday);
            $condition .= " || (MONTH(user_birthdate)='".$dmonth."' AND DAY(user_birthdate)='".$dday."')";
        }

        $result = dbquery("SELECT $years user_id, user_name, user_status, user_birthdate
            FROM ".DB_USERS."
            WHERE $condition
            ORDER BY user_name ASC
        ");
        $info = [];
        while ($data = dbarray($result)) {
            $bdate = explode('-', $data['user_birthdate']);
            $key = $bdate[1] == date("m") && $bdate[2] == date("d") ? "day" : "tomorow";
            $info[$key][] = $data;
        }

        return $info;
	}

	public static function UserNameday($filter = NULL) {
        if (!fieldgenerator(DB_USERS." LIKE 'user_nameday'")) { return NULL; }

        $condition = "(MONTH(user_nameday)='".date("m")."' AND DAY(user_nameday)='".date("d")."')";
        if (!$filter) {
            return NULL;
        } else if ($filter == 2) {
            $dmonth = strftime("%m", self::$oneday);
            $dday = strftime("%d", self::$oneday);
            $condition .= " || (MONTH(user_nameday)='".$dmonth."' AND DAY(user_nameday)='".$dday."')";
        }

        $result = dbquery("SELECT user_id, user_name, user_status, user_nameday
            FROM ".DB_USERS."
            WHERE $condition
            ORDER BY user_name ASC
        ");
        $info = [];
        while ($data = dbarray($result)) {
            $bdate = explode('-', $data['user_nameday']);
            $key = $bdate[1] == date("m") && $bdate[2] == date("d") ? "day" : "tomorow";
            $info[$key][] = $data;
        }

        return $info;
	}

    public function zodiak($m, $d) {    //honap, nap
        $zodiacnum = ($m * 100) + $d;
        $zodiacimageset = $this->settings['zodiakimg'];

        $imgprefix = "";

        switch ($zodiacimageset) {
        	case $zodiacimageset == 1: $imgprefix = "set1_"; $ext=".png"; break;
        	case $zodiacimageset == 2: $imgprefix = "set2_"; $ext=".png"; break;
        	case $zodiacimageset == 3: $imgprefix = "set3_"; $ext=".jpg"; break;
        	case $zodiacimageset == 4: $imgprefix = "set4_"; $ext=".jpg"; break;
        }

        switch ($zodiacnum) {
        	case ($zodiacnum < 121): 					        $maizodiac = self::$locale['ZODIAK'][11];	$zodiackep = "hircum"; break;
        	case ($zodiacnum > 120) and ($zodiacnum < 220):		$maizodiac = self::$locale['ZODIAK'][0];	$zodiackep = "aquarius"; break;
        	case ($zodiacnum > 219) and ($zodiacnum < 321):		$maizodiac = self::$locale['ZODIAK'][1];	$zodiackep = "pisces"; break;
        	case ($zodiacnum > 320) and ($zodiacnum < 421):		$maizodiac = self::$locale['ZODIAK'][2];	$zodiackep = "aries"; break;
        	case ($zodiacnum > 420) and ($zodiacnum < 521):		$maizodiac = self::$locale['ZODIAK'][3];	$zodiackep = "taurus"; break;
        	case ($zodiacnum > 520) and ($zodiacnum < 622):		$maizodiac = self::$locale['ZODIAK'][4];	$zodiackep = "gemini"; break;
        	case ($zodiacnum > 621) and ($zodiacnum < 723):		$maizodiac = self::$locale['ZODIAK'][5];	$zodiackep = "cancer"; break;
        	case ($zodiacnum > 722) and ($zodiacnum < 824):		$maizodiac = self::$locale['ZODIAK'][6];	$zodiackep = "leo"; break;
        	case ($zodiacnum > 823) and ($zodiacnum < 924):		$maizodiac = self::$locale['ZODIAK'][7];	$zodiackep = "virgo"; break;
        	case ($zodiacnum > 923) and ($zodiacnum < 1024):	$maizodiac = self::$locale['ZODIAK'][8];	$zodiackep = "libra"; break;
        	case ($zodiacnum > 1023) and ($zodiacnum < 1124):	$maizodiac = self::$locale['ZODIAK'][9];	$zodiackep = "scorpio"; break;
        	case ($zodiacnum > 1123) and ($zodiacnum < 1222):	$maizodiac = self::$locale['ZODIAK'][10];	$zodiackep = "sagittarius"; break;
        	case ($zodiacnum > 1221):				            $maizodiac = self::$locale['ZODIAK'][11];	$zodiackep = "hircum"; break;
        }

        $info = [
            'images' => $imgprefix.$zodiackep.$ext,
            'title'  => $maizodiac
        ];
        return $info;
    }

}
