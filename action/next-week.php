<?php
    include_once '../logic/savegame.php';
    $save = new Savegame;

    // move inputs to variables
    $workers = $_POST['workers'];
    $defenders = $_POST['defenders'];
    $scholars = $_POST['scholars'];

    // ensure validity
    if (!is_numeric($workers) || !is_numeric($defenders) || !is_numeric($scholars)) {
        $error = "Invalid input.";
        include '../view/main.php';
        exit;
    }

    // update save state
    $save->Set('workers', $workers);
    $save->Set('defenders', $defenders);
    $save->Set('scholars', $scholars);

    if ($workers + $defenders + $scholars != 100) {
        $error = "The population distribution does not add up to 100%.";
        include '../view/main.php';
        exit;
    }

    // get the tasks for the week
    $build_farms = isset($_POST['farms']);
    $build_houses = isset($_POST['houses']);
    $improve_barracks = isset($_POST['barracks']);
    $improve_walls = isset($_POST['walls']);

    // a php bool is really just either 0 or 1, so this works.
    $task_count = $build_farms + $build_houses + $improve_barracks + $improve_walls;

    if ($task_count > 0) {
        $workforce = $workers / $task_count;
    }
    else {
        $error = "You must assign your workers at least one task.";
        include '../view/main.php';
        exit;
    }

    // weekly events
    $week = $save->Get('week');

    $refugee_arrivals = $week * 2 - rand(0, $week);

    if ($week > 1) {
        $new_demons = rand(0, $week) * rand(0, 100) * 0.1;
        // no luck after week 10
        if ($week >= 10 && $new_demons == 0) {
            $new_demons = rand(2, 10);
        }
    }
    else   
        $new_demons = 0;

    include '../view/main.php';
?>