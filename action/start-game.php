<?php
    if (!isset($_POST['leader_name']) || !isset($_POST['fortress_name']) || strlen($_POST['leader_name']) < 1 || strlen($_POST['fortress_name']) < 1) {
        include('../view/new-game.php');
        echo '<p>You must complete both fields.</p>';
        return;
    }

    if (strlen($_POST['leader_name']) > 32 || strlen($_POST['fortress_name']) > 32) {
        include('../view/new-game.php');
        echo '<p>Neither field may be longer than 32 characters.</p>';
        return;
    }

    include_once "../logic/savegame.php";

    $save = new Savegame;

    $save->NewGame($_POST['leader_name'], $_POST['fortress_name']);

    include("../view/main.php");
?>