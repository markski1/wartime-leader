<?php
    // Inherited from where this document is called: The savegame object in $save, and the week number in $week.

    

    $leader = $save->Get('leader');
    $fortress = $save->Get('fortress');
    $weeks = ++$week;
    $killed = $save->Get('killed');
    $deaths = $save->Get('deaths');
    $property_loss = $save->Get('property_loss');
    $attacks = $save->Get('attacks');

    ob_start();
?>

<main style="max-width: 980px; max-width: 97%; margin: auto;">
    <h1>Victory.</h1>
    <p>After <?=$weeks?> weeks, your scholars have completed their objective.</p>
    <p>Your workers and defenders worked incredibly hard, but it has finally paid out.</p>
    <p>The ritual was carried out, the hell gates have closed, the demons were banished, back where they belong.</p>
    <p>The Realm will know, it was under the command of <?=$leader?>, that the fortress of <?=$fortress?> stood fast and saved the world.</p>
    <p>Statistics</p>
    <ul>
        <li>Killed <?=$killed?> demons.</li>
        <li>Lost <?=$deaths?> people.</li>
        <li>Lost <?=$property_loss?> property.</li>
        <li>Sustained <?=$attacks?> attacks.</li>
    </ul>
    <p>You have been added to the <a href="/" hx-post="view/hall.php" hx-target="#main">Hall of fame</a>.</p>
    <p>Your saved game has been erased. <a href="/" hx-post="view/main.php" hx-target="#main">Beginning</a></p>
</main>

<?php
    if (isset($_COOKIE['savegame'])) {
        unset($_COOKIE['savegame']);
        setcookie('savegame', '', -1, '/');
        setcookie('week_report', '', -1, '/');
    }
    // send output before database stuff which might be slow.
    ob_flush();
    flush();
    ob_end_flush();

    include "../logic/sql.php";
    $con = connect();
    
    $query = $con->prepare("INSERT INTO hall_of_fame (`leader`, `fortress`, `weeks`, `killed`, `deaths`, `property_loss`, `attacks`) VALUES(?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssiiiii", $leader, $fortress, $weeks, $killed, $deaths, $property_loss, $attacks);
    $query->execute();
?>