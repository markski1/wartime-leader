<?php
    // Inherited from where this document is called: The savegame object in $save, and the week number in $week.

    include "../logic/sql.php";

    $con = connect();

    $result = $con->query("SELECT * FROM hall_of_fame ORDER BY weeks ASC");
?>

<main style="max-width: 980px; max-width: 97%; margin: auto;">
    <h1>Hall of fame</h1>
    <p>Sorted by lowest amount of weeks.</p>
    <?php
        if ($result->num_rows < 1) {
            echo 'No records loaded.';
        }
        else {
            while ($row = $result->fetch_array()) {
                echo '<p>';
                $fortress = htmlspecialchars($row['fortress']);
                $leader = htmlspecialchars($row['leader']);
                echo "{$row['weeks']} weeks: Fortress of '{$fortress}', lead by '{$leader}' <br />";
                echo "<small>killed {$row['killed']}, lost {$row['deaths']} people, lost {$row['property_loss']} buildings, endured {$row['attacks']} attacks.</small>";
                echo '</p>';
            }
        }
    ?>
    <p><a href="/" hx-post="view/main.php" hx-target="#main">Return</a></p>
</main>

<?php
    if (isset($_COOKIE['savegame'])) {
        unset($_COOKIE['savegame']);
        setcookie('savegame', '', -1, '/');
        setcookie('week_report', '', -1, '/');
    }
?>