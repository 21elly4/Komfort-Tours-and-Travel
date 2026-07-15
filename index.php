<?php
$pageTitle = 'Home';
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-grid container">
        <div>
            <p style="text-transform: uppercase; letter-spacing: 0.2em; color: #0f766e; font-weight: 700;">Plan your next escape</p>
            <h1>Discover unforgettable travel experiences with VoyageHub.</h1>
            <p>From cooperative tours to eco adventures, retreats, events, road trips, and airport transfers, everything is planned around your journey.</p>
            <a class="btn" href="/routes/cooporatetours.php">Explore Tours</a>
            <a class="btn secondary" href="/routes/transfers.php" style="margin-left: 0.5rem;">Book Transfer</a>
        </div>
        <div class="card">
            <h2>Why travelers choose us</h2>
            <ul>
                <li>Custom travel planning</li>
                <li>Reliable local support</li>
                <li>Flexible packages and transfers</li>
            </ul>
        </div>
    </div>
</section>

<section class="container">
    <div class="grid">
        <div class="card">
            <h3>Cooperate Tours</h3>
            <p>Group-friendly journeys with comfort and coordination.</p>
        </div>
        <div class="card">
            <h3>Eco Tours</h3>
            <p>Responsible exploration with nature-focused itineraries.</p>
        </div>
        <div class="card">
            <h3>Events</h3>
            <p>Well-managed event travel and destination experiences.</p>
        </div>
        <div class="card">
            <h3>Retreats</h3>
            <p>Restorative escapes with personalized scheduling.</p>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
