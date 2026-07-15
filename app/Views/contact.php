<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with us for bookings, inquiries, or support</p>
    </div>
</section>

<section class="container contact-section">
    <div class="contact-grid">
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p>We'd love to hear from you. Reach out for bookings, inquiries, or any questions about our services.</p>
            
            <div class="contact-details">
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-phone"></i></div>
                    <div class="contact-text">
                        <h3>Phone</h3>
                        <p>+254 XXX XXX XXX</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact-text">
                        <h3>Email</h3>
                        <p>info@komfort.com</p>
                        <p>bookings@komfort.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-text">
                        <h3>Office</h3>
                        <p>Nairobi, Kenya</p>
                        <p>Open Mon-Fri: 8AM - 6PM</p>
                    </div>
                </div>
            </div>
            
            <div class="social-links">
                <h3>Follow Us</h3>
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        
        <div class="contact-form-card">
            <h2>Send us a Message</h2>
            <form method="POST" action="/contact" class="contact-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Your email address">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Your phone number">
                </div>
                
                <div class="form-group">
                    <label for="service">Service Interest</label>
                    <select id="service" name="service">
                        <option value="">Select a service</option>
                        <option value="corporate_tours">Corporate Tours</option>
                        <option value="eco_tours">Eco Tours</option>
                        <option value="events">Events</option>
                        <option value="retreats">Retreats</option>
                        <option value="road_trips">Road Trips</option>
                        <option value="transfers">Transfers</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required placeholder="Tell us about your travel plans or inquiry"></textarea>
                </div>
                
                <button type="submit" class="btn btn-block">Send Message</button>
            </form>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layouts/app.php';
