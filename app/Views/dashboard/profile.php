<?php
$include_header = true;
$include_footer = true;
ob_start();
?>

<section class="dashboard-section">
    <div class="container">
        <div class="dashboard-header">
            <h1>My Profile</h1>
            <a href="/dashboard" class="btn secondary">Back to Dashboard</a>
        </div>

        <div class="profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                    <span class="role-badge role-<?= $user['role'] ?>">
                        <?= ucfirst($user['role']) ?>
                    </span>
                </div>

                <form method="POST" action="/dashboard/profile" class="profile-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   value="<?= htmlspecialchars($old['first_name'] ?? $user['first_name']) ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required 
                                   value="<?= htmlspecialchars($old['last_name'] ?? $user['last_name']) ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        <small>Contact support to change email</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($old['phone'] ?? $user['phone'] ?? '') ?>">
                    </div>
                    
                    <div class="profile-info">
                        <h3>Account Information</h3>
                        <div class="info-row">
                            <span>Member Since:</span>
                            <span><?= date('F j, Y', strtotime($user['created_at'])) ?></span>
                        </div>
                        <div class="info-row">
                            <span>Account Status:</span>
                            <span class="status-<?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                        <?php if ($user['email_verified_at']): ?>
                        <div class="info-row">
                            <span>Email Verified:</span>
                            <span><?= date('F j, Y', strtotime($user['email_verified_at'])) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
