<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="page-header">
    <div class="container">
        <h1>Book a Trip</h1>
        <p>Create your next unforgettable travel experience</p>
    </div>
</section>

<section class="container booking-section">
    <div class="booking-form-card">
        <form method="POST" action="/booking/create" class="booking-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            
            <div class="form-section">
                <h2>Select Service</h2>
                <div class="form-group">
                    <label for="service_type_id">Service Type *</label>
                    <select id="service_type_id" name="service_type_id" required>
                        <option value="">Choose a service</option>
                        <?php foreach ($serviceTypes as $service): ?>
                            <option value="<?= $service['id'] ?>" data-price="<?= $service['base_price'] ?>">
                                <?= htmlspecialchars($service['name']) ?> - KES <?= number_format($service['base_price'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Destination</h2>
                <div class="form-group">
                    <label for="destination_id">Destination (Optional)</label>
                    <select id="destination_id" name="destination_id">
                        <option value="">Select destination</option>
                        <?php foreach ($destinations as $destination): ?>
                            <option value="<?= $destination['id'] ?>">
                                <?= htmlspecialchars($destination['name']) ?> - <?= htmlspecialchars($destination['location']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Travel Details</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date *</label>
                        <input type="date" id="start_date" name="start_date" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" min="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="number_of_travelers">Number of Travelers *</label>
                    <input type="number" id="number_of_travelers" name="number_of_travelers" required min="1" max="50" value="1">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Location Details</h2>
                <div class="form-group">
                    <label for="pickup_location">Pickup Location</label>
                    <input type="text" id="pickup_location" name="pickup_location" placeholder="Enter pickup location">
                </div>
                <div class="form-group">
                    <label for="dropoff_location">Drop-off Location</label>
                    <input type="text" id="dropoff_location" name="dropoff_location" placeholder="Enter drop-off location">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Additional Information</h2>
                <div class="form-group">
                    <label for="special_requirements">Special Requirements</label>
                    <textarea id="special_requirements" name="special_requirements" rows="4" placeholder="Any special requests or requirements"></textarea>
                </div>
            </div>
            
            <div class="booking-summary">
                <h3>Estimated Total</h3>
                <div class="total-amount">
                    <span id="total_amount">KES 0.00</span>
                </div>
                <p class="total-note">Final price may vary based on specific requirements</p>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-large">Create Booking</button>
                <a href="/dashboard" class="btn secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_type_id');
    const travelersInput = document.getElementById('number_of_travelers');
    const totalDisplay = document.getElementById('total_amount');
    
    function calculateTotal() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const basePrice = parseFloat(selectedOption.dataset.price) || 0;
        const travelers = parseInt(travelersInput.value) || 1;
        const total = basePrice * travelers;
        totalDisplay.textContent = 'KES ' + total.toLocaleString('en-KE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    serviceSelect.addEventListener('change', calculateTotal);
    travelersInput.addEventListener('input', calculateTotal);
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
