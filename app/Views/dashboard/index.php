<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <h1>Welcome back, <?= htmlspecialchars($user['first_name']) ?>!</h1>
                <p>Here's an overview of your travel activities</p>
            </div>
            <div class="dashboard-actions">
                <a href="/dashboard/bookings" class="btn">View All Bookings</a>
                <a href="/dashboard/profile" class="btn secondary">Edit Profile</a>
            </div>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-content">
                    <h3><?= count($recentBookings) ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-plane-departure"></i></div>
                <div class="stat-content">
                    <h3><?= count($upcomingBookings) ?></h3>
                    <p>Upcoming Trips</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <h3><?= count(array_filter($recentBookings, fn($b) => $b['status'] === 'completed')) ?></h3>
                    <p>Completed Trips</p>
                </div>
            </div>
        </div>

        <?php if (!empty($upcomingBookings)): ?>
        <div class="dashboard-section-content">
            <h2>Upcoming Trips</h2>
            <div class="bookings-list">
                <?php foreach ($upcomingBookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-info">
                        <h3><?= htmlspecialchars($booking['booking_reference']) ?></h3>
                        <p><i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($booking['start_date'])) ?></p>
                        <?php if ($booking['end_date']): ?>
                            <p><i class="fas fa-calendar-times"></i> <?= date('F j, Y', strtotime($booking['end_date'])) ?></p>
                        <?php endif; ?>
                        <p><i class="fas fa-users"></i> <?= $booking['number_of_travelers'] ?> Traveler(s)</p>
                    </div>
                    <div class="booking-status">
                        <span class="status-badge status-<?= $booking['status'] ?>">
                            <?= ucfirst($booking['status']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($recentBookings)): ?>
        <div class="dashboard-section-content">
            <h2>Recent Bookings</h2>
            <div class="bookings-list">
                <?php foreach ($recentBookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-info">
                        <h3><?= htmlspecialchars($booking['booking_reference']) ?></h3>
                        <p><i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($booking['start_date'])) ?></p>
                        <p><i class="fas fa-money-bill"></i> KES <?= number_format($booking['total_amount'], 2) ?></p>
                    </div>
                    <div class="booking-status">
                        <span class="status-badge status-<?= $booking['status'] ?>">
                            <?= ucfirst($booking['status']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($recentBookings)): ?>
        <div class="empty-state">
            <i class="fas fa-suitcase-rolling"></i>
            <h2>No bookings yet</h2>
            <p>Start your journey by booking your first trip with us!</p>
            <a href="/services" class="btn">Explore Services</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
