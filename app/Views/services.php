<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="page-header">
    <div class="container">
        <h1>Our Services</h1>
        <p>Choose from our wide range of professional travel services</p>
    </div>
</section>

<section class="container services-section">
    <div class="grid">
        <div class="card service-card-large">
            <div class="service-icon-large"><i class="fas fa-users"></i></div>
            <h2>Corporate Tours</h2>
            <p>Group-friendly journeys with comfort and coordination for teams, partnerships, and corporate travel. Perfect for team building and business trips.</p>
            <ul class="service-features">
                <li><i class="fas fa-check"></i> Custom itineraries for teams</li>
                <li><i class="fas fa-check"></i> Professional coordination</li>
                <li><i class="fas fa-check"></i> Luxury transport options</li>
                <li><i class="fas fa-check"></i> Group discounts available</li>
            </ul>
            <a href="/contact" class="btn">Book Corporate Tour</a>
        </div>

        <div class="card service-card-large">
            <div class="service-icon-large"><i class="fas fa-leaf"></i></div>
            <h2>Eco Tours</h2>
            <p>Responsible exploration with nature-focused itineraries and sustainable travel practices. Experience the beauty of nature while protecting it.</p>
            <ul class="service-features">
                <li><i class="fas fa-check"></i> Nature-focused destinations</li>
                <li><i class="fas fa-check"></i> Sustainable travel practices</li>
                <li><i class="fas fa-check"></i> Local community support</li>
                <li><i class="fas fa-check"></i> Eco-friendly accommodations</li>
            </ul>
            <a href="/contact" class="btn">Book Eco Tour</a>
        </div>

        <div class="card service-card-large">
            <div class="service-icon-large"><i class="fas fa-calendar-alt"></i></div>
            <h2>Events</h2>
            <p>Well-managed event travel and destination experiences for conferences, retreats, weddings, and private gatherings.</p>
            <ul class="service-features">
                <li><i class="fas fa-check"></i> Event logistics management</li>
                <li><i class="fas fa-check"></i> Destination coordination</li>
                <li><i class="fas fa-check"></i> Guest transport services</li>
                <li><i class="fas fa-check"></i> On-site support staff</li>
            </ul>
            <a href="/contact" class="btn">Plan Event</a>
        </div>

        <div class="card service-card-large">
            <div class="service-icon-large"><i class="fas fa-spa"></i></div>
            <h2>Retreats</h2>
            <p>Restorative escapes with personalized scheduling for wellness, reflection, and slow travel. Perfect for couples, families, and small groups.</p>
            <ul class="service-features">
                <li><i class="fas fa-check"></i> Wellness-focused itineraries</li>
                <li><i class="fas fa-check"></i> Personalized scheduling</li>
                <li><i class="fas fa-check"></i> Peaceful destinations</li>
                <li><i class="fas fa-check"></i> Private group options</li>
            </ul>
            <a href="/contact" class="btn">Book Retreat</a>
        </div>

        <div class="card service-card-large">
            <div class="service-icon-large"><i class="fas fa-car"></i></div>
            <h2>Road Trips</h2>
            <p>Explore routes, scenic stops, and flexible road trip itineraries at your own pace with professional drivers and route guides.</p>
            <ul class="service-features">
                <li><i class="fas fa-check"></i> Scenic route planning</li>
                <li><i class="fas fa-check"></i> Professional local drivers</li>
                <li><i class="fas fa-check"></i> Flexible itineraries</li>
                <li><i class="fas fa-check"></i> Custom stops available</li>
            </ul>
            <a href="/contact" class="btn">Plan Road Trip</a>
        </div>

        <div class="card service-card-large">
            <div class="service-icon-large"><i class="fas fa-shuttle-van"></i></div>
            <h2>Transfers</h2>
            <p>Airport pickups, hotel transfers, and private travel support for seamless journeys between destinations.</p>
            <ul class="service-features">
                <li><i class="fas fa-check"></i> Airport pickup services</li>
                <li><i class="fas fa-check"></i> Hotel transfers</li>
                <li><i class="fas fa-check"></i> Private transport options</li>
                <li><i class="fas fa-check"></i> 24/7 availability</li>
            </ul>
            <a href="/contact" class="btn">Book Transfer</a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Need a Custom Package?</h2>
            <p>Contact us to create a personalized travel experience tailored to your needs.</p>
            <a href="/contact" class="btn cta-btn">Get in Touch</a>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
