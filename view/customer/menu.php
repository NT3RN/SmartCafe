<?php
session_start();

/* 
   1) AUTH GUARD (Customer only)
   */
$loggedIn = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$loggedIn || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

/* 
   2) LOAD MODEL + FETCH DB ITEMS
   */
require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/itemModel.php");

/* @var array $dbItems */
$dbItems = getActiveMenuItems();

/*
   3) DEFAULT ITEMS (shown if DB empty; also used for merge)
    */
$defaultItems = [
    ['menu_item_id' => -101, 'name' => 'Pizza',        'description' => 'Cheesy pizza slice',    'price' => 150, 'image_url' => 'pizza.jpg'],
    ['menu_item_id' => -102, 'name' => 'Burger',       'description' => 'Juicy beef burger',     'price' => 180, 'image_url' => 'burger.jpg'],
    ['menu_item_id' => -103, 'name' => 'Shawarma',     'description' => 'Chicken shawarma roll',  'price' => 160, 'image_url' => 'shawarma.jpg'],
    ['menu_item_id' => -104, 'name' => 'Cold Coffee',  'description' => 'Iced cold coffee',      'price' => 120, 'image_url' => 'cold_coffee.jpg'],
    ['menu_item_id' => -105, 'name' => 'Hot Coffee',   'description' => 'Fresh hot coffee',      'price' => 100, 'image_url' => 'hot_coffee.jpg'],
    ['menu_item_id' => -106, 'name' => 'Fried Rice',   'description' => 'Egg fried rice',        'price' => 140, 'image_url' => 'fried_rice.jpg'],
    ['menu_item_id' => -107, 'name' => 'Chicken Fry',  'description' => 'Crispy chicken fry',    'price' => 170, 'image_url' => 'chicken_fry.jpg'],
    ['menu_item_id' => -108, 'name' => 'Vegetable',    'description' => 'Mixed veg bowl',        'price' => 110, 'image_url' => 'vegetable.jpg'],
];

/* 
   4) HELPER: normalize string (for name-based merge)
    */
function normalizeString($value) {
    if (!isset($value)) {
        return '';
    }
    $trimmed = trim($value);
    $lower   = strtolower($trimmed);
    return $lower;
}

/* 
   5) MERGE: DB overrides Default by normalized name
    */
$byName = [];

// Seed with defaults first
for ($i = 0; $i < count($defaultItems); $i++) {
    $d = $defaultItems[$i];
    $key = normalizeString(isset($d['name']) ? $d['name'] : '');
    $byName[$key] = $d;
}

// Override with DB items if names match; or add new if not present
for ($j = 0; $j < count($dbItems); $j++) {
    $it = $dbItems[$j];
    $key = normalizeString(isset($it['name']) ? $it['name'] : '');
    $byName[$key] = $it; // DB wins
}

// Flatten to a simple indexed array
$items = array_values($byName);

/* 
   6) FLASH / QUERY MESSAGE (safe)
    */
$msg = '';
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Menu - SmartCafe</title>
  <link rel="stylesheet" href="/SmartCafe/view/css/customer.css">
</head>
<body>

<header class="topbar">
  <div class="brand">SmartCafe</div>
  <nav>
    <a class="active" href="/SmartCafe/view/customer/menu.php">Menu</a>
    <a href="/SmartCafe/view/customer/cart.php">Cart</a>
    <a href="/SmartCafe/view/customer/orders.php">My Orders</a>
    <a href="/SmartCafe/view/logout.php">Logout</a>
  </nav>
</header>

<main class="container">
  <h1>Menu</h1>

  <?php if ($msg !== ''): ?>
    <div class="alert"><?php echo $msg; ?></div>
  <?php endif; ?>

  <div class="grid">
    <?php
    // Render each item card
    for ($k = 0; $k < count($items); $k++):
        $it = $items[$k];

        // ID & default flag
        $id = 0;
        if (isset($it['menu_item_id'])) {
            $id = (int)$it['menu_item_id'];
        }
        $isDefault = ($id <= 0);

        //  Image path (fallback to placeholder if empty)
        $rawImg = '';
        if (isset($it['image_url'])) {
            $rawImg = $it['image_url'];
        }
        if (!isset($rawImg) || $rawImg === '' || $rawImg === false) {
            $rawImg = 'placeholder.png';
        }
        $img = htmlspecialchars($rawImg);

        //  Name (required for display)
        $rawName = isset($it['name']) ? $it['name'] : '';
        $name = htmlspecialchars($rawName);

        //  Description (optional)
        $rawDesc = '';
        if (isset($it['description'])) {
            $rawDesc = $it['description'];
        }
        $desc = htmlspecialchars($rawDesc);

        //  Price
        $priceVal = 0.0;
        if (isset($it['price'])) {
            $priceVal = (float)$it['price'];
        }
        $priceTxt = number_format($priceVal, 2);
    ?>
      <div class="card">
        <img
          class="thumb"
          src="/SmartCafe/view/images/<?php echo $img; ?>"
          alt="<?php echo $name; ?>"
        >
        <!-- ↑ <?php echo $name; ?> image path: /SmartCafe/view/images/<?php echo $img; ?> -->

        <div class="card-body">
          <h3><?php echo $name; ?></h3>

          <?php if ($desc !== ''): ?>
            <p class="muted"><?php echo $desc; ?></p>
          <?php endif; ?>

          <div class="row-between">
            <span class="price">৳ <?php echo $priceTxt; ?></span>
            <span class="badge"><?php echo $isDefault ? 'New' : 'Existing'; ?></span>
            <!-- Add to Cart Form -->
            <form method="post" action="/SmartCafe/controller/customer/cartController.php">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="menu_item_id" value="<?php echo $id; ?>">

              <?php if ($isDefault): ?>
                <!-- Default item (not in DB): send name & price for cart -->
                <input type="hidden" name="name"  value="<?php echo $name; ?>">
                <input type="hidden" name="price" value="<?php echo $priceVal; ?>">
              <?php endif; ?>

              <input type="number" name="qty" min="1" value="1" class="qty">
              <button class="btn sm" type="submit">Add to Cart</button>
            </form>
          </div>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</main>

</body>
</html>
