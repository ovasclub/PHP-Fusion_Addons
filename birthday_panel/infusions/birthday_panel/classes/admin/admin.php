<?php
namespace PHPFusion\Bday;
use \PHPFusion\BreadCrumbs;

class Bdayadmin extends BdayServer {
    private static $instance = NULL;
    private $allowed_pages = ["birthday", "settings"];
    public $settings = [];

    public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new static();
            self::$instance->settings = self::$instance->CurrentSetup();
        }
        return self::$instance;
    }

    public function displayadmin() {

        $_GET['section'] = isset($_GET['section']) && in_array($_GET['section'], $this->allowed_pages) ? $_GET['section'] : $this->allowed_pages[1];
        BreadCrumbs::getInstance()->addBreadCrumb(["link" => BDAY_PATH.'admin.php'.fusion_get_aidlink(), "title" => self::$locale['BDAY_050']]);
        add_to_title(self::$locale['global_200'].self::$locale['BDAY_050']);

        $tab['title'][] = 'nevnapok';
        $tab['id'][] = self::$locale['BDAY_051'];
        $tab['icon'][] = "fa fa-fw fa-file-text";
        $tab['title'][] = self::$locale['BDAY_052'];
        $tab['id'][] = "settings";
        $tab['icon'][] = "fa fa-fw fa-cogs";

        // Display Content
        opentable(self::$locale['BDAY_050']);

        echo opentab($tab, $_GET['section'], "birthday_admin", TRUE, "", "section", ['birthday', 'rowstart']);
        switch ($_GET['section']) {
            case "settings":
                self::daysettings();
                break;
            default:
                //ArticlesAdmin::getInstance()->displayArticlesAdmin();
        }
        echo closetab();
        closetable();
    }

    private function daysettings() {
        if (isset($_POST['savesettings'])) {
        	$savesettings = [
        	    'nsid'        => $this->settings['nsid'],
        	    'bfrom'       => form_sanitizer($_POST['bfrom'], '1', 'bfrom'),
        	    'search'      => form_sanitizer($_POST['search'], '0', 'search'),
        	    'zodiak'      => form_sanitizer($_POST['zodiak'], '0', 'zodiak'),
        	    'age'         => form_sanitizer($_POST['age'], '0', 'age'),
        	    'birthday'    => form_sanitizer($_POST['birthday'], '0', 'birthday'),
        	    'nameday'     => form_sanitizer($_POST['nameday'], '0', 'nameday'),
        	    'usernameday' => form_sanitizer($_POST['usernameday'], '0', 'usernameday'),
        	    'nevinfo'     => form_sanitizer($_POST['nevinfo'], '0', 'nevinfo'),
        	    'zodiakimg'   => form_sanitizer($_POST['zodiakimg'], '1', 'zodiakimg'),
        	    'nameimg'     => form_sanitizer($_POST['nameimg'], '', 'nameimg'),
        	    'birthimg'    => form_sanitizer($_POST['birthimg'], '', 'birthimg'),
        	];

            if (\defender::safe()) {
            	dbquery_insert(DB_BDAY_ST, $savesettings, 'update');
            	addNotice('success', self::$locale['BDAY_054']);
            	redirect(FUSION_REQUEST);
            }
        }

        $result = dbquery("SELECT user_name, user_id FROM ".DB_USERS." WHERE user_level <= -103");
        $ufrom = [];
        while ($u = dbarray($result)) {
        	$ufrom[$u['user_id']] = $u['user_name'];
        }

        echo openform('admin_form', 'post', FUSION_REQUEST, ['class' => 'spacer-sm']);
        $yesno = ['1' => self::$locale['yes'], '0' => self::$locale['no']];

        echo form_select('bfrom', self::$locale['BDAY_060'], $this->settings['bfrom'], [
            'inline'      => TRUE,
            'keyflip'     => TRUE,
            'options'     => $ufrom,
            'placeholder' => self::$locale['choose'],
            'width'       => '100%'
        ]);

        echo form_select('search', self::$locale['BDAY_061'], $this->settings['search'], [
            'inline'      => TRUE,
            'options'     => $yesno,
            'placeholder' => self::$locale['choose'],
            'width'       => '100%'
        ]);

        echo form_select('usernameday', self::$locale['BDAY_062'], $this->settings['usernameday'], [
            'inline'      => TRUE,
            'options'     => self::$locale['BDAY_055'],
            'placeholder' => self::$locale['choose'],
            'width'       => '100%'
        ]);

        echo form_select('birthday', self::$locale['BDAY_063'], $this->settings['birthday'], [
            'inline'      => TRUE,
            'options'     => self::$locale['BDAY_055'],
            'placeholder' => self::$locale['choose'],
            'width'       => '100%'
        ]);

        echo form_select('age', self::$locale['BDAY_064'], $this->settings['age'], [
            'inline'      => TRUE,
            'options'     => $yesno,
            'placeholder' => self::$locale['choose'],
            'width'       => '100%'
        ]);

        echo form_select('nameday', self::$locale['BDAY_065'], $this->settings['nameday'], [
            'inline' => TRUE,
            'options' => self::$locale['BDAY_055'],
            'placeholder' => self::$locale['choose'],
            'width' => '100%'
        ]);

        echo form_select('nevinfo', self::$locale['BDAY_066'], $this->settings['nevinfo'], [
            'inline' => TRUE,
            'options' => $yesno,
            'placeholder' => self::$locale['choose'],
            'width' => '100%'
        ]);

        echo form_text('nameimg', self::$locale['BDAY_067'], $this->settings['nameimg'], [
            'inline' => TRUE,
            'required' => TRUE,
            'max_length' => 100,
            'error_text' => self::$locale['choose']
        ]);

        echo form_text('birthimg', self::$locale['BDAY_068'], $this->settings['birthimg'], [
            'required' => TRUE,
            'inline' => TRUE,
            'max_length' => 100,
            'error_text' => self::$locale['choose']
        ]);

        echo form_select('zodiak', self::$locale['BDAY_069'], $this->settings['zodiak'], [
            'inline' => TRUE,
            'options' => $yesno,
            'placeholder' => self::$locale['choose'],
            'width' => '100%'
        ]);

        echo "<div class='row'>\n";
        echo "<div class='col-sm-2 col-md text-left'><b>".self::$locale['BDAY_070']."</b></div>\n";
        echo self::zodiakimg();
        echo "</div>\n";
        echo form_button('savesettings', self::$locale['save'], self::$locale['save'], ['class' => 'btn-primary']);
        echo closeform();

    }

    private function zodiakimg() {
    	$tx = "";
    	for ($i=1; $i<5; $i++) {
    		$tx .= "<div class='col-sm-2 col-md-2 text-center'>\n";
    		$tx .= "<img height='70' src='".BDAY_PATH."images/set".$i."_aquarius.".($i < 3 ? "png" : "jpg")."'><br />";
    		$tx .= form_checkbox('zodiakimg', '', $this->settings['zodiakimg'], ['reverse_label' => TRUE, 'inner_width' => '100%', 'value' => $i, 'input_id' => 'reset-id-'.$i, 'type'=> 'radio']);
    		$tx .= "</div>\n";
    	}
    	return $tx;
    }



}