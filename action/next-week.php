<?php
    include_once '../logic/savegame.php';
    $save = new Savegame;

    $workers = $save->Get('workers');
    $defenders = $save->Get('defenders');
    $scholars = $save->Get('scholars');
    $progress = $save->Get('progress');
    $population = $save->Get('total_pop');
    $week = $save->Get('week');
    $demons = $save->Get('demons');
    $aggresivity = $save->Get('aggresivity');
    $houses = $save->Get('houses');
    $farms = $save->Get('farms');
    $barracks = $save->Get('barracks');
    $walls = $save->Get('walls');
    $attacks = $save->Get('attacks');
    $killed = $save->Get('killed');
    $total_deaths = $save->Get('deaths');
    $property_loss = $save->Get('property_loss');

    $report = array();

    // get the tasks for the week
    $build_farms = isset($_POST['farms']);
    $build_houses = isset($_POST['houses']);
    $improve_barracks = isset($_POST['barracks']);
    $improve_walls = isset($_POST['walls']);

    // a php bool is really just either 0 or 1, so this works.
    $task_count = $build_farms + $build_houses + $improve_barracks + $improve_walls;

    if ($task_count > 0) {
        if ($task_count == 1) {
            $workforce = round($workers * 0.01 * $population * 0.85); // 85% of workforce if only one task. too many cooks....
        }
        else {
            $workforce = round($workers * 0.01 * $population / $task_count); // if more than one task, split workforce accordingly.
        }
    }
    else {
        $error = "You must assign your workers at least one task.";
        include '../view/main.php';
        exit;
    }

    $attacks++;

    $week_modifier = $week;
    if ($week_modifier > 22) $week_modifier = 22;

    // weekly events
    $refugee_arrivals = round($week_modifier * 2 - rand(0, $week_modifier));

    if ($week > 1) {
        $new_demons = rand(0, $week_modifier) * rand(0, 80) * 0.1;
        // no luck after week 10
        if ($week >= 10 && $new_demons == 0) {
            $new_demons = rand(2, 20);
        }
        if ($progress < 60) {
            if ($new_demons > 25) $new_demons *= 0.75;
            if ($new_demons > 50) $new_demons *= 0.5;
        }
        else if ($progress < 90) {
            if ($new_demons > 25) $new_demons *= 0.9;
            if ($new_demons > 50) $new_demons *= 0.75;
        }
        $new_demons = round($new_demons);
    }
    else   
        $new_demons = 0;

    
    $demons += $new_demons;

    if ($new_demons == 0) {
        $report[] = "Scouts report <span style='color: green'>no</span> new demons nearby.";
    }
    else {
        $report[] = "Scouts report <span style='color: red'>{$new_demons}</span> more demons nearby.";
    }

    if ($week > 2) {
        $add_aggro = round($new_demons / 2);
        if ($add_aggro < 5) {
            $add_aggro += round(2, 7);
        }
        $aggresivity += $add_aggro;
    }

    $demons_will_attack = false;

    if ($aggresivity > 50) {
        $attack_roll = rand(0, 1);

        if ($attack_roll == 1 || $aggresivity > 100) {
            $demons_will_attack = true;
        }
    }
    
    else 

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
            $report[] = "1 new house was built.";
        else
            $report[] = "{$new_houses} houses were built.";
    }
    
    // farm building
    if ($build_farms) {

        $new_farms = round($workforce / 20);
        if ($new_farms < 1) {
            $new_farms = rand(0, 1);
        }

        $farms += $new_farms;

        if ($new_farms == 0)
            $report[] = "No farms were built.";
        else if ($new_farms == 1)
            $report[] = "1 new farm was built.";
        else
            $report[] = "{$new_farms} farms were built.";
    }

    // barrack improvement
    if ($improve_barracks) {
        // workforce % is irrelevant.
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
    if ($improve_walls) {
        $walls += round($workforce * 0.07);
        if ($walls > 100) $walls = 100;
        $report[] = "Walls were repaired to {$walls}% condition.";
    }

    // scholar research
    $scholar_count = (($scholars * 0.01) * $population);

    if ($scholars > 50) { // too many cooks....
        $scholar_count = $scholar_count * 0.9;
    }

    $new_progress = $scholar_count * 0.025;
    if ($new_progress > 1.75) {
        $new_progress = 1.75 + (($new_progress - 1.75) * 0.5);
    }
    $progress += $new_progress;
    $report[] = "Scholars have made ".number_format($new_progress, 2)."% progress.";

    if ($progress >= 100) {
        include "../view/victory.php";
        exit;
    }

    $max_pop = $houses * 10;

    $population += $refugee_arrivals;

    if ($population > $max_pop) {
        $rejected_refugees = $population - $max_pop;
        $population = $max_pop;

        $report[] = "<span style='color: var(--primary)'>{$refugee_arrivals}</span> refugees from neighboring fortresses arrived.<br />{$rejected_refugees} were turned away due to lack of housing.";
    }
    else {
        if ($refugee_arrivals == 0) $report = "We received no refugees this week.";
        $report[] = "We received <span style='color: var(--primary)'>{$refugee_arrivals}</span> refugees from neighboring fortresses.";
    }

    // if not enough farms, famine.
    if ($farms * 20 < $population) {
        $starved = $population - ($farms * 20);
        $starved = rand(1, $starved);
        $report[] = "<span style='color: red;'>Famine!</span> Not enough farms. {$starved} have died of hunger.";
        $population -= $starved;
    }

    if ($demons_will_attack) {
        $report[] = "-----------------------";
        $report[] = "<h1 style='color:red; font-size: 1.75rem; margin-bottom: .5rem;'><em>DEMONS HAVE ATTACKED</em></h1>";

        $attack_force = rand(round($demons / 3), round($demons * 0.95));

        $demons -= $attack_force;
        $killed += $attack_force;

        $report[] = "{$attack_force} demons attacked the fortress.";

        if ($walls >= 50) {
            $report[] = "The walls sustained heavy damage, but helped hold back the attack.";
            $walls -= rand(20, round($walls / 2));
            $attack_force = round($attack_force * 0.75);
        }
        else if ($walls >= 20) {
            $report[] = "The walls sustained damage, but helped hold back the attack some.";
            $walls -= rand(10, round($walls / 2));
            $attack_force = round($attack_force * 0.88);
        }
        else {
            $report[] = "The walls had no chance helping hold back the attack.";
            $walls = 0;
        }
        if ($walls < 0) $walls = 0;


        $defender_count = (($defenders * 0.01) * $population);
        $defense_force = round($defender_count * ($barracks * 0.05));

        if ($barracks > 1) {
            $barracks--;
            if ($barracks > 1 && $attack_force > 100) $barracks--;
            $report[] = "The barracks sustained damage.";
        }

        $effective_attack_force = round($attack_force - ($defense_force * 0.9));

        $property_destruction_force = $effective_attack_force;
        $houses_gone = 0;
        $farms_gone = 0;
        while ($property_destruction_force > 15) {
            if (rand(0, 1)) {
                $houses_gone++;
            }
            else {
                $farms_gone++;
            }
            $property_loss++;
            $property_destruction_force -= 15;
        }

        if ($houses_gone > 0) $report[] = "{$houses_gone} houses were destroyed.";
        if ($farms_gone > 0) $report[] = "{$farms_gone} farms were destroyed.";

        $deaths = round(($population * 0.01) * ($effective_attack_force * (0.02 * $week_modifier)));

        // no point continuing to play with less than 20 population, so just kill them all.
        if ($population - $deaths < 20) {
            $killed -= $attack_force - 2;
            $save->Set('attacks', $attacks);
            $save->Set('killed', $killed);
            $save->Set('deaths', $total_deaths);
            $save->Set('property_loss', $property_loss);
            include "../view/game-over.php";
            exit;
        }

        if ($deaths > 0) {
            $population -= $deaths;

            $report[] = "{$deaths} people were killed.";

            $aggresivity -= $deaths;
            if ($aggresivity < 0) $aggresivity = 0;
            $total_deaths += $deaths;
        }
        else {
            $report[] = "No one was killed.";
        }

        if ($aggresivity > 40) {
            $aggresivity = round($aggresivity / 2);
        }

        $report[] = "-----------------------";
        $report[] = "All attacking demons were killed.";
    }

    $attacks++;

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
    $save->Set('attacks', $attacks);
    $save->Set('killed', $killed);
    $save->Set('deaths', $total_deaths);
    $save->Set('property_loss', $property_loss);

    $save->SetWeekReport($report);

        $debug = "Demon aggresivity: {$aggresivity} <br />";
        $debug .= "Max population: {$max_pop} <br />";
        $debug .= "Workforce per task: {$workforce} <br />";
        if (isset($attack_force)) {
            $debug .= "Attack force: {$attack_force} <br />";
        }
        if (isset($defense_force)) {
            $debug .= "Defense force: {$defense_force} <br />";
        }
    
    include '../view/main.php';
?>