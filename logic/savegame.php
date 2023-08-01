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

    function Get($field) {
        if (!array_key_exists($field, $this->savegame) || $this->savegame[$field] == NULL) {
            $this->savegame[$field] = $this->GetDefaults($field);
        }

        if (is_array($this->UpdateSave()))
            return htmlspecialchars($this->UpdateSave());
        
        return $this->savegame[$field];
    }

    function Set($field, $value) {
        $this->savegame[$field] = $value;

        $this->UpdateSave();
    }

    function UpdateSave() {
        setcookie('savegame', json_encode($this->savegame), time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    function GetDefaults($field) {
        // inputs
        $default['fortress'] = "default";
        $default['leader'] = "default";

        // resources
        $default['food'] = 25;

        // buildings
        $default['farms'] = 3;
        $default['level_barrack'] = 1;
        
        // population
        $default['total_pop'] = 100;
        $default['workers'] = 50;
        $default['defenders'] = 20;
        $default['scholars'] = 30;
        
        // state
        $default['progress'] = 0.0;
        $default['week'] = 1;
        $default['morale'] = 60;
        $default['demons'] = 0;

        return $default[$field];
    }
}

?>