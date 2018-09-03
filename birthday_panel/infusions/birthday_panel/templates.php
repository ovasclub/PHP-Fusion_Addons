<?php
if (!function_exists("Displayform")) {
    function Displayform($info) {
        $locale = fusion_get_locale();
        $html = \PHPFusion\Template::getInstance('birthdayform');
        $html->set_template(__DIR__.'/templates/birthday.html');
        $html->set_locale(fusion_get_locale());
        $html->set_tag('openside', fusion_get_function('openside', $info['openside']));
        $html->set_tag('closeside', fusion_get_function('closeside'));

        add_to_head("<script>$(function () { $('[data-toggle=\"tooltip\"]').tooltip() })</script>");

        if (!empty($info['searchlink'])) {
            $html->set_block('search', ['searchlink' => $info['searchlink']]);
        }

        $html->set_tag('daymsg', $info['daymsg']);

        if (!empty($info['nameday'])) {
            $dtxt = "";
            $ttxt = "";
            foreach ($info['nameday'] as $day_id => $day_data) {                $i = 1;
                foreach ($day_data as $id => $data) {                    $day_id == "day" ? $dtxt .= $data : $ttxt .= $data;
                    if ($i != count($day_data)) {                        $day_id == "day" ? $dtxt .= ", " : $ttxt .= ", ";
                    }
                    $i++;
                }
			}
            !empty($dtxt) ? $html->set_block('namedayd', ['dnameday' => $dtxt]) : '';
            !empty($ttxt) ? $html->set_block('namedayt', ['tnameday' => $ttxt]) : '';
        }

        if (!empty($info['birthdate'])) {
            $bdtxt = "";
            $bttxt = "";
            foreach ($info['birthdate'] as $birthdate_id => $birthdate_data) {
                $i = 1;
                foreach ($birthdate_data as $bid => $data) {                    $birthdate_id == "day" ? $bdtxt .= profile_link($data['user_id'], $data['user_name'], $data['user_status']).(!empty($data['bday']) ? " - ".$data['bday'].$locale['BDAY_020'] : '') :
                              $bttxt .= profile_link($data['user_id'], $data['user_name'], $data['user_status']).(!empty($data['bday']) ? " - ".$data['bday'].$locale['BDAY_020'] : '');
                    if ($i != count($birthdate_data)) {
                        $birthdate_id == "day" ? $bdtxt .= ", " : $bttxt .= ", ";
                    }
                    $i++;
                }
			}
            !empty($bdtxt) ? $html->set_block('birthdated', ['dbirthdate' => $bdtxt]) : '';
            !empty($bttxt) ? $html->set_block('birthdatet', ['tbirthdate' => $bttxt]) : '';
        }

        if (!empty($info['usernameday'])) {
            $udtxt = "";
            $uttxt = "";
            foreach ($info['usernameday'] as $usernameday_id => $usernameday_data) {
                $i = 1;
                foreach ($usernameday_data as $id => $data) {                    $usernameday_id == "day" ? $udtxt .= profile_link($data['user_id'], $data['user_name'], $data['user_status']) :
                              $uttxt .= profile_link($data['user_id'], $data['user_name'], $data['user_status']);
                    if ($i != count($usernameday_data)) {
                        $usernameday_id == "day" ? $udtxt .= ", " : $uttxt .= ", ";
                    }
                    $i++;
                }
			}
            !empty($udtxt) ? $html->set_block('usernamedayd', ['dusernameday' => $udtxt]) : '';
            !empty($uttxt) ? $html->set_block('usernamedayt', ['tusernameday' => $uttxt]) : '';
        }

        if (!empty($info['zodiak'])) {            $html->set_block('zodiak', [
                'images' => BDAY_PATH."images/".$info['zodiak']['images'],
                'title'  => $info['zodiak']['title']
            ]);
        }

        $html->set_tag('leapyear', $info['leapyear']);

        echo $html->get_output();
    }
}

if (!function_exists("Displaysearch")) {
    function Displaysearch($info) {
        $locale = fusion_get_locale();
        $html = \PHPFusion\Template::getInstance('searchform');
        $html->set_template(__DIR__.'/templates/search.html');
        $html->set_locale(fusion_get_locale());
        $html->set_tag('opentable', fusion_get_function('opentable', $info['opentable']));
        $html->set_tag('closetable', fusion_get_function('closetable'));

        if (!empty($info['searchform'])) {
            $html->set_block('searchform', [
                'sname'  => $info['searchform']['sname'],
            ]);
        }

        if (!empty($info['namedat'])) {
            !empty($info['namedat']['ncount']) ? $html->set_block('name', ['message' => sprintf($locale['BDAY_042'], ucfirst($info['namedat']['name']), $info['namedat']['ncount'])]) :
            $html->set_block('name', ['message' => sprintf($locale['BDAY_043'], ucfirst($info['namedat']['name']))]);

            if (!empty($info['namedat']['ittem'])) {            	foreach ($info['namedat']['ittem'] as $key => $data) {            		$html->set_block('namedat', [
            		    'snameday'  => $data
            		]);
            	}
            }
        }

        $opform = (!empty($info['searchform']['openform']) ? $info['searchform']['openform'] : '');
        $closform = (!empty($info['searchform']['closeform']) ? $info['searchform']['closeform'] : '');
        echo $opform;
        echo $html->get_output();
        echo $closform;
    }
}

