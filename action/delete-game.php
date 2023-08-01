<?php
    if (isset($_COOKIE['savegame'])) {
        unset($_COOKIE['savegame']);
        setcookie('savegame', '', -1, '/');
        setcookie('week_report', '', -1, '/');
    }
?>

<p>Save deleted.</p>
<p><a href="/" hx-post="view/new-game.php" hx-target="#main">Start new game</a></p>