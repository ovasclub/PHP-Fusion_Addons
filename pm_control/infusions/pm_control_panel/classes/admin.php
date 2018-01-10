<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: pm_control_panel/classes/admin.php
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
class PmControlAdmin {
    private static $instance = NULL;
    private static $locale = [];
    private static $pmsettings = [];

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

    public function DisplayAdmin() {
        $allowed_section = array("pm_messages", "pm_count", 'settings');

        $_GET['section'] = isset($_GET['section']) && in_array($_GET['section'], $allowed_section) ? $_GET['section'] : 'pm_messages';
        \PHPFusion\BreadCrumbs::getInstance()->addBreadCrumb(['link' => PMC_CLASS."admin.php".fusion_get_aidlink(), 'title' => self::$locale['PMC_003']]);
        switch ($_GET['section']) {
            case "pm_messages":
                add_to_title(self::$locale['PMC_050']);
                \PHPFusion\BreadCrumbs::getInstance()->addBreadCrumb(['link' => PMC_CLASS."admin.php".fusion_get_aidlink(), "title" => self::$locale['PMC_050']]);
                break;
            case "pm_count":
                add_to_title(self::$locale['PMC_051']);
                \PHPFusion\BreadCrumbs::getInstance()->addBreadCrumb(['link' => PMC_CLASS."admin.php".fusion_get_aidlink(), "title" => self::$locale['PMC_051']]);
                break;
            case "settings":
                add_to_title(self::$locale['PMC_052']);
                \PHPFusion\BreadCrumbs::getInstance()->addBreadCrumb(['link' => PMC_CLASS."admin.php".fusion_get_aidlink(), "title" => self::$locale['PMC_052']]);
                break;
            default:
        }

        $master_tab_title['title'][] = self::$locale['PMC_050'];
        $master_tab_title['id'][] = "pm_messages";
        $master_tab_title['icon'][] = "fa fa-envelope-square";

        $master_tab_title['title'][] = self::$locale['PMC_051'];
        $master_tab_title['id'][] = "pm_count";
        $master_tab_title['icon'][] = "fa fa-info";

        $master_tab_title['title'][] = self::$locale['PMC_052'];
        $master_tab_title['id'][] = "settings";
        $master_tab_title['icon'][] = "fa fa-cogs";

        opentable(self::$locale['PMC_053']);
        echo opentab($master_tab_title, $_GET['section'], "pm_messages", TRUE, '', 'section', ['rowstart']);
        switch ($_GET['section']) {
            case "pm_messages":
                self::pm_messages();
                break;
            case "pm_count":
                self::pm_count();
                break;
            case "settings":
                self::pm_settings();
                break;
            default:
                break;
        }
        echo closetab();
        closetable();
    }

    private function pm_settings() {
    	$pmsettings = PmControl::getInstance(TRUE)->PmSettings();
        if (isset($_POST['save'])) {
            $inputArray = [
                'limit' => form_sanitizer($_POST['limit'], 0, "limit"),
                'days'   => form_sanitizer($_POST['days'], 0, "days"),
                'jq'  => isset($_POST['jq']) ? form_sanitizer($_POST['jq'], 0, "jq") : $pmsettings['jq'],
            ];

            if (\defender::safe()) {
                foreach ($inputArray as $settings_name => $settings_value) {
                    $inputSettings = [
                        "settings_name" => $settings_name, "settings_value" => $settings_value, "settings_inf" => "pm_control_panel",
                    ];
                    dbquery_insert(DB_SETTINGS_INF, $inputSettings, "update", ["primary_key" => "settings_name"]);
                }
                addNotice("success", self::$locale['PMC_100']);
                redirect(clean_request("", [""], FALSE));
            }
        }

        echo "<div class='well'>".self::$locale['PMC_072']."</div>";
        openside('');
        echo openform('pmcontrol', 'post', FUSION_REQUEST);
        echo form_text('limit', self::$locale['PMC_054'], $pmsettings['limit'], [
            'inline'      => TRUE,
            'type'        => 'number',
            'inner_width' => '60px',
            'ext_tip'     => self::$locale['PMC_055']
        ]);
        echo form_text('days', self::$locale['PMC_056'], $pmsettings['days'], [
            'inline'      => TRUE,
            'type'        => 'number',
            'inner_width' => '60px',
            'ext_tip'     => self::$locale['PMC_057']
        ]);

        echo form_checkbox('jq', self::$locale['PMC_058'], $pmsettings['jq'], [
            'inline'  => TRUE,
            'ext_tip' => self::$locale['PMC_059']
        ]);
        echo form_button('save', self::$locale['save'], self::$locale['save'], ['class' => 'btn-danger', 'icon' => 'fa fa-plus']);
        echo closeform();
        closeside();

    }

