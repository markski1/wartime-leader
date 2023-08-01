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

    function NewGame($leader, $fortress) {
        $this->savegame = array('leader' => $leader, 'fortress' => $fortress);

        $this->SetWeekReport("You are on your first week.");
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

    function SetWeekReport($data) {
        setcookie('week_report', $data, time() + (10 * 365 * 24 * 60 * 60), "/");
        $_COOKIE['week_report'] = $data;
    }

    function GetWeekReport() {
        if (isset($_COOKIE['week_report'])) {
            return $_COOKIE['week_report'];
        }

        return "No report.";
    }

    function UpdateSave() {
        $_COOKIE['savegame'] = json_encode($this->savegame);
        setcookie('savegame', json_encode($this->savegame), time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    function GetDefaults($field) {
        // inputs
        $default['fortress'] = "default";
        $default['leader'] = "default";

        // resources
        $default['food'] = 25;

        // buildings
        $default['houses'] = 20;
        $default['farms'] = 3;
        $default['barracks'] = 1;
        $default['walls'] = 50;
        
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