<?php


if (!defined("ASTUDIO_LOCALE")) {
    if (file_exists(INFUSIONS."avatar_studio/locale/".LANGUAGE.".php")) {
        define("ASTUDIO_LOCALE", INFUSIONS."avatar_studio/locale/".LANGUAGE.".php");
    } else {
        define("ASTUDIO_LOCALE", INFUSIONS."avatar_studio/locale/Hungarian.php");
    }
}

