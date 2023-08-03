<?php
    include "game-view.php";
    if (!isset($save)) {
        include_once '../logic/savegame.php';
        $save = new Savegame;
    }

    // if no save exists, show the preface.
    if (!$save->SaveExists()) {?>
        <main style="max-width: 980px; max-width: 97%; margin: auto;">
            <h1>Wartime leader</h1>
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
        </main>
    <?php
        exit;
    }
    else {
        DrawGame($save);
    }
?>

