<?php
// Safe defaults if controller didn't set
if (!isset($prefs) || !is_array($prefs)) { $prefs = []; }
if (!isset($msg)) { $msg = ''; }
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Meal Preferences — SmartCafe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/SmartCafe/view/css/site.css">
</head>
<body>

<header class="navbar" id="navbar">
  <div class="nav-wrap">
    <a class="brand" href="/SmartCafe/index.php"><img src="/SmartCafe/assets/logo.png" alt="SmartCafe" class="logo"><span>SmartCafe</span></a>
    <nav class="links">
      <a class="btn" href="/SmartCafe/view/customer/dashboard.php">Dashboard</a>
      <a href="/SmartCafe/view/customer/menu.php">Menu</a>
      <a href="/SmartCafe/view/customer/orders.php">My Orders</a>
      <a class="btn" href="/SmartCafe/view/logout.php">Logout</a>
    </nav>
  </div>
</header>

<main class="container">
  <?php if (!empty($msg)): ?>
    <div class="card"><div class="card-body"><p><?php echo htmlspecialchars($msg); ?></p></div></div>
  <?php endif; ?>

  <h2 class="section-title">Meal Preference — Actions</h2>

  <div class="grid">
    <!-- ADD -->
    <div class="card">
      <div class="card-body">
        <h3 id="add">Add Preference</h3>
        <form method="post" action="/SmartCafe/controller/customer/preferenceController.php?action=store">
          <label for="item_name">Item name</label>
          <input id="item_name" type="text" name="item_name" placeholder="e.g., Cappuccino" required>

          <label for="spice_level">Spice level</label>
          <select id="spice_level" name="spice_level">
            <option value="mild">Mild</option>
            <option value="medium" selected>Medium</option>
            <option value="hot">Hot</option>
          </select>

          <label style="display:block;margin:8px 0;">
            <input type="checkbox" name="is_favorite"> Mark as favorite
          </label>

          <label for="notes">Notes</label>
          <input id="notes" type="text" name="notes" placeholder="No sugar / Oat milk">

          <button class="btn" type="submit">Save</button>
        </form>
      </div>
    </div>

    <!-- LIST + ACTIONS -->
    <div class="card" style="grid-column: span 2;">
      <div class="card-body">
        <h3>Your Preferences</h3>

        <?php if (empty($prefs)): ?>
          <p class="muted">No preferences found.</p>
        <?php else: ?>
          <table style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee;">Item</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee;">Spice</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee;">Fav</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee;">Notes</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($prefs as $p): ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f1f1f1;"><?php echo htmlspecialchars($p['item_name'] ?? ''); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f1f1;"><?php echo htmlspecialchars($p['spice_level'] ?? ''); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f1f1;"><?php echo !empty($p['is_favorite']) ? '★' : '—'; ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f1f1;"><?php echo htmlspecialchars($p['notes'] ?? ''); ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f1f1f1;">
                    <a class="btn" href="/SmartCafe/controller/customer/preferenceController.php?action=toggle&id=<?php echo (int)$p['id']; ?>">Fav</a>
                    <a class="btn" href="/SmartCafe/controller/customer/preferenceController.php?action=edit&id=<?php echo (int)$p['id']; ?>">Edit</a>
                    <a class="btn" href="/SmartCafe/controller/customer/preferenceController.php?action=delete&id=<?php echo (int)$p['id']; ?>" onclick="return confirm('Delete this preference?')">Delete</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<footer class="footer">
  <div class="foot-inner">
    <div>© SmartCafe</div>
    <div class="foot-links"><a href="#">Privacy</a><a href="#">Terms</a></div>
  </div>
</footer>

</body>
</html>
 