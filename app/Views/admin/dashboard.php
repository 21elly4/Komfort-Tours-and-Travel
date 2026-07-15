<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="admin-dashboard">
    <div class="container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <a href="/dashboard" class="btn secondary">Back to User Dashboard</a>
        </div>

        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-content">
                    <h3><?= $totalBookings ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-content">
                    <h3><?= $totalUsers ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-car"></i></div>
                <div class="stat-content">
                    <h3><?= $totalVehicles ?></h3>
                    <p>Total Vehicles</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-content">
                    <h3>KES <?= number_format($totalRevenue, 0) ?></h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <div class="admin-grid">
            <div class="admin-section">
                <div class="section-header">
                    <h2>Pending Bookings</h2>
                    <a href="/admin/bookings" class="btn-link">View All</a>
                </div>
                <?php if (empty($pendingBookings)): ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>No pending bookings</p>
                    </div>
                <?php else: ?>
                    <div class="bookings-list">
                        <?php foreach (array_slice($pendingBookings, 0, 5) as $booking): ?>
                        <div class="booking-card">
                            <div class="booking-info">
                                <h3><?= htmlspecialchars($booking['booking_reference']) ?></h3>
                                <p><i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($booking['start_date'])) ?></p>
                                <p><i class="fas fa-users"></i> <?= $booking['number_of_travelers'] ?> travelers</p>
                            </div>
                            <div class="booking-actions">
                                <form method="POST" action="/admin/booking/confirm/<?= $booking['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                                    <button type="submit" class="btn btn-small">Confirm</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-section">
                <div class="section-header">
                    <h2>Recent Bookings</h2>
                    <a href="/admin/bookings" class="btn-link">View All</a>
                </div>
                <?php if (empty($recentBookings)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No recent bookings</p>
                    </div>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
        </div>

        <div class="admin-quick-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="/admin/bookings" class="action-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Manage Bookings</h3>
                    <p>View and manage all bookings</p>
                </a>
                <a href="/admin/users" class="action-card">
                    <i class="fas fa-users-cog"></i>
                    <h3>Manage Users</h3>
                    <p>View and manage user accounts</p>
                </a>
                <a href="/admin/destinations" class="action-card">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Manage Destinations</h3>
                    <p>Add and update destinations</p>
                </a>
                <a href="/admin/vehicles" class="action-card">
                    <i class="fas fa-truck"></i>
                    <h3>Manage Vehicles</h3>
                    <p>Manage your vehicle fleet</p>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
