<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $csrf_token ?? '' ?>">
    <title><?= $title ?? 'Komfort Tours & Travel' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if (isset($include_header) && $include_header): ?>
    <header class="site-header">
        <nav class="container nav-bar">
            <a href="/" class="logo">
                <i class="fas fa-route"></i> Komfort
            </a>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/services">Services</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
                <?php if (\Komfort\App\Middleware\Auth::check()): ?>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register" class="btn">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <?php endif; ?>

    <main class="container">
        <?php if (isset($flash_success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash_success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($flash_error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($flash_error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($flash_warning)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($flash_warning) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($flash_info)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <?= htmlspecialchars($flash_info) ?>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <?php if (isset($include_footer) && $include_footer): ?>
    <footer class="site-footer">
        <div class="container footer-content">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Komfort Tours & Travel</h3>
                    <p>Your trusted partner for unforgettable travel experiences.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/services">Services</a></li>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="/services">Corporate Tours</a></li>
                        <li><a href="/services">Eco Tours</a></li>
                        <li><a href="/services">Events</a></li>
                        <li><a href="/services">Retreats</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <p><i class="fas fa-phone"></i> +254 XXX XXX XXX</p>
                    <p><i class="fas fa-envelope"></i> info@komfort.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Nairobi, Kenya</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Komfort Tours & Travel. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <script src="/assets/js/main.js"></script>
</body>
</html>
