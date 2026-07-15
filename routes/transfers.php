<?php
$pageTitle = 'Transfers';
include __DIR__ . '/../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name !== '' && $email !== '' && $service !== '') {
        $success = 'Thank you! Your transfer request has been received.';
    } else {
        $error = 'Please complete the required fields.';
    }
}
?>

<section class="hero">
    <div class="container">
        <h1>Transfers</h1>
        <p>Book airport or local transfers with a simple request form.</p>
    </div>
</section>

<section class="container" style="padding-bottom: 2rem;">
    <div class="grid">
        <div class="card">
            <h3>Transfer Services</h3>
            <p>Airport pickups, hotel transfers, and private travel support.</p>
        </div>
        <div class="card">
            <h3>Request a Transfer</h3>
            <?php if (!empty($success)) { echo '<p style="color:#0f766e; font-weight:700;">' . htmlspecialchars($success) . '</p>'; } ?>
            <?php if (!empty($error)) { echo '<p style="color:#b91c1c; font-weight:700;">' . htmlspecialchars($error) . '</p>'; } ?>
            <form method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required>
                </div>
                <div class="form-group">
                    <label for="service">Service</label>
                    <select id="service" name="service" required>
                        <option value="">Select one</option>
                        <option value="Airport Pickup">Airport Pickup</option>
                        <option value="Hotel Transfer">Hotel Transfer</option>
                        <option value="Private Tour Transfer">Private Tour Transfer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="4"></textarea>
                </div>
                <button type="submit" class="btn">Send Request</button>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
