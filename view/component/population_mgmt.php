<?php
    define("MAX_WORKERS", 70);
    define("MAX_DEFENDERS", 70);
    define("MAX_SCHOLARS", 70);
    define("MIN_WORKERS", 15);
    define("MIN_DEFENDERS", 15);
    define("MIN_SCHOLARS", 15);

    if (!isset($save)) {
        include_once '../../logic/savegame.php';
        $save = new Savegame;
    }

    $workers = $save->Get('workers');
    $defenders = $save->Get('defenders');
    $scholars = $save->Get('scholars');

    if (isset($_GET['do']) && isset($_GET['item'])) {
        if ($_GET['do'] == "add") {
            switch($_GET['item']) {
                case 'workers':
                    if ($workers < MAX_WORKERS) $workers += 5;
                    break;
                case 'scholars':
                    if ($scholars < MAX_SCHOLARS) $scholars += 5;
                    break;
                case 'defenders':
                    if ($defenders < MAX_DEFENDERS) $defenders += 5;
                    break;
            }
        }
        else {
            switch($_GET['item']) {
                case 'workers':
                    if ($workers > MIN_WORKERS) $workers -= 5;
                    break;
                case 'scholars':
                    if ($scholars > MIN_SCHOLARS) $scholars -= 5;
                    break;
                case 'defenders':
                    if ($defenders > MIN_DEFENDERS) $defenders -= 5;
                    break;
            }
        }

        if ($workers + $scholars + $defenders < 100) {
            if ($_GET['item'] != "workers" && $workers < MAX_WORKERS) $workers += 5;
            else if ($_GET['item'] != "scholars" && $scholars < MAX_SCHOLARS) $scholars += 5;
            else if ($_GET['item'] != "defenders" && $defenders < MAX_DEFENDERS) $defenders += 5;
        }
        else if ($workers + $scholars + $defenders > 100) {
            if ($_GET['item'] != "workers" && $workers > MIN_WORKERS) $workers -= 5;
            else if ($_GET['item'] != "scholars" && $scholars > MIN_SCHOLARS) $scholars -= 5;
            else if ($_GET['item'] != "defenders" && $defenders > MIN_DEFENDERS) $defenders -= 5;
        }

        // oops.
        if ($workers + $scholars + $defenders > 100) {
            $workers = 50;
            $scholars = 30;
            $defenders = 20;
        }
    }

    $save->Set('workers', $workers);
    $save->Set('defenders', $defenders);
    $save->Set('scholars', $scholars);
    $save->UpdateSave();
?>

<ul>
    <li class="item">Workers: <?=$workers?>% -- <button class="popBtn" hx-post="view/component/population_mgmt.php?do=add&item=workers">+</button> <button class="popBtn" hx-post="view/component/population_mgmt.php?do=sub&item=workers">-</button></li>
    <li class="item">Defenders: <?=$defenders?>% -- <button class="popBtn" hx-post="view/component/population_mgmt.php?do=add&item=defenders">+</button> <button class="popBtn" hx-post="view/component/population_mgmt.php?do=sub&item=defenders">-</button></li>
    <li class="item">Scholars: <?=$scholars?>% -- <button class="popBtn" hx-post="view/component/population_mgmt.php?do=add&item=scholars">+</button> <button class="popBtn" hx-post="view/component/population_mgmt.php?do=sub&item=scholars">-</button></li>
</ul>