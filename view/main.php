<?php

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

    // otherwise, game interface.
?>

<main>
    <section>
        <h3>Fortress of <?=$save->Get('fortress')?></h3>
        <p class="item">Leader <?=$save->Get('leader')?>, Week <?=$save->Get('week')?></h3>
    </section>
    <form hx-post="action/next-week.php" hx-target="#main">
        <section style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: center;">
            <div class="block">
                <h4>Population</h4>
                <p class="item">Total: <?=$save->Get('total_pop')?></p>
                <p>Distribution for the next week:</p>
                <div hx-post="view/component/population_mgmt.php" hx-trigger="load" hx-target="this"></div>
            </div>
            <div class="block">
                <h4>State</h4>
                <p class="item">Buildings:</p>
                <ul>
                    <li class="item"><b>Farms:</b> <?=$save->Get('farms')?> (will feed <?=$save->Get('farms') * 20?> people)</li>
                    <li class="item"><b>Houses:</b> <?=$save->Get('houses')?> (will fit <?=$save->Get('houses') * 10?> people)</li>
                </ul>
                <p class="item">Stats:</p>
                <ul>
                    <li class="item"><b>Barracks level:</b> <?=$save->Get('barracks')?> / 5</li>
                    <li class="item"><b>Wall condition:</b> <?=$save->Get('walls')?>%</li>
                </ul>
            </div>
            <div class="block">
                <h4>Actions</h4>
                <p class="item">Split the workforce in:</p>
                <ul>
                    <li class="item"><label><input type="checkbox" class="actionBox" <?php if (isset($_POST['farms'])) echo 'checked';?> name="farms"> Build more farms</label></li>
                    <li class="item"><label><input type="checkbox" class="actionBox" <?php if (isset($_POST['houses'])) echo 'checked';?> name="houses"> Build more houses</label></li>
                    <li class="item"><label><input type="checkbox" class="actionBox" <?php if (isset($_POST['barracks'])) echo 'checked';?> name="barracks"> Improve the barracks</label></li>
                    <li class="item"><label><input type="checkbox" class="actionBox" <?php if (isset($_POST['walls'])) echo 'checked';?> name="walls"> Improve the walls</label></li>
                </ul>
            </div>
        </section>


        <section style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: center;">
            <div class="block" style="max-width: 30rem">
                <input type="submit" class="formButton" style="width: 100%" value="Proceed into the next week" />
                <?php
                    if (isset($error)) echo "<p style='color: red;'>{$error}</p>";
                ?>
                <div id="action-area">
                    <p class="item">The scouts reports <?=$save->Get('demons')?> demons nearby.</p>
                    <p class="item">
                        <small>
                            They look: 
                            <?php
                                $aggro = $save->Get('aggresivity');
                                if ($aggro > 80) {
                                    echo "<span style='color: #FF0000'>ready to kill</span>.";
                                }
                                else if ($aggro > 50) {
                                    echo "<span style='color: #FF2222'>vicious</span>.";
                                }
                                else if ($aggro > 30) {
                                    echo "<span style='color: #FFAAAA'>aggresive</span>.";
                                }
                                else {
                                    echo "<span style='color: #22AA22'>passive</span>.";
                                }
                            ?>
                        </small>
                    </p>
                    <p class="item">The scholars report <?=number_format($save->Get('progress'), 2)?>% progress.</p>
                    <h4 style="margin: 1rem">Previous week report:</h4>
                    <div id="events" class="innerBlock" style="text-align: center;">
                        <p>
                            <?=$save->GetWeekReport()?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <p style="text-align: center">
            <small>
                <?php
                    if (isset($debug)) {
                        echo '<p>previous week debug</p>';
                        echo $debug;
                    }
                ?>
                <a href="/" hx-target="#main" hx-confirm="Are you sure?" hx-post="action/delete-game.php">delete save</a>
            </small>
        </p>
    </form>
</main>