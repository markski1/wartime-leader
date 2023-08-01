<?php

    if (!isset($save)) {
        include_once '../logic/savegame.php';
        $save = new Savegame;
    }

    // if no save exists, show the preface.
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
            <h2>Gameplay</h2>
            <p>
                You must do your actions for each week, then proceed to the next week.<br />
                Depending on the state of your fortress and the progress of the invasion, events will partake during the night.<br />
                All progress is automatically saved on your web browser.
            </p>
            <p><a href="/" hx-post="view/new-game.php" hx-target="#main">Start new game</a></p>
        </div>
    <?php
        exit;
    }

    // otherwise, game interface.
?>

<main>
    <section>
        <h3>Fortress of <?=$save->Get('fortress')?></h3>
        <p class="item">Leader <?=$save->Get('leader')?>, Week <?=$save->Get('week')?></h3>
    </section>
    <section style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: center;">
        <div class="block">
            <h4>Population</h4>
            <p class="item">Total: <?=$save->Get('total_pop')?></p>
            <p>Distribution for the next week:</p>
            <ul>
                <li class="item">Workers: <input class="popInput" type="number" min="10" max="100" value="<?=$save->Get('workers')?>"> percent</li>
                <li class="item">Defenders: <input class="popInput" type="number" min="10" max="100" value="<?=$save->Get('defenders')?>"> percent</li>
                <li class="item">Scholars: <input class="popInput" type="number" min="10" max="100" value="<?=$save->Get('scholars')?>"> percent</li>
            </ul>
        </div>
        <div class="block">
            <h4>State</h4>
            <p class="item">Buildings:</p>
            <ul>
                <li class="item">Farms: <?=$save->Get('farms')?></li>
                <li class="item">Barracks: Level <?=$save->Get('level_barrack')?></li>
            </ul>
            <p class="item">Resources:</p>
            <ul>
                <li class="item">Food: <?=$save->Get('food')?></li>
            </ul>
            <p>Focus for the next week:</p>
            <div style="text-align: center">
                <select name="priority" class="formInput" style="height: 2rem; margin-left: 1rem;">
                    <option value="1">Build more farms</option>
                    <option value="2">Improve barracks</option>
                </select>
            </div>
        </div>
    </section>


    <section style="justify-content: center; margin-top: 1.5rem">
        <div class="block" style="margin: auto; max-width: 30rem; width: 100%">
            <div id="action-area">
                <p class="item">The scout reports <?=$save->Get('demons')?> demons nearby.</p>
                <p class="item">The scholars report <?=$save->Get('progress')?>% progress.</p>
                <button class="formButton">Proceed into the next week.</button><br />
                <div id="events" style="text-align: center;">
                    <p>
                        You are on your first week.
                    </p>
                </div>
            </div>
        </div>
        <p style="text-align: center">
            <small>
                <a href="/" hx-target="#main" hx-confirm="Are you sure?" hx-post="action/delete-game.php">delete save</a>
            </small>
        </p>
    </section>
</main>