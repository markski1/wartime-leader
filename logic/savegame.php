<?php

class Savegame {
    private $savegame;

    function __construct() {
        if (!isset($_COOKIE['savegame'])) {
            $this->savegame = NULL;
        }
        else {
            $this->savegame = json_decode($_COOKIE['savegame'], true);
        }
    }

    function SaveExists() {
        if ($this->savegame == NULL) {
            return false;
        }
        return true;
    }

    function NewSave($leader, $fortress) {
        $this->savegame = array('leader' => $leader, 'fortress' => $fortress);

        $this->UpdateSave();
    }

    function Get($field_name) {
        if (!array_key_exists($field_name, $this->savegame)) {
            $this->savegame[$field_name] = $this->GetDefaults($field_name);
        }

        if (is_array($this->UpdateSave()))
            return htmlspecialchars($this->UpdateSave());
        
        return $this->savegame[$field_name];
    }

    function Set($field_name, $value) {
        $this->savegame[$field_name] = $value;

        $this->UpdateSave();
    }

    function UpdateSave() {
        setcookie('savegame', json_encode($this->savegame), time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    function GetDefaults($field_name) {
        // inputs
        $default['fortress'] = "default";
        $default['leader'] = "default";

        // resources
        $default['gold'] = 50;
        $default['food'] = 25;
        $default['farms'] = 3;
        
        // population
        $default['pop_idle'] = 10;
        $default['pop_defenders'] = 0;
        $default['pop_farmers'] = 0;

        // state
        $default['day'] = 1;
        $default['morale'] = 60;
        $default['enemy_aggro'] = 0;

        return $default[$field_name];
    }
}

?>