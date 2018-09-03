<?php
namespace PHPFusion\Bday;

class bdaypanel extends BdayServer {
    private static $instance = NULL;
    public $settings = [];

    public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new static();
            self::$instance->settings = self::$instance->CurrentSetup();
        }
        return self::$instance;
    }

    public function display() {
        $month = explode("|", self::$locale['months']);
        $day = explode("|", self::$locale['weekdays']);

        $info = [
            'openside'    => "<i class='fa fa-gift fa-lg m-r-10'></i>".self::$locale['BDAY_010'],
            'searchlink'  => $this->settings['search'] ? BDAY_PATH.'search.php' : '',
            'daymsg'      => date("Y").". ".$month[date("n")]." ".date("j").", ".$day[date("w")],
            'nameday'     => self::Nameday($this->settings['nameday']), //0 = disabled, 1 = day nameday, 2 = day and tomorrow nameday
            'birthdate'   => iMEMBER ? self::Birthdate($this->settings['birthday'], $this->settings['age']) : '',
            'usernameday' => iMEMBER ? self::UserNameday($this->settings['usernameday']) : '',
            'zodiak'      => $this->settings['zodiak'] ? self::zodiak(date("m"), date("d")) : '',
            'leapyear'    => self::$locale['BDAY_021'][date("L")]
        ];

        Displayform($info);
    }
}