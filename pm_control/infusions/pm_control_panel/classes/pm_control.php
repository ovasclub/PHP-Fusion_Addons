<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: pm_control_panel/classes/pm_control.php
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
class PmControl {
    private static $instance = NULL;
    private static $locale = [];
    public static $pmsettings = [];

    public function __construct() {
        require_once INCLUDES."infusions_include.php";
        self::$locale = fusion_get_locale("", PMC_LOCALE);
        $pmsettings = self::PmSettings();
    }

    public static function getInstance($key = TRUE) {
        if (self::$instance === NULL) {
            self::$instance = new static();
        }
       return self::$instance;
    }

    public static function PmSettings() {
        if (empty(self::$pmsettings)) {
            self::$pmsettings = get_settings("pm_control_panel");
        }

        return self::$pmsettings;
    }

    public static function DisplayPmcontrol() {
        if (self::CheckDay()) { self::DailyChecks(); }

        if (self::CountMessages()) { self::PmData(); }
    }

    private static function PmOptions() {
        $moptions = fusion_get_settings('pm_inbox_limit');
    	return $moptions;
    }

    private static function CountMessages() {
        $msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to=:mto AND message_read=:mread AND message_folder=:mfolder ORDER BY message_datestamp DESC LIMIT :limit", [':mto' => fusion_get_userdata('user_id'), ':mread' => '0', ':mfolder' => '0', ':limit' => self::$pmsettings['limit']]);
    	return $msg_count;
    }

    private static function PmUser() {
	    $Queryresult = "SELECT ts.*, tu.user_id, tu.user_name, tu.user_avatar, tu.user_status
	        FROM ".DB_MESSAGES." ts
	        LEFT JOIN ".DB_USERS." tu ON ts.message_from = tu.user_id
	        WHERE message_to ='".fusion_get_userdata('user_id')."' AND message_folder='0' AND message_read='0'
	        ORDER BY message_datestamp DESC
	        LIMIT ".self::$pmsettings['limit']."";

	    $result = dbquery($Queryresult);
        $info = [];
        while($data = dbarray($result)) {
            $info[$data['message_id']]	= $data;
        }

    	return $info;
    }

    private static function PmData() {
    	$data = self::PmUser();
        if (!empty($data)) {
        	$info['openside'] = self::$locale['PMC_010'];
        	$info['locale'] = [self::$locale['PMC_011'], self::$locale['PMC_012'], self::$locale['PMC_013']];
        	$info['info0'] = self::$locale['PMC_D00'];
        	$info['info'] = sprintf(self::$locale['PMC_D01'], self::$pmsettings['days']);
            foreach ($data as $key => $messages) {
            	$text = "<div class='pull-left m-b-10'>".$messages['message_smileys'] == "y" ? parseubb(parsesmileys($messages['message_message'])) : parseubb($messages['message_message'])."</div>";
                $inf = [
                    'avatar'   => display_avatar($messages, '50px', '', '', ''),
                    'profile'  => profile_link($messages['user_id'], $messages['user_name'], $messages['user_status']),
                    'dates'    => showdate(self::$locale['PMC_date'], $messages['message_datestamp']),
                    'pmtag'    => self::PmTags($messages, $messages['message_subject'], $text),
                ];

                $info['item'][$messages['message_id']] = $inf;
            }

            DisplayPmControl($info);
        }
    }

    private static function CheckDay() {
        $days = date(mktime(0,0,0,date("m"),date("d"),date("Y")));
        $info = (self::$pmsettings['control'] < $days || empty(self::$pmsettings['control'])) ? TRUE : FALSE;
    	return $info;
    }

    private static function DailyChecks() {
        $days = self::$pmsettings['days'];
        $dates = (time() - ($days * 86400));

        $result = dbquery("SELECT *
            FROM ".DB_MESSAGES."
            WHERE  message_folder=:folder AND message_read=:read AND message_datestamp<:datest
            ORDER BY message_datestamp DESC", [':folder' => '0', ':read' => '0', ':datest' => $dates]
        );

        if (dbrows($result)) {
		    // Removing unread messages from the database
            $delete = dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_folder=:folder AND message_read=:read AND message_datestamp<:datest", [':folder' => '0', ':read' => '0', ':datest' => $dates]);

            $message= dbrows($result).self::$locale['PMC_021'].$days.self::$locale['PMC_022'];
            dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_user, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES('1', '1', '1', '".self::$locale['PMC_023']."', '".$message."', 'y', '0', '".time()."', '0')");
        }

        $inputSettings = [
            'settings_name' => 'control', 'settings_value' => time(), 'settings_inf' => 'pm_control_panel',
        ];

        dbquery_insert(DB_SETTINGS_INF, $inputSettings, 'update', ['primary_key' => 'settings_name']);
    }

    private static function PmTags($data, $subject, $text) {

        add_to_jquery("$('[data-toggle=\"user-pmtags\"]').popover();");

        $title = "<div class='user-pmtags'><div class='pull-left m-r-10'>".display_avatar($data, '50px', '', FALSE, '')."</div>";
        $title .= "<div class='clearfix'>".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</div>";
        $content = $text."<a class='btn btn-block btn-primary' href='".BASEDIR."messages.php?msg_send=".$data['user_id']."'>".self::$locale['send_message']."</a>";
        $content .= "<a class='btn btn-block btn-primary' href='".BASEDIR."messages.php?folder=inbox&amp;msg_read=".$data['message_id']."'>".self::$locale['PMC_020']."</a>";
        $html = '<a class="strong pointer" tabindex="0" role="button" data-html="true" data-trigger="focus" data-placement="top" data-toggle="user-pmtags" title="'.$title.'" data-content="'.$content.'">';
        $html .= "<span class='user-label'>".$subject."</span>";
        $html .= "</a>\n";

        return $html;
    }

}