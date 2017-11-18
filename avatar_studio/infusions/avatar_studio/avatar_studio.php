<?php
/*---------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: avatar_studio.php
| Version: 3.00
| Author: Terry Broullette (Grimloch)
| Email: webmaster@whisperwillow.com
| Web: http://www.whisperwillow.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once dirname(__FILE__).'/../../maincore.php';
require_once THEMES."templates/header.php";

// Check if locale file is available matching the current site locale setting.
$locale = fusion_get_locale("", ASTUDIO_LOCALE);

include_once INFUSIONS."avatar_studio/include/avatar_functions.php";

opentable($locale['ast_001']);

if(isset($_POST['avatar_save'])) {    echo SaveAvatar();
} else {

$formSettings = [
    'enctype'    => true,
    'max_tokens' => 20,
];

$ava_cats = loadAvaCats();
    if(is_array($ava_cats)){

        echo "<div class='well text-center m-b-20'>".$locale['ast_002']."\n";
        echo openform('avatarform', 'post', FUSION_REQUEST, $formSettings);

        $_POST['avatar_cat'] = isset($_POST['avatar_cat']) ? $_POST['avatar_cat'] : $ava_cats[0];

        echo form_select('avatar_cat', $locale['ast_003'], $_POST['avatar_cat'], [
            'options' => $ava_cats,
            'keyflip' => true,
        ]);
        echo form_button('cancel', $locale['view'], $locale['view'], ['class' => 'btn-default btn-sm']);
        echo "</div>\n";
        // new table for manual avatar column change
        $avatar_cat = (isset($_POST['avatar_cat'])) ? $_POST['avatar_cat'] : $ava_cats[1];
        $avatar_images = loadAvaImgs($avatar_cat);
        if(is_array($avatar_images) && count($avatar_images) > 0) {
            echo "<div class='table-responsive'><table class='table table-hover table-bordered'>\n";

            $i = 1;
            // define the avatar colums to display
            $avatar_cols = 3;
            $avatar_rows = array_chunk($avatar_images, $avatar_cols);
            foreach($avatar_rows as $avatar_images) {

                echo "<tr>\n";
                $i = 0;

                foreach($avatar_images as $key => $avatar_name) {

                    $image = AVATAR_FOLDER.$avatar_cat.'/'.$avatar_name;

                    echo "<td class='text-center'>\n".
                    "<img src='$image' alt='$avatar_name' title='$avatar_name' /><br />\n".
                    form_checkbox('avatar_select', '', '', ['value' => $avatar_name, 'type'=>'radio']).
                    "</td>\n";
                    $i++;
                }
                if($i < $avatar_cols) {
                    echo "<td colspan=".($avatar_cols - $i)."></td>\n";
                }

                echo "</tr>\n";
            }
            echo "</table>\n</div>";
            // end new table
            echo "<div class='text-center'>"
            .(iMEMBER ?
            form_button('avatar_save', $locale['ast_006'], $locale['ast_006'], ['class' => 'btn-success btn-sm'])
            : "")
            ."</div>\n";
        } else {

            echo "<div class='bg-danger text-center'>".$locale['ast_009']."</div>\n";
        }
    } else {

        echo "<div class='bg-danger text-center'>".$locale['ast_010']."</div>\n";
    }
echo closeform();

}
closetable();
require_once THEMES."templates/footer.php";