<?php
if (!isset($pref) || !$pref) { $pref = ['id'=>0,'item_name'=>'','spice_level'=>'medium','is_favorite'=>0,'notes'=>'']; }
$msg = isset($msg) ? $msg : '';
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Edit Preference — SmartCafe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/SmartCafe/view/css/site.css">
</head>
<body>

<header class="navbar" id="navbar">
  <div class="nav-wrap">
    <a class="brand" href="/SmartCafe/index.php"><img src="/SmartCafe/assets/logo.png" alt="SmartCafe" class="logo"><span>SmartCafe</span></a>
    <nav class="links">
      <a class="btn" href="/SmartCafe/controller/customer/preferenceController.php">Back</a>
      <a class="btn" href="/SmartCafe/view/customer/dashboard.php">Dashboard</a>
    </nav>
  </div>
</header>

<main class="container">
  <?php if (!empty($msg)): ?><div class="card"><div class="card-body"><p><?php echo htmlspecialchars($msg); ?></p></div></div><?php endif; ?>

  <div class="card">
    <div class="card-body">
      <h3>Edit Preference</h3>

      <form method="post" action="/SmartCafe/controller/customer/preferenceController.php?action=update">
        <input type="hidden" name="id" value="<?php echo (int)$pref['id']; ?>">

        <label for="item_name">Item name</label>
        <input id="item_name" type="text" name="item_name" value="<?php echo htmlspecialchars($pref['item_name']); ?>" required>

        <label for="spice_level">Spice level</label>
        <select id="spice_level" name="spice_level">
          <option value="mild"   <?php if (($pref['spice_level'] ?? '')==='mild')   echo 'selected'; ?>>Mild</option>
          <option value="medium" <?php if (($pref['spice_level'] ?? '')==='medium') echo 'selected'; ?>>Medium</option>
          <option value="hot"    <?php if (($pref['spice_level'] ?? '')==='hot')    echo 'selected'; ?>>Hot</option>
        </select>

        <label style="display:block;margin:8px 0;">
          <input type="checkbox" name="is_favorite" <?php echo !empty($pref['is_favorite']) ? 'checked' : ''; ?>> Mark as favorite
        </label>

        <label for="notes">Notes</label>
        <input id="notes" type="text" name="notes" value="<?php echo htmlspecialchars($pref['notes'] ?? ''); ?>">

        <button class="btn" type="submit">Update</button>
        <a class="btn" href="/SmartCafe/controller/customer/preferenceController.php">Cancel</a>
      </form>
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
