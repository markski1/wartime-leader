<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Wartime leader</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="container">
            <h1>Wartime leader</h1>
            <div id="main" class="main" hx-get="view/main.php" hx-trigger="load"></div>
        </div>
        <footer>
            <small>
                no rights reserved / <a href="https://markski.ar">markski</a> / <a href="https://markski.ar/donate">leave a tip</a><br />
                <div hx-target="this">
                    built using <a href="#" hx-post="action/htmx.php">htmx</a></small><br />
                </div>
        </footer>
    </body>
</html>
<script src="./assets/htmx.min.js"></script>