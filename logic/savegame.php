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

    /**
     * Obtain wether a savegame exists or not.
     * @return bool
     */
    function SaveExists() {
        if ($this->savegame == NULL) {
            return false;
        }
        return true;
    }

    /**
     * Start a new game.
     * @param string $leader
     * @param string $fortress
     * @return void
     */
    function NewGame($leader, $fortress) {
        $this->savegame = array('leader' => $leader, 'fortress' => $fortress);

        $this->SetWeekReport([ "You are on your first week." ]);
        $this->UpdateSave();
    }

    /**
     * Obtain data for a provided field.
     * @param string $field
     * @return mixed
     */
    function Get($field) {
        if (!array_key_exists($field, $this->savegame)) {
            $this->savegame[$field] = $this->GetDefaults($field);
        }

        if (is_array($this->UpdateSave()))
            return htmlspecialchars($this->UpdateSave());
        
        return $this->savegame[$field];
    }

    /**
     * Set data for a provided field.
     * @param string $field
     * @param mixed $value
     * @return void
     */
    function Set($field, $value) {
        $this->savegame[$field] = $value;

        $this->UpdateSave();
    }

    function SetWeekReport($data) {
        $json_data = json_encode($data);
        setcookie('week_report', $json_data, time() + (10 * 365 * 24 * 60 * 60), "/");
        $_COOKIE['week_report'] = $json_data;
    }

    /**
     * Obtain the report for the past week.
     * @return string
     */
    function GetWeekReport() {
        if (isset($_COOKIE['week_report'])) {
            $data = json_decode($_COOKIE['week_report'], true);

            $report = "";

            $limit = 100;

            foreach($data as $line) {
                $report = $report . $line . "<br />";
                $limit--;
                if ($limit < 1) break;
            }

            return $report;
        }

        return "No report.";
    }

    /**
     * Commit new savegame to the client.
     * @return void
     */
    function UpdateSave() {
        $_COOKIE['savegame'] = json_encode($this->savegame);
        setcookie('savegame', json_encode($this->savegame), time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    /**
     * Obtain the default value for a field.
     * @param string $field
     * @return float|int|string
     */
    function GetDefaults($field) {
        // inputs
        $default['fortress'] = "default";
        $default['leader'] = "default";

        // resources
        $default['food'] = 25;

        // buildings
        $default['houses'] = 10;
        $default['farms'] = 5;
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
        $default['aggresivity'] = 0;

        return $default[$field];
    }
}

?>