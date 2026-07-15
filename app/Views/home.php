<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="hero">
    <div class="hero-grid container">
        <div>
            <p class="hero-subtitle">Plan your next escape</p>
            <h1>Discover unforgettable travel experiences with Komfort Tours & Travel.</h1>
            <p>From cooperative tours to eco adventures, retreats, events, road trips, and airport transfers, everything is planned around your journey.</p>
            <div class="hero-buttons">
                <a class="btn" href="/services">Explore Tours</a>
                <a class="btn secondary" href="/contact">Book Transfer</a>
            </div>
        </div>
        <div class="card hero-card">
            <h2>Why travelers choose us</h2>
            <ul class="feature-list">
                <li><i class="fas fa-check"></i> Custom travel planning</li>
                <li><i class="fas fa-check"></i> Reliable local support</li>
                <li><i class="fas fa-check"></i> Flexible packages and transfers</li>
                <li><i class="fas fa-check"></i> Competitive pricing</li>
            </ul>
        </div>
    </div>
</section>

<section class="container">
    <div class="section-header">
        <h2>Our Services</h2>
        <p>Choose from our wide range of travel services</p>
    </div>
    <div class="grid">
        <div class="card service-card">
            <div class="card-icon"><i class="fas fa-users"></i></div>
            <h3>Corporate Tours</h3>
            <p>Group-friendly journeys with comfort and coordination for teams and partnerships.</p>
            <a href="/services" class="card-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card service-card">
            <div class="card-icon"><i class="fas fa-leaf"></i></div>
            <h3>Eco Tours</h3>
            <p>Responsible exploration with nature-focused itineraries and sustainable travel.</p>
            <a href="/services" class="card-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card service-card">
            <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
            <h3>Events</h3>
            <p>Well-managed event travel and destination experiences for conferences and gatherings.</p>
            <a href="/services" class="card-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card service-card">
            <div class="card-icon"><i class="fas fa-spa"></i></div>
            <h3>Retreats</h3>
            <p>Restorative escapes with personalized scheduling for wellness and relaxation.</p>
            <a href="/services" class="card-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card service-card">
            <div class="card-icon"><i class="fas fa-car"></i></div>
            <h3>Road Trips</h3>
            <p>Explore routes, scenic stops, and flexible road trip itineraries at your own pace.</p>
            <a href="/services" class="card-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card service-card">
            <div class="card-icon"><i class="fas fa-shuttle-van"></i></div>
            <h3>Transfers</h3>
            <p>Airport pickups, hotel transfers, and private travel support for seamless journeys.</p>
            <a href="/services" class="card-link">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<?php if (!empty($destinations)): ?>
<section class="container destinations-section">
    <div class="section-header">
        <h2>Popular Destinations</h2>
        <p>Explore our most sought-after travel destinations</p>
    </div>
    <div class="grid">
        <?php foreach ($destinations as $destination): ?>
        <div class="card destination-card">
            <div class="destination-image">
                <img src="<?= htmlspecialchars($destination['image_url'] ?? '/assets/images/placeholder.jpg') ?>" 
                     alt="<?= htmlspecialchars($destination['name']) ?>">
            </div>
            <div class="destination-content">
                <h3><?= htmlspecialchars($destination['name']) ?></h3>
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($destination['location']) ?></p>
                <p><?= htmlspecialchars(substr($destination['description'] ?? '', 0, 100)) ?>...</p>
                <a href="/destinations/<?= $destination['id'] ?>" class="card-link">Explore <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your Journey?</h2>
            <p>Book your next adventure with Komfort Tours & Travel today.</p>
            <a href="/contact" class="btn cta-btn">Get Started</a>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
