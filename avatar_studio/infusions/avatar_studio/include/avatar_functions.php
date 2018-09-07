<?php
define('AVATAR_FOLDER', IMAGES.'avatars/avatar_studio/');

function loadAvaCats() {

    $avaCats = makefilelist(AVATAR_FOLDER, '.|..|.htaccess|index.php|._DS_Store|.tmp', TRUE, 'folders');
    return (isset($avaCats)) ? $avaCats : false;
}

function loadAvaImgs($dir) {

    $avatar_images = makefilelist(AVATAR_FOLDER.$dir, '.|..|.htaccess|index.php|._DS_Store|.tmp', TRUE, 'files', '.png,.gif,.jpg,.jpeg');
    return (isset($avatar_images)) ? $avatar_images : false;
}

function saveAvatar() {

    $locale = fusion_get_locale('', ASTUDIO_LOCALE);
    $userdata = fusion_get_userdata();
    $output = null;
    // check that an avatar is selected
    if(isset($_POST['avatar_save']) && !empty($_POST['avatar_select'])) {

        // get the avatar category
        $avatar_cat = form_sanitizer($_POST['avatar_cat'], '', 'avatar_cat');
        // get the selected avatar
        $avatar_image = form_sanitizer($_POST['avatar_select'], '', 'avatar_select');
        // build the path to the avatar
        $avatar_path = AVATAR_FOLDER.$avatar_cat.'/'.$avatar_image;
        // check that the chosen avatar exists
        if(file_exists($avatar_path) && \defender::safe()) {
            $avatar = IMAGES."avatars/".$userdata['user_avatar'];
            if (file_exists($avatar) && $userdata['user_avatar']) {
                @unlink($avatar);
            }

            $ext = strrchr($avatar_path, '.');

            if(@copy($avatar_path, IMAGES.'avatars/avatar['.$userdata['user_id'].']'.$ext)){

                $bind = [
                    ':avatar' => "avatar[".$userdata['user_id']."]".$ext."",
                    ':userId' => $userdata['user_id']
                ];

                dbquery("UPDATE ".DB_USERS." SET user_avatar=:avatar WHERE user_id=:userId LIMIT 1", $bind);

                $output .= "<div class='well text-center m-b-20'>";
                $output .= $locale['ast_004'].'<br /><br />';
                $output .= "<a href='".BASEDIR."edit_profile.php'><button type='button' class='btn btn-link'>".$locale['ast_005']."</button></a><br /><br />";
                $output .= "<span style='color:#990000'><big>".sprintf($locale['ast_011'], $userdata['user_name'])."</big></span>
                        <br /><br /><img src='".INFUSIONS."avatar_studio/img/loading.gif' alt='' /><br /><br />";
                $output .= '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
                    <meta http-equiv="Pragma" content="no-cache" />
                    <meta http-equiv="Expires" content="0" />';
                $output .= "<script type=\"text/javascript\"><!--
                        setTimeout('Redirect()',5000);
                        function Redirect()
                        {
                        window.location.href = '".BASEDIR."profile.php?lookup=".$userdata['user_id']."';
                          }
                        // --></script>";
                $output .= "</div>\n";
            } else {

                $output .= "<div class='bg-danger text-center m-b-20'>".$locale['ast_015']."</div>\n";
            }

        } else {
            $output .= "<div class='bg-danger text-center m-b-20'>".$locale['ast_013']."</div>\n";
        }
    } else {
        $output .= "<div class='bg-danger text-center m-b-20'>".$locale['ast_012']."</div>\n";

    }
    return $output;
}
