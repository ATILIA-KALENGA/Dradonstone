<?php
include '../includes/header.php';
?>

<style>
.about-section {
    min-height: 70vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: linear-gradient(to bottom, #f8f9fa, #e6f2f2);
    padding: 60px 20px;
}

.about-section h2 {
    font-size: 2.5rem;
    color: #222;
    margin-bottom: 20px;
    font-weight: 700;
}

.about-section p {
    max-width: 700px;
    font-size: 1.1rem;
    color: #444;
    line-height: 1.8;
    margin-bottom: 15px;
}

.about-section .highlight {
    color: #ffcc00;
    font-weight: 600;
}
</style>

<div class="about-section">
    <h2>About <span class="highlight">Dragonstone Store</span></h2>
    <p>Welcome to <strong>Dragonstone Store</strong> — your trusted destination for high-quality products and unbeatable value. We’re passionate about providing our customers with the best shopping experience possible.</p>
    <p>At Dragonstone, we combine quality craftsmanship, innovative designs, and customer care that sets us apart. Whether you’re shopping for essentials or exclusive finds, we’re here to make it easy, secure, and enjoyable.</p>
    <p>Thank you for being part of our growing community. Your trust means everything to us!</p>
</div>

<?php
include '../includes/footer.php';
?>
