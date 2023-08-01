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

    $report = array();

    // get the tasks for the week
    $build_farms = isset($_POST['farms']);
    $build_houses = isset($_POST['houses']);
    $improve_barracks = isset($_POST['barracks']);
    $improve_walls = isset($_POST['walls']);

    // a php bool is really just either 0 or 1, so this works.
    $task_count = $build_farms + $build_houses + $improve_barracks + $improve_walls;

    $population = $save->Get('total_pop');

    if ($task_count > 0) {
        $workforce = (($workers * 0.01) * $population) / $task_count;
        $workforce = round($workforce);
    }
    else {
        $error = "You must assign your workers at least one task.";
        include '../view/main.php';
        exit;
    }

    // weekly events
    $week = $save->Get('week');

    $refugee_arrivals = $week * 2 - rand(0, $week);
    $refugee_arrivals = round($refugee_arrivals);

    $week_modifier = $week;
    if ($week_modifier > 40) $week_modifier = 40;

    if ($week > 1) {
        $new_demons = rand(0, $week_modifier) * rand(0, 100) * 0.1;
        // no luck after week 10
        if ($week >= 10 && $new_demons == 0) {
            $new_demons = rand(2, 10);
        }
        if ($new_demons > 20) $new_demons *= 0.9;
        if ($new_demons > 40) $new_demons *= 0.8;
        if ($new_demons > 60) $new_demons = 50 + rand(0, 25);
        $new_demons = round($new_demons);
    }
    else   
        $new_demons = 0;

    $demons = $save->Get('demons');
    $demons += $new_demons;

    if ($new_demons == 0) {
        $report[] = "Scouts report no new demons nearby.";
    }
    else {
        $report[] = "Scouts report {$new_demons} more demons nearby.";
    }

    $aggresivity = $save->Get('aggresivity');

    if ($week > 2)
        @$aggresivity += rand($week_modifier / 3, $week_modifier) * ($new_demons / 3);

    $aggresivity = round($aggresivity);

    $attack = rand(0, $aggresivity);

    $demons_will_attack = false;

    if ($attack > 70) {
        $demons_will_attack = true;
    }
    else if ($attack > 40) {
        $demons_will_attack = true;
    }

    $houses = $save->Get('houses');

    // house building
    if ($build_houses) {
        $new_houses = ($workforce / 20);
        if ($new_houses < 1) {
            $new_houses = rand(0, 1);
        }
        
        $new_houses = round($new_houses);
        $houses += $new_houses;

        if ($new_houses == 0)
            $report[] = "No houses were built.";
        else if ($new_houses == 1)
            $report[] = "A new house was built.";
        else
            $report[] = "{$new_houses} houses were built.";
    }

    $farms = $save->Get('farms');
    
    // farm building
    if ($build_farms) {

        $new_farms = ($workforce / 20);
        if ($new_farms < 1) {
            $new_farms = rand(0, 1);
        }

        $new_farms = round($new_farms);
        $farms += $new_farms;

        if ($new_farms == 0)
            $report[] = "No farms were built.";
        else if ($new_farms == 1)
            $report[] = "1 new farm was built.";
        else
            $report[] = "{$new_farms} farms were built.";
    }

    // barrack improvement
    $barracks = $save->Get('barracks');
    if ($improve_barracks) {
        // workforce % is irrelevant.
        $barracks = $save->Get('barracks');
        $barracks++;
        if ($barracks > 5) {
            $barracks = 5;
            $report[] = "Barracks received maintenance.";
        }
        else {
            $report[] = "Barracks improved, our defenders are now more powerful.";
        }
    }

    // wall improvements
    $walls = $save->Get('walls');
    if ($improve_walls) {
        $wall_improvement = $walls * ($workforce * 0.0023);
        $walls += round($wall_improvement);
        if ($walls > 100) $walls = 100;
        $report[] = "Walls were repaired to {$walls}% condition.";
    }

    // scholar research
    $scholar_count = $population * ($scholars * 0.003);
    $progress = $save->Get('progress');

    $new_progress = $scholar_count * 0.1;
    $progress += $new_progress;
    $report[] = "Scholars have made ".number_format($new_progress, 2)."% progress.";


    $max_pop = $houses * 10;

    $population += $refugee_arrivals;

    if ($population > $max_pop) {
        $rejected_refugees = $population - $max_pop;
        $population = $max_pop;

        $report[] = "{$refugee_arrivals} refugees from neighboring fortresses arrived. {$rejected_refugees} were turned away due to lack of housing.";
    }
    else {
        if ($refugee_arrivals == 0) $report = "We received no refugees this week.";
        $report[] = "We received {$refugee_arrivals} refugees from neighboring fortresses.";
    }

    if ($demons_will_attack) {
        $report[] = "-----------------------";
        $report[] = "<h1 style='color:red'><em>DEMONS HAVE ATTACKED</em></h1>";

        @$attack_force = rand($demons / 3, round($demons * 0.95));

        $demons -= $attack_force;

        $report[] = "{$attack_force} demons attacked the fortress.";

        if ($walls > 70) {
            $report[] = "The walls sustained heavy damage, but helped hold back the attack.";
            $walls -= rand (10, round($walls / 2));
            $attack_force = round( $attack_force * 0.7 );
        }
        else if ($walls > 25) {
            $report[] = "The walls sustained damage, but helped hold back the attack some.";
            $walls -= rand (7, round($walls / 3));
            $attack_force = round( $attack_force * 0.83 );
        }
        else {
            $report[] = "The walls had no chance helping hold back the attack.";
            $walls = 0;
        }

        if ($walls < 0) $walls = 0;

        /*
        TODO:
            - Damage to property
            - Damage to research
        */

        $deaths = round( ($population * 0.01) * ($attack_force * 0.015) );
        $population -= $deaths;

        $report[] = "{$deaths} people were killed.";

        if ($aggresivity > 100) {
            $aggresivity -= 100;
        }
        $aggresivity = round ($aggresivity / 2);


        if ($population < 20) {
            include "../view/game_over.php";
            exit;
        }

        $report[] = "-----------------------";
        $report[] = "All attacking demons were killed.";
    }

    // update all new values to the savegame.
    $save->Set('total_pop', $population);
    $save->Set('aggresivity', $aggresivity);
    $save->Set('demons', $demons);
    $save->Set('walls', $walls);
    $save->Set('barracks', $barracks);
    $save->Set('week', ++$week);
    $save->Set('farms', $farms);
    $save->Set('houses', $houses);
    $save->Set('progress', $progress);

    $save->SetWeekReport($report);

    $debug = "Demon aggresivity: {$aggresivity} <br />";
    $debug .= "Max population: {$max_pop} <br />";
    $debug .= "Attack roll: {$attack} <br />";
    $debug .= "Workforce per task: {$workforce} <br />";
    
    include '../view/main.php';
?>