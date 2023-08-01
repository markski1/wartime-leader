
<main style="max-width: 980px; max-width: 97%; margin: auto;">
    <h1>Victory.</h1>
    <p>After <?=++$week?> weeks, your scholars have completed their objective.</p>
    <p>Your workers and defenders worked incredibly hard, but it has finally paid out.</p>
    <p>The ritual was carried out, the hell gates have closed, the demons were banished, back where they belong.</p>
    <p>The Realm will know, it was under the command of <?=$save->Get('leader');?>, that the fortress of <?=$save->Get('fortress');?> stood fast and saved the world.</p>
    <p>Your saved game has been erased. <a href="/" hx-post="view/main.php" hx-target="#main">Beginning</a></p>
</main>

<?php
    if (isset($_COOKIE['savegame'])) {
        unset($_COOKIE['savegame']);
        setcookie('savegame', '', -1, '/');
        setcookie('week_report', '', -1, '/');
    }
?>