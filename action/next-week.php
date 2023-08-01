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

    include '../view/main.php';
?>