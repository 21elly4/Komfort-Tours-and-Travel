<?php
$pageTitle = isset($pageTitle) ? $pageTitle : 'Tours & Travel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | VoyageHub</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <nav class="container nav-bar">
            <a href="/index.php" class="logo">VoyageHub</a>
            <ul class="nav-links">
                <li><a href="/index.php">Home</a></li>
                <li><a href="/routes/cooporatetours.php">Cooperate Tours</a></li>
                <li><a href="/routes/ecotours.php">Eco Tours</a></li>
                <li><a href="/routes/events.php">Events</a></li>
                <li><a href="/routes/retreat.php">Retreats</a></li>
                <li><a href="/routes/roadtrips.php">Road Trips</a></li>
                <li><a href="/routes/transfers.php">Transfers</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
