<?php
include '../includes/header.php';

// Handle form submission
$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // You can save to database or send email here
    // Example (just simulating success):
    $message_sent = true;
}
?>

<style>
.contact-section {
    min-height: 75vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: linear-gradient(to bottom, #f8f9fa, #e6f2f2);
    padding: 50px 20px;
}

.contact-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 60px;
    width: 90%;
    max-width: 1100px;
}

.contact-info {
    flex: 1;
    min-width: 300px;
    text-align: left;
}

.contact-info ul {
    list-style: none;
    padding: 0;
}

.contact-info li {
    margin: 10px 0;
    font-size: 1rem;
}

.contact-form {
    flex: 1;
    min-width: 300px;
}

.contact-form form {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 25px;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.contact-form button {
    width: 100%;
    background-color: #222;
    color: #ffcc00;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.contact-form button:hover {
    background-color: #ffcc00;
    color: #222;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 15px;
    width: 80%;
    max-width: 600px;
    text-align: center;
    margin-bottom: 20px;
    font-size: 1rem;
}
</style>

<div class="contact-section">
    <h2>Contact <span style="color:#ffcc00;">Us</span></h2>
    <p>We’d love to hear from you! Whether it’s feedback, inquiries, or support — reach out anytime.</p>

    <?php if ($message_sent): ?>
        <div class="success-message">
            ✅ Your message has been sent successfully! We’ll get back to you soon.
        </div>
    <?php endif; ?>

    <div class="contact-container">
        <div class="contact-info">
            <ul>
                <li>📧 <strong>Email:</strong> support@dragonstone.com</li>
                <li>📞 <strong>Phone:</strong> +1 (555) 123-4567</li>
                <li>📍 <strong>Address:</strong> 123 Market Street, Stone City, Earth</li>
                <li>🕓 <strong>Hours:</strong> Mon–Fri, 9 AM – 6 PM</li>
            </ul>
        </div>

        <div class="contact-form">
            <form method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
