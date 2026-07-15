<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="page-header">
    <div class="container">
        <h1>Booking Details</h1>
        <p>View your booking information</p>
    </div>
</section>

<section class="container booking-details-section">
    <div class="booking-details-card">
        <div class="booking-header">
            <div class="booking-reference">
                <h2><?= htmlspecialchars($booking['booking_reference']) ?></h2>
                <span class="status-badge status-<?= $booking['status'] ?>">
                    <?= ucfirst($booking['status']) ?>
                </span>
            </div>
            <div class="booking-actions">
                <?php if (in_array($booking['status'], ['pending', 'confirmed'])): ?>
                    <form method="POST" action="/booking/cancel/<?= $booking['id'] ?>" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                        <button type="submit" class="btn secondary" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
                    </form>
                <?php endif; ?>
                <a href="/dashboard" class="btn">Back to Dashboard</a>
            </div>
        </div>
        
        <div class="booking-info-grid">
            <div class="info-section">
                <h3>Service Information</h3>
                <div class="info-row">
                    <span>Service Type:</span>
                    <span><?= htmlspecialchars($booking['service_type_name']) ?></span>
                </div>
                <?php if ($booking['destination_name']): ?>
                <div class="info-row">
                    <span>Destination:</span>
                    <span><?= htmlspecialchars($booking['destination_name']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="info-section">
                <h3>Travel Dates</h3>
                <div class="info-row">
                    <span>Start Date:</span>
                    <span><?= date('F j, Y', strtotime($booking['start_date'])) ?></span>
                </div>
                <?php if ($booking['end_date']): ?>
                <div class="info-row">
                    <span>End Date:</span>
                    <span><?= date('F j, Y', strtotime($booking['end_date'])) ?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span>Travelers:</span>
                    <span><?= $booking['number_of_travelers'] ?> person(s)</span>
                </div>
            </div>
            
            <div class="info-section">
                <h3>Location Details</h3>
                <?php if ($booking['pickup_location']): ?>
                <div class="info-row">
                    <span>Pickup:</span>
                    <span><?= htmlspecialchars($booking['pickup_location']) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($booking['dropoff_location']): ?>
                <div class="info-row">
                    <span>Drop-off:</span>
                    <span><?= htmlspecialchars($booking['dropoff_location']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="info-section">
                <h3>Pricing</h3>
                <div class="info-row">
                    <span>Total Amount:</span>
                    <span class="amount-highlight">KES <?= number_format($booking['total_amount'], 2) ?></span>
                </div>
                <div class="info-row">
                    <span>Payment Status:</span>
                    <span><?= in_array($booking['status'], ['paid', 'completed']) ? 'Paid' : 'Pending Payment' ?></span>
                </div>
            </div>
        </div>
        
        <?php if ($booking['special_requirements']): ?>
        <div class="special-requirements">
            <h3>Special Requirements</h3>
            <p><?= htmlspecialchars($booking['special_requirements']) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if ($booking['registration_number']): ?>
        <div class="vehicle-info">
            <h3>Assigned Vehicle</h3>
            <div class="vehicle-details">
                <p><strong>Vehicle:</strong> <?= htmlspecialchars($booking['make'] . ' ' . $booking['model']) ?></p>
                <p><strong>Registration:</strong> <?= htmlspecialchars($booking['registration_number']) ?></p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="booking-meta">
            <p><small>Booking created on: <?= date('F j, Y g:i A', strtotime($booking['created_at'])) ?></small></p>
            <p><small>Last updated: <?= date('F j, Y g:i A', strtotime($booking['updated_at'])) ?></small></p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
