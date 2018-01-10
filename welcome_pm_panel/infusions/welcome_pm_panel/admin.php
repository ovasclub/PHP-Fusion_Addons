<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin.php
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
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";

pageAccess("WPM");

$locale = fusion_get_locale("", WPM_LOCALE);

$welcpmsettings = dbarray(dbquery("SELECT * FROM ".DB_WELCOME_PM.(multilang_table("WPM") ? " WHERE wp_language='".LANGUAGE."'" : '')." "));

if (isset($_POST['savesettings'])) {
    $wp_messages = "";
    if ($_POST['wp_messages']) {
        $wp_messages = fusion_get_settings("allow_php_exe") ? htmlspecialchars($_POST['wp_messages']) : $_POST['wp_messages'];
    }

    $wp_sb_messages = "";
    if ($_POST['wp_sb_messages']) {
        $wp_sb_messages = fusion_get_settings("allow_php_exe") ? htmlspecialchars($_POST['wp_sb_messages']) : $_POST['wp_sb_messages'];
    }

    $welcpmsettings = [
        'wp_id'           => form_sanitizer($_POST['wp_id'], '', 'wp_id'),
        'wp_active'       => isset($_POST['wp_active']) ? $_POST['wp_active'] : 0,
        'wp_subject'      => form_sanitizer($_POST['wp_subject'], '', 'wp_subject'),
        'wp_messages'     => form_sanitizer($wp_messages, '', 'wp_messages')
    ];
    if (isset($_POST['wp_sbox'])) {
        $welcpmsettings += [
            'wp_sbox'        => isset($_POST['wp_sbox']) ? $_POST['wp_sbox'] : 0,
            'wp_sb_messages' => isset($_POST['wp_sb_messages']) ? form_sanitizer($_POST['wp_sb_messages'], '', 'wp_sb_messages') : $welcpmsetting['wp_sb_messages']
        ];

    }
    dbquery_insert(DB_WELCOME_PM, $welcpmsettings, 'update');
    addNotice("success", $locale['WPM_008']);
    redirect(FUSION_REQUEST);
}

if (!fusion_get_settings("tinymce_enabled")) {
    $textareaSettings = [
        'required'    => TRUE,
        'preview'     => TRUE,
        'type'        => 'bbcode',
        'autosize'    => TRUE,
        'placeholder' => 'placeholder',
        'error_text'  => 'error text',
        'form_name'   => 'settingsform',
        'width'       => '50%',
        'wordcount'   => TRUE
    ];
} else {
    $textareaSettings = ['required' => TRUE, 'type' => 'tinymce', 'tinymce' => 'advanced', 'error_text' => 'error text'];
}

opentable($locale['WPM_009']);
	echo"<div class='well m-t-10'>".$locale['WPM_001']."</div>\n";
    echo openform('settingsform', 'post', FUSION_REQUEST, ['class' => 'spacer-sm']);
    echo form_hidden('wp_id', '', $welcpmsettings['wp_id']);
    echo form_select('wp_active', $locale['WPM_010'], $welcpmsettings['wp_active'], ['inline' => TRUE, 'options' => $locale['WPM_A01']]);
    echo form_text('wp_subject', $locale['WPM_011'], $welcpmsettings['wp_subject'], ['inline' => TRUE, 'inner_width' => '250px', 'width' => '150px']);
    echo form_textarea('wp_messages', $locale['WPM_012'], $welcpmsettings['wp_messages'], $textareaSettings);

    echo form_select('wp_sbox', $locale['WPM_013'], $welcpmsettings['wp_sbox'], ['inline' => TRUE, 'options' => $locale['WPM_A01']]);
    echo "<div id='extDiv' ".($welcpmsettings['wp_sbox'] !== 1 ? "style='display:none;'" : '').">\n";
    echo "<div class='hidden-xs'>\n";
    echo form_textarea('wp_sb_messages', $locale['WPM_012'], $welcpmsettings['wp_sb_messages'], $textareaSettings);
    echo "</div>\n</div>\n";
    echo form_button('savesettings', $locale['save'], $locale['save'], ['class' => 'btn-success']);
    echo closeform();

add_to_jquery("
val = $('#wp_sbox').select2().val();
if (val == '1') {
    $('#extDiv').slideDown('slow');
} else {
    $('#extDiv').slideUp('slow');
}
$('#wp_sbox').bind('change', function() {
    var val = $(this).select2().val();
    if (val == '1') {
        $('#extDiv').slideDown('slow');
    } else {
        $('#extDiv').slideUp('slow');
    }
});
");
require_once THEMES."templates/footer.php";
