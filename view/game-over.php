<main style="max-width: 980px; max-width: 97%; margin: auto;">
    <h1>Defeat.</h1>
    <p>After <?=++$week?> weeks, <?=$save->Get('fortress');?> has fallen. All <?=$save->Get('total_pop')?> are dead, and so are you.</p>
    <p>Your scholars have failed at their task, either by incompetence, or by lack of time and numbers.</p>
    <p>Your defenders fought bravely, but were mere humans at the end of the day, and stood no chance against the demon army, allowed to grow too large powerful.</p>
    <p>With the fall of your fortress, nothing else stands in their way. It's over.</p>
    <p>Your saved game has been erased. <a href="/" hx-post="view/main.php" hx-target="#main">Beginning</a></p>
</main>

<?php
    if (isset($_COOKIE['savegame'])) {
        unset($_COOKIE['savegame']);
        setcookie('savegame', '', -1, '/');
        setcookie('week_report', '', -1, '/');
    }
?>