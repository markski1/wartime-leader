<?php
include "fragments.php";
function DrawGame($save, $error = false) {
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
                    <div hx-target="this">
                        <?php DrawPopulationManager($save->Get('workers'), $save->Get('defenders'), $save->Get('scholars')); ?>
                    </div>
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
                    <a href="/" hx-target="#main" hx-confirm="Are you sure?" hx-post="action/delete-game.php">delete save</a>
                </small>
            </p>
        </form>
    </main>
<?php
}
?>