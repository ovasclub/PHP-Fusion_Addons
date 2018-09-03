<?php
require_once "../../maincore.php";
pageAccess("BDY");
require_once THEMES."templates/admin_header.php";
PHPFusion\Bday\Bdayadmin::getInstance()->displayadmin();

/*$locale = fusion_get_locale("", BDAY_LOCALE);

add_breadcrumb(array('link' => BDAY_PATH.'admin.php'.$aidlink, 'title' => self::$locale['BDAY_000']));
if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 1; }


opentable("<i class='fa fa-wrench fa-lg m-r-10'></i>".$locale['NEVA_051']);
$navigation = "<div class='row'>";
$navigation .= "<div class='col-xs-8 col-sm-4 text-center'><a href='".ADMIN."index.php".$aidlink."' class='".($_GET['page'] == 1 ? "btn btn-primary btn-lg active btn-block" : "btn btn-default btn-lg active btn-block")."'>".$locale['NEVA_052']."</a></div>";
$navigation .= "<div class='col-xs-8 col-sm-4 text-center'><a href='".FUSION_SELF.$aidlink."&amp;page=2' class='".($_GET['page'] == 2 ? "btn btn-primary btn-lg active btn-block" : "btn btn-default btn-lg active btn-block")."'>".$locale['NEVA_053']."</a></div>";
$navigation .= "<div class='col-xs-8 col-sm-4 text-center'><a href='".FUSION_SELF.$aidlink."&amp;page=3' class='".($_GET['page'] == 3 ? "btn btn-primary btn-lg active btn-block" : "btn btn-default btn-lg active btn-block")."'>".$locale['NEVA_054']."</a></div>";
$navigation .= "</div>";

openside($locale['NEVA_055']);
echo $navigation;
closeside();

if ($_GET['page'] == 2) {

add_breadcrumb(array('link' => INFUSIONS.'nevnap_szulinap_panel/admin.php'.$aidlink."&amp;page=2", 'title' => $locale['NEVA_053']));

if (isset($_POST['save']) and !empty($_POST['save'])) {

	$data = array(
		'id' => 1,
		'szulinap_time' => $nsz_setup['szulinap_time'],
		'nevnap_time' => $nsz_setup['nevnap_time'],
		'kuldo' => form_sanitizer($_POST['kuldo'], '', 'kuldo'),
		'kereso' => form_sanitizer($_POST['kereso'], 0, 'kereso'),
		'readaccessholnap' => form_sanitizer($_POST['holnap'], 0, 'holnap'),
		'readaccessma' => form_sanitizer($_POST['ma'], 0, 'ma'),
		'nevinfo' => form_sanitizer($_POST['nevinfo'], 0, 'nevinfo'),
		'photo' => form_sanitizer($_POST['nevkep'], '', 'nevkep'),
		'szulkep' => form_sanitizer($_POST['szulkep'], '', 'szulkep'),
		'zodiak' => form_sanitizer($_POST['zodiak'], 0, 'zodiak'),
		'zodiakimg' => stripinput($_POST['zodiakimg']),
		'napszak' => form_sanitizer($_POST['napszak'], 0, 'napszak'),
	);

	if ($defender::safe()) {
			dbquery_insert(DB_NAME_SETTINGS, $data, 'update');
			addNotice('success', $locale['NEVA_056']);
			redirect(FUSION_SELF.$aidlink);
	}
}
$result = dbquery("SELECT user_name, user_id FROM ".DB_USERS." where user_level <= -103");
$edit = "";
 while ($u = dbarray($result)) {

			$edit[] = $u['user_id'];
		}

opentable($locale['NEVA_053']);
$formaction = FUSION_SELF.$aidlink."&page=2";

echo openform('fej_form', 'post', $formaction, array('max_tokens' => 1));

echo form_select('kuldo', $locale['NEVA_057'], $nsz_setup['kuldo'], array(
	'keyflip' => TRUE,
	'options' => $edit,
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

echo form_select('kereso', $locale['NEVA_058'], $nsz_setup['kereso'], array(
	'options' => fusion_get_groups(),
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

echo form_select('holnap', $locale['NEVA_059'], $nsz_setup['readaccessholnap'], array(
	'options' => fusion_get_groups(),
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

echo form_select('ma', $locale['NEVA_060'], $nsz_setup['readaccessma'], array(
	'options' => fusion_get_groups(),
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

echo form_select('nevinfo', $locale['NEVA_061'], $nsz_setup['nevinfo'], array(
	'options' => fusion_get_groups(),
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

echo form_text('nevkep', $locale['NEVA_062'], $nsz_setup['photo'], array(
	'required' => TRUE,
	"inline" => TRUE,
	'max_length' => 100,
	'error_text' => $locale['NEVA_064']
));

echo form_text('szulkep', $locale['NEVA_063'], $nsz_setup['szulkep'], array(
	'required' => TRUE,
	"inline" => TRUE,
	'max_length' => 100,
	'error_text' => $locale['NEVA_064']
));

echo form_select('napszak', $locale['NEVA_066'], $nsz_setup['napszak'], array(
	'options' => array('1' => $locale['NEVA_067'][1], '0' => $locale['NEVA_067'][0]),
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

echo form_select('zodiak', $locale['NEVA_065'], $nsz_setup['zodiak'], array(
	'options' => array('1' => $locale['yes'], '0' => $locale['no']),
	'placeholder' => $locale['choose'],
	'width' => '100%',
	"inline" => TRUE,
));

function KOP_kep() {
	global $nsz_setup;
$tx = "";
for ($i=1; $i<5; $i++) {
$tx .= "<div class='col-sm-2 col-md-2 text-center'>\n";
$tx .= "<img height='70' alt='' title='' src='".NSZ_IMG."set".$i."_aquarius.".($i < 3 ? "png" : "jpg")."'>\n";
$tx .= "<p><label><input type='radio' name='zodiakimg' value='".$i."' ".($i == $nsz_setup['zodiakimg'] ? "checked='checked'" : "")." /></label></p>\n";
$tx .= "</div>\n";
}
return $tx;
}
echo "<div class='clearfix text-center row'>\n";
echo "<div class='pager'>\n";
echo "<div class='col-sm-2 col-md text-left'><b>".$locale['NEVA_068']."</b></div>\n";
echo KOP_kep();
echo "</div>\n";
echo "</div>\n";
echo form_button('save', $locale['NEVA_069'], $locale['NEVA_069'], array('inline' => TRUE, 'class' => 'btn-primary button',
																					'icon' => "entypo icomment"
																					));
echo  closeform();

closetable();
}

if ($_GET['page'] == 3) {
// Névnap infó beállítása
add_breadcrumb(array('link' => INFUSIONS.'nevnap_szulinap_panel/admin.php'.$aidlink."&amp;page=3", 'title' => $locale['NEVA_070']));

opentable($locale['NEVA_070']);

if (isset($_POST['valaszt'])) {

$ho = form_sanitizer($_POST['honap'], '', 'honap');
$nap = form_sanitizer($_POST['nap'], '', 'nap');
$hn = ($ho < 10 ? '0'.$ho : $ho)."-".($nap < 10 ? '0'.$nap : $nap);

$nap=dbarray(dbquery("SELECT * FROM ".DB_NAME_DAY." where day like '".$hn."'"));

$formaction = FUSION_SELF.$aidlink."&page=3";

echo "<div class='alert text-center alert-info'>".$nap['day']."</div>\n";

echo openform('nmod_form', 'post', $formaction, array('max_tokens' => 1));
echo form_hidden('id', '', $nap['id']);
echo form_text('nevnap', $locale['NEVA_071'], $nap['name'], array(
	'required' => TRUE,
	"inline" => TRUE,
	'max_length' => 100,
	'error_text' => $locale['NEVA_064']
));
$extendedSettings = array(
	"inline" => TRUE,
	"preview" => TRUE,
	"html" => TRUE,
	"autosize" => TRUE,
	"placeholder" => $locale['choose'],
	"form_name" => "nmod_form"
);

echo form_textarea('leiras', $nap['name'], $nap['jel'],$extendedSettings);

echo form_button('mentes', $locale['NEVA_072'], $locale['NEVA_072'], array('inline' => TRUE, 'class' => 'btn-primary button',
																					'icon' => "entypo icomment"
																					));
echo  closeform();

} elseif (isset($_POST['mentes'])) {
$id = form_sanitizer($_POST['id'], '', 'id');
$nevnap = form_sanitizer($_POST['nevnap'], '', 'nevnap');
$leiras = form_sanitizer($_POST['leiras'], '', 'leiras');

	if ($defender::safe()) {
$result = dbquery("UPDATE ".DB_NAME_DAY." SET name='$nevnap', jel='$leiras' WHERE id=".$id."");
			addNotice('success', $locale['NEVA_073']);
			redirect(FUSION_SELF.$aidlink);
	}


} else {

$formaction = FUSION_SELF.$aidlink."&page=3";
echo "<div class='row'>\n";

echo openform('nssze_form', 'post', $formaction, array('max_tokens' => 1));
echo "<div class='col-xs-6 col-sm-2'>".form_select('honap', $locale['NEVA_074'], '', array(
	'required' => TRUE,
	'options' => range(1, 12),
	'keyflip' => TRUE,
	'placeholder' => $locale['choose'],
	'width' => '100px',
	"inline" => TRUE,
));
echo "</div><div class='col-xs-6 col-sm-2'>\n";
echo form_select('nap', $locale['NEVA_075'], '', array(
	'required' => TRUE,
	'options' => range(1, 31),
	'keyflip' => TRUE,
	'placeholder' => $locale['choose'],
	'width' => '100px',
	"inline" => TRUE,
));
echo "</div></div>\n";
echo form_button('valaszt', $locale['NEVA_076'], $locale['NEVA_076'], array('inline' => TRUE, 'class' => 'btn-primary button',
																					'icon' => "entypo icomment"
																					));
echo  closeform();

}

}


closetable();
*/
require_once THEMES."templates/footer.php";
