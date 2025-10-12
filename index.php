<?php
session_start();

/* Session flags and computed links (no ?:)
 */
$isLogged = (isset($_SESSION['email']) && isset($_SESSION['role']));

$menuOrLogin = '';
if ($isLogged) {
    $menuOrLogin = '/SmartCafe/view/customer/menu.php';
} else {
    $menuOrLogin = '/SmartCafe/view/login.php';
}

/* Footer label that was using a ternary */
$footerMenuOrLoginLabel = '';
if ($isLogged) {
    $footerMenuOrLoginLabel = 'Menu';
} else {
    $footerMenuOrLoginLabel = 'Login';
}
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>SmartCafe — Crafted Coffee & More</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="/SmartCafe/assets/logo.png">
  <link rel="stylesheet" href="/SmartCafe/view/css/site.css">
</head>
<body>

<header class="navbar">
  <div class="nav-wrap">
    <a class="brand" href="/SmartCafe/index.php">
      <img src="/SmartCafe/assets/logo.png" alt="SmartCafe" class="logo">
      <span>SmartCafe</span>
    </a>
    <nav class="links">
      <!-- আগের #menu ছিল; এখন সরাসরি Menu বা Login -->
      <a href="<?php echo $menuOrLogin; ?>">Menu</a>
      <a href="#locations">Locations</a>
      <a href="#about">About</a>
      <?php if ($isLogged): ?>
        <a class="btn" href="/SmartCafe/view/customer/menu.php">Go to Menu</a>
      <?php else: ?>
        <a class="btn" href="/SmartCafe/view/login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="hero">
  <img class="hero-img" src="/SmartCafe/assets/hero.jpg" alt="Freshly brewed coffee">
  <div class="hero-content">
    <h1 class="reveal">Freshly Brewed.<br>Lovingly Crafted.</h1>
    <p class="reveal delay-1">Signature blends, artisan pastries, and your daily dose of joy.</p>
    <div class="cta-row reveal delay-2">
      <!-- Explore Menu এখন সরাসরি customer menu বা login -->
      <a class="btn lg" href="<?php echo $menuOrLogin; ?>">Explore Menu</a>
      <a class="btn ghost" href="#about">Learn More</a>
    </div>
  </div>
  <div class="hero-overlay"></div>
</section>

<section id="menu" class="container">
  <h2 class="section-title reveal">Popular Picks</h2>

  <!-- শুধু ছবি/টেক্সট—কোনো Add to Cart/লিংক নেই -->
  <div class="grid">
    <article class="card pop">
      <img class="thumb" src="/SmartCafe/assets/cappuccino.jpg" alt="Cappuccino">
      <div class="card-body">
        <h3>Cappuccino</h3>
        <p class="muted">Rich espresso with velvety foam.</p>
      </div>
    </article>

    <article class="card pop delay-1">
      <img class="thumb" src="/SmartCafe/assets/coldbrew.jpg" alt="Cold Brew">
      <div class="card-body">
        <h3>Cold Brew</h3>
        <p class="muted">Slow-steeped, bold & refreshing.</p>
      </div>
    </article>

    <article class="card pop delay-2">
      <img class="thumb" src="/SmartCafe/assets/beans.jpg" alt="House Blend Beans">
      <div class="card-body">
        <h3>House Blend Beans</h3>
        <p class="muted">Perfect roast for home brewing.</p>
      </div>
    </article>

    <article class="card pop delay-3">
      <img class="thumb" src="/SmartCafe/assets/pastry.jpg" alt="Butter Croissant">
      <div class="card-body">
        <h3>Butter Croissant</h3>
        <p class="muted">Flaky, buttery & fresh.</p>
      </div>
    </article>
  </div>

  <div class="center reveal" style="margin-top:18px;">
    <span class="hint">More items inside the cafe menu.</span>
  </div>
</section>

<section id="about" class="container about">
  <div class="about-text reveal">
    <h2>About SmartCafe</h2>
    <p>আমরা বিশ্বাস করি ভালো কফি মানেই ভালো দিন। আমাদের রোস্টার থেকে ফ্রেশ বিনস, স্কিলড বারিস্তা আর নির্বাচিত উপাদানে প্রতিটি কাপ বানাই যত্ন দিয়ে।</p>
    <ul class="ticks">
      <li>Signature Blends & Seasonal Specials</li>
      <li>Fresh Bakery & Artisan Pastry</li>
      <li>Loyalty Rewards & Gift Cards</li>
    </ul>
  </div>
</section>

<section id="locations" class="container">
  <h2 class="section-title reveal">Locations</h2>
  <div class="loc-grid">
    <div class="loc-card pop">
      <h4>Tangail</h4>
      <p>Road 11, Park bazazr</p>
      <p class="muted">Open: 8am – 11pm</p>
    </div>
    <div class="loc-card pop delay-1">
      <h4>MAI</h4>
      <p>Road 27, Russia</p>
      <p class="muted">Open: 8am – 11pm</p>
    </div>
    <div class="loc-card pop delay-2">
      <h4>Mohammadpur</h4>
      <p>Sector 7, Dhaka</p>
      <p class="muted">Open: 8am – 11pm</p>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container foot-inner">
    <div>© <?php echo date('Y'); ?> SmartCafe. All rights reserved.</div>
    <div class="foot-links">
      <a href="#about">About</a>
      <a href="#locations">Locations</a>
      <!-- Footer-এও একই লজিক -->
      <a href="<?php echo $menuOrLogin; ?>">
        <?php echo $footerMenuOrLoginLabel; ?>
      </a>
    </div>
  </div>
</footer>

</body>
</html>
