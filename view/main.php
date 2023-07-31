<?php

    if (!isset($save)) {
        include_once '../logic/savegame.php';
        $save = new Savegame;
    }

    if (!$save->SaveExists()) {
        echo '<div>';
        echo '<p>No savegame detected.</p>';
        echo '<p><a href="#" hx-post="view/new-game.php" hx-target="#main">Start new game</a></p>';
        echo '<p><a href="#" hx-post="action/import-game.php" hx-confirm="not yet implemented" hx-target="#main">Import save file</a></p>';
        echo '</div>';
        return;
    }
?>

<main>
<section>
    <h3>Village of <?=$save->Get('village')?></h3>
    <p class="item">leader <?=$save->Get('leader')?></h3>
</section>
<section style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: center;">
    <div class="block">
        <h4>State</h4>
        <p class="item">Villagers: <?=$save->Get('villagers')?></p>
        <p class="item">Army: <?=$save->Get('army')?></p>
    </div>
    <div class="block">
        <h4>Resources</h4>
        <p class="item">Gold: <?=$save->Get('gold')?></p>
        <p class="item">Food: <?=$save->Get('gold')?></p>
    </div>
</section>
</main>