    private function pm_messages() {
        $aidlink = fusion_get_aidlink();

        if(isset($_GET['delete_message_id']) AND isnum($_GET['delete_message_id'])) {
            if (dbcount("('message_id')", DB_MESSAGES, "message_id='".intval($_GET['delete_message_id'])."'") && \defender::safe()) {
                dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_id=".intval($_GET['delete_message_id']));
                addNotice('success', self::$locale['PMC_101']);
                redirect(clean_request('', ['section', 'delete_message_id'], FALSE));
            }
        }

        if(isset($_POST['delete_all'])) {
            $input = (isset($_POST['message_id'])) ? explode(",", form_sanitizer($_POST['message_id'], '', 'message_id')) : "";
            if (!empty($input)) {
                $i = 0;
                foreach ($input as $message_id) {
                    if (dbcount("('message_id')", DB_MESSAGES, "message_id='".intval($message_id)."'") && \defender::safe()) {
                        dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_id='".intval($message_id)."'");
                        $i++;
                    }
                }
                addNotice('warning', sprintf(self::$locale['PMC_102'], $i));
                redirect(clean_request('', ['section', 'delete_message_id'], FALSE));
            }
            addNotice("warning", self::$locale['PMC_103']);
        }

        $rowstart = (isset($_GET['rowstart']) AND isnum($_GET['rowstart'])) ? $_GET['rowstart'] : 0;

        $pm_per_page = fusion_get_settings('comments_per_page');

        $rows = dbrows(dbquery("SELECT * FROM ".DB_MESSAGES));

        if ($rows) {
            $message_data = dbquery("SELECT x1.*, x2.user_name as from_name, x2.user_id as id_from, x3.user_name as to_name, x3.user_id as id_to
                FROM ".DB_MESSAGES." as x1
                LEFT JOIN ".DB_USERS." as x2 on x2.user_id = x1.message_from
                LEFT JOIN ".DB_USERS." as x3 on x3.user_id = x1.message_to
                WHERE x1.message_from = x2.user_id AND x1.message_to = x3.user_id
                ORDER BY message_id DESC
                LIMIT ".$rowstart.",".$pm_per_page
            );

            $allnotread = dbcount("(message_id)", DB_MESSAGES, " message_folder ='0' && message_read = '0' ");
            $allread = dbcount("(message_id)", DB_MESSAGES, " message_folder ='0' && message_read = '1' ");
            $allarchiv = dbcount("(message_id)", DB_MESSAGES, " message_folder ='2' && message_read = '1' ");

            openside('');

            if ($rows > $pm_per_page) {
                echo "<div class='clearfix'>";
                    echo "<div class='pull-right'>".makepagenav($rowstart, $pm_per_page, $rows, 3, FUSION_SELF.$aidlink."&amp;section=pm_messages&amp;")."</div>";
                echo "</div>\n";
            }
            echo "<div class='well'>".self::$locale['PMC_060']."</div>";
            echo "<div class='text-right m-b-10'>".sprintf(self::$locale['PMC_071'], $allnotread, $allread, $allarchiv)."</div>";

            echo "<div class='table-responsive'>";

            echo "<table class='table table-hover'>
		        <thead>
			        <tr>
				        <th></th>
				        <th>".self::$locale['PMC_061']."</th>
				        <th>".self::$locale['PMC_062']."</th>
				        <th>".self::$locale['PMC_063']."</th>
				        <th>".self::$locale['PMC_064']."</th>
				        <th>".self::$locale['PMC_065']."</th>
				        <th>".self::$locale['PMC_066']."</th>
				        <th>".self::$locale['PMC_067']."</th>
				        <th>".self::$locale['delete']."</th>
			       </tr>
		       </thead>
		       <tbody>";
            echo openform('pmcontrol_table', 'post', FUSION_SELF.$aidlink."&amp;section=pm_messages");

            while($epc_data = dbarray($message_data))  {

                $message_read = self::$locale['PMC_068'][$epc_data['message_read']];
                $message_folder = self::$locale['PMC_069'][$epc_data['message_folder']];
                $message = parseubb(parse_textarea($epc_data['message_message'], false, false));
                $tx = "<div class='text-left'>".$message."</div>";

                echo "<tr id='link-".$epc_data['message_id']."' data-id=".$epc_data['message_id']."'>
			        <td>".form_checkbox('message_id[]', '', '', [
			            'value'    => $epc_data['message_id'],
			            'class'    => 'm-0',
			            'input_id' => 'link-id-'.$epc_data['message_id']
			        ])."</td>
                    <td>[ <a href='".BASEDIR."profile.php?lookup=".$epc_data['id_from']."' target='_new'>".$epc_data['from_name']."</a> ]</td>
                    <td>[ <a href='".BASEDIR."profile.php?lookup=".$epc_data['id_to']."' target='_new'>".$epc_data['to_name']."</a> ]</td>
                    <td>".$epc_data['message_subject']."</td>
                    <td>".fusion_parse_user('@'.$epc_data['from_name'],$tx)."</td>
                    <td>".date(self::$locale['PMC_datepicker'], $epc_data['message_datestamp'])."</td>
                    <td>".$message_read."</td>
                    <td>".$message_folder."</td>
                    <td><a class='btn btn-default' href='".FUSION_SELF.$aidlink."&amp;section=pm_messages&amp;delete_message_id=".$epc_data['message_id']."' onclick=\"return confirm('".self::$locale['PMC_104']."')\"><i class='fa fa-times white'></i> ".self::$locale['delete']."</a></td>
                </tr>";

                add_to_jquery('$("#link-id-'.$epc_data['message_id'].'").click(function() {
                    if ($(this).prop("checked")) {
                        $("#link-'.$epc_data['message_id'].'").addClass("active");
                    } else {
                        $("#link-'.$epc_data['message_id'].'").removeClass("active");
                    }
	            });');
            }
            echo "</tbody></table>";

            echo form_checkbox('check_all', self::$locale['PMC_070'], '', [
                'reverse_label' => TRUE,
                'class'         => 'pull-left'
            ]);
            echo form_button('delete_all', self::$locale['delete'], self::$locale['delete'], [
                'class' => 'btn-danger m-l-10',
                'icon'  => 'fa fa-fw fa-trash-o'
            ]);

            echo closeform();

            add_to_jquery("
                $('#check_all').bind('click', function() {
                    if ($(this).is(':checked')) {
                        $('input[name^=message_id]:checkbox').prop('checked', true);
                        $('#links-table tbody tr').addClass('active');
                    } else {
                        $('input[name^=message_id]:checkbox').prop('checked', false);
                        $('#links-table tbody tr').removeClass('active');
                    }
                });
	        ");
            echo "</div>";

            if ($rows > $pm_per_page) {
                echo "<div class='clearfix'>";
                    echo "<div class='pull-right'>".makepagenav($rowstart, $pm_per_page, $rows, 3, FUSION_SELF.$aidlink."&amp;section=pm_messages&amp;")."</div>";
                echo "</div>\n";
            }
            closeside();
        } else {

            echo "<div class='text-center well'>".self::$locale['PMC_105']."</div>";
        }
    }

    private function pm_count() {

        $rowstart = (isset($_GET['rowstart']) AND isnum($_GET['rowstart'])) ? $_GET['rowstart'] : 0;
        $pm_per_page = fusion_get_settings('comments_per_page');

        $result = dbquery("SELECT m.*, u.user_lastvisit, u.user_email, u.user_id, u.user_name, COUNT(message_to) AS message_count
            FROM ".DB_USERS." u
            LEFT JOIN ".DB_MESSAGES." m ON u.user_id=m.message_to
            GROUP BY user_id
        ");

        $rows = dbrows($result);

        echo "<div class='well'>".self::$locale['PMC_080']."</div>";

        if ($rows > $pm_per_page) {
            echo makepagenav($rowstart, $pm_per_page, $rows, 3, FUSION_SELF.$aidlink."&amp;section=pm_count&amp;");
        }

        if ($rows) {
            $result = dbquery("SELECT m.*, u.user_lastvisit, u.user_email, u.user_id, u.user_name, COUNT(message_to) AS message_to
                FROM ".DB_USERS." u
                LEFT JOIN ".DB_MESSAGES." m ON user_id=message_to
                GROUP BY user_id
                ORDER BY message_to DESC
                LIMIT ".$rowstart.",20"
            );

            echo "<div class='table-responsive'>";
            echo "<table class='table table-hover'>
                <thead>
                    <tr>
                        <th>".self::$locale['PMC_081']."</th>
                        <th>".self::$locale['PMC_082']."</th>
                        <th>".self::$locale['PMC_083']."</th>
                        <th>".self::$locale['PMC_084']."</th>
                    </tr>
                </thead>
                <tbody>";
            $db_to = 0; $db_from = 0;
            while($epc_data = dbarray($result)) {
            	$db = dbcount("(message_id)", DB_MESSAGES, "message_from=".$epc_data['user_id']);
            	$db_to = $db_to + $epc_data['message_to'];
            	$db_from = $db_from + $db;

            	echo "<tr>
            	    <td>".fusion_parse_user('@'.$epc_data['user_name'])."</td>
            	    <td><a href='mailto:".$epc_data['user_email']."'>".$epc_data['user_email']."</a></td>
            	    <td>".showdate("longdate", $epc_data['user_lastvisit'])."</td>
            	    <td>".$epc_data['message_to']."/".$db."</td>
                </tr>";
            }

            echo"</tbody></table>";
            echo "</div>";
            echo "<div class='text-center'>".sprintf(self::$locale['PMC_085'], $db_to, $db_from)."</div>\n";

            if ($rows > $pm_per_page) {
                echo makepagenav($rowstart, $pm_per_page, $rows, 3, FUSION_SELF.$aidlink."&amp;section=pm_count&amp;");
            }

        } else {
            echo "<div class='text-center'>".self::$locale['PMC_105']."</div>\n";
        }
    }
}