<?php

    if (!isset($save)) {
        include_once '../logic/savegame.php';
        $save = new Savegame;
    }

    if (!$save->SaveExists()) {?>
        <div>
        <p>Wartime leader is a simple game, inspired on <em>Enchanted Fortress</em>, a text-based Android game by Ivan Kravarscan.</p>
        <h2>Preface</h2>
        <p>
            Twisted people have pacted with demons. The hell gates have opened, and demon kind is now roaming your world.<br />
            As time passes this situation only worsens, and the demon army grows stronger and bolder.<br />
            If not stopped, the realm will be overrun.
        </p>
        <p>
            Your scholars believe that, if there was a ritual to summon the demons, there must be one to banish them.
        </p>
        <h2>Objective</h2>
        <p>
            It is your duty to defend your fortress until your scholars can figure this out.<br />
            You must distribute the workforce within your fortress, across the duties of farming, building, defending and researching.<br />
            Too many people working the ground and you will be indefense. Too many people on the frontline and you will collapse from within.
        </p>
        <p>Your success will depend on your ability to adapt to the given time.</p>
        <p><a href="#" hx-post="view/new-game.php" hx-target="#main">Start new game</a></p>
        <p><a href="#" hx-post="action/import-game.php" hx-confirm="not yet implemented" hx-target="#main">Import save file</a></p>
        </div>
    <?php
        return;
    }
?>

<main>
<section>
    <h3>Fortress of <?=$save->Get('fortress')?></h3>
    <p class="item">leader <?=$save->Get('leader')?></h3>
</section>
<section style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: center;">
    <div class="block">
        <h4>State</h4>
        <p class="item">Idle: <?=$save->Get('pop_idle')?></p>
        <p class="item">Farmers: <?=$save->Get('pop_farmers')?></p>
        <p class="item">Defenders: <?=$save->Get('pop_defenders')?></p>
    </div>
    <div class="block">
        <h4>Resources</h4>
        <p class="item">Gold: <?=$save->Get('gold')?></p>
        <p class="item">Food: <?=$save->Get('gold')?></p>
    </div>
    <div class="block">
        <h4>Actions</h4>
        <p class="item">Gold: <?=$save->Get('gold')?></p>
        <p class="item">Food: <?=$save->Get('gold')?></p>
    </div>
</section>
</main>