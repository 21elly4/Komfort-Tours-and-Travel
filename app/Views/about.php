<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="page-header">
    <div class="container">
        <h1>About Us</h1>
        <p>Learn more about Komfort Tours & Travel</p>
    </div>
</section>

<section class="container about-section">
    <div class="about-content">
        <div class="about-text">
            <h2>Our Story</h2>
            <p>Komfort Tours & Travel was founded with a simple mission: to provide unforgettable travel experiences that combine comfort, adventure, and authentic cultural encounters. We believe that travel should be more than just visiting places—it should be about creating memories that last a lifetime.</p>
            <p>With years of experience in the travel industry, our team of dedicated professionals works tirelessly to ensure every journey is perfectly planned and executed. From corporate retreats to eco-adventures, we specialize in creating personalized travel experiences that cater to your unique preferences and needs.</p>
        </div>
        
        <div class="about-image">
            <div class="placeholder-image">
                <i class="fas fa-mountain"></i>
                <p>Travel Image Placeholder</p>
            </div>
        </div>
    </div>
</section>

<section class="container values-section">
    <div class="section-header">
        <h2>Our Values</h2>
        <p>The principles that guide everything we do</p>
    </div>
    
    <div class="grid">
        <div class="card value-card">
            <div class="value-icon"><i class="fas fa-heart"></i></div>
            <h3>Customer First</h3>
            <p>Your satisfaction is our top priority. We go above and beyond to ensure every aspect of your journey exceeds expectations.</p>
        </div>
        
        <div class="card value-card">
            <div class="value-icon"><i class="fas fa-shield-alt"></i></div>
            <h3>Safety & Reliability</h3>
            <p>We maintain the highest safety standards and work with trusted partners to ensure your travel is secure and worry-free.</p>
        </div>
        
        <div class="card value-card">
            <div class="value-icon"><i class="fas fa-leaf"></i></div>
            <h3>Sustainability</h3>
            <p>We're committed to responsible tourism that respects local communities and preserves natural environments for future generations.</p>
        </div>
        
        <div class="card value-card">
            <div class="value-icon"><i class="fas fa-lightbulb"></i></div>
            <h3>Innovation</h3>
            <p>We continuously improve our services and embrace new technologies to provide better travel experiences.</p>
        </div>
    </div>
</section>

<section class="container team-section">
    <div class="section-header">
        <h2>Our Team</h2>
        <p>Meet the passionate people behind Komfort Tours & Travel</p>
    </div>
    
    <div class="team-grid">
        <div class="team-member">
            <div class="team-avatar">
                <i class="fas fa-user-tie"></i>
            </div>
            <h3>John Doe</h3>
            <p class="team-role">Founder & CEO</p>
            <p>Visionary leader with 15+ years in travel industry</p>
        </div>
        
        <div class="team-member">
            <div class="team-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h3>Jane Smith</h3>
            <p class="team-role">Operations Manager</p>
            <p>Expert in logistics and travel coordination</p>
        </div>
        
        <div class="team-member">
            <div class="team-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h3>Michael Johnson</h3>
            <p class="team-role">Travel Consultant</p>
            <p>Specialist in custom itinerary planning</p>
        </div>
        
        <div class="team-member">
            <div class="team-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h3>Sarah Williams</h3>
            <p class="team-role">Customer Relations</p>
            <p>Dedicated to exceptional customer service</p>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>500+</h3>
                <p>Happy Travelers</p>
            </div>
            <div class="stat-item">
                <h3>50+</h3>
                <p>Destinations</p>
            </div>
            <div class="stat-item">
                <h3>10+</h3>
                <p>Years Experience</p>
            </div>
            <div class="stat-item">
                <h3>98%</h3>
                <p>Satisfaction Rate</p>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
