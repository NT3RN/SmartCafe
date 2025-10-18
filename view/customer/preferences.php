<?php
session_start();

$isLogged = (isset($_SESSION["email"]) && isset($_SESSION["role"]));
if (!$isLogged || $_SESSION["role"] !== "Customer") {
    header("Location: /SmartCafe/view/login.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/SmartCafe/model/customer/preferenceModel.php");

$customerId = 0;
if (isset($_SESSION['customer_id'])) {
    $customerId = $_SESSION['customer_id'];
}
if (!$customerId) {
    $emailForLookup = '';
    if (isset($_SESSION['email'])) {
        $emailForLookup = $_SESSION['email'];
    }
    $customerId = pref_getCustomerIdByEmail($emailForLookup);
}

$prefs = pref_all($customerId);

$msg = '';
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
}
?>
<!doctype html>
<html>
<head>
  <title>Preferences - SmartCafe</title>
  <link rel="icon" href="/SmartCafe/assets/logo.png">
  <link rel="stylesheet" href="/SmartCafe/view/css/customer.css">
</head>
<body>

<header class="topbar">
  <div class="brand">SmartCafe</div>
  <nav>
    <a href="/SmartCafe/view/customer/customerDashboard.php">Dashboard</a>
    <a href="/SmartCafe/view/customer/menu.php">Menu</a>
    <a href="/SmartCafe/view/customer/cart.php">Cart</a>
    <a href="/SmartCafe/view/customer/orders.php">My Orders</a>
    <a class="active" href="/SmartCafe/view/customer/preferences.php">Preferences</a>
    <a href="/SmartCafe/view/logout.php">Logout</a>
  </nav>
</header>

<main class="container">
  <h1>Meal Preferences</h1>

  <?php if ($msg !== ''): ?>
    <div class="alert"><?php echo $msg; ?></div>
  <?php endif; ?>

  <div class="grid">

    <div class="card" style="padding:16px;">
      <h3>Add Preference</h3>
      <form method="post" action="/SmartCafe/controller/customer/preferenceController.php">
        <input type="hidden" name="action" value="add">
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
          <input
            type="text"
            name="preference_type"
            placeholder="Type (e.g., Spicy, Vegan)"
            required
            style="padding:10px;border:1px solid #ccc;border-radius:10px;min-width:240px;"
          >
          <input
            type="text"
            name="details"
            placeholder="Details (optional)"
            style="padding:10px;border:1px solid #ccc;border-radius:10px;min-width:280px;"
          >
          <button class="btn" type="submit">Add</button>
        </div>
      </form>
    </div>

    <div class="card" style="padding:16px;">
      <h3>Saved Preferences</h3>
      <?php
      $hasPrefs = (is_array($prefs) && count($prefs) > 0);
      if (!$hasPrefs):
      ?>
        <p class="muted">No preferences saved yet.</p>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Type</th>
              <th>Details</th>
              <th>Created</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $i = 1;
          for ($x = 0; $x < count($prefs); $x++):
              $p = $prefs[$x];

              $prefId = 0;
              if (isset($p['preference_id'])) {
                  $prefId = (int)$p['preference_id'];
              }

              $typeRaw = '';
              if (isset($p['preference_type'])) {
                  $typeRaw = $p['preference_type'];
              }
              $typeEsc = htmlspecialchars($typeRaw);

              $detailsRaw = '';
              if (isset($p['details'])) {
                  $detailsRaw = $p['details'];
              }
              $detailsEsc = htmlspecialchars($detailsRaw);

        // mealpreferences has no created_at column in provided schema
        $createdEsc = '';
          ?>
            <tr>
              <td><?php echo $i; $i++; ?></td>
              <td><?php echo $typeEsc; ?></td>
              <td><?php echo $detailsEsc; ?></td>
              <td><?php echo $createdEsc; ?></td>
              
              <td style="display:flex; gap:8px; flex-wrap:wrap;">
                <a
                  class="btn sm"
                  href="javascript:void(0)"
                  onclick="editPref(<?php echo $prefId; ?>,'<?php echo $typeEsc; ?>','<?php echo $detailsEsc; ?>')"
                >
                  Edit
                </a>
                <a
                  class="btn danger sm"
                  href="/SmartCafe/controller/customer/preferenceController.php?action=delete&id=<?php echo $prefId; ?>"
                  onclick="return confirm('Delete this preference?');"
                >
                  Delete
                </a>
              </td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <div id="editBox" class="card" style="padding:16px;display:none;">
      <h3>Edit Preference</h3>
      <form method="post" action="/SmartCafe/controller/customer/preferenceController.php">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="pref_id" id="edit_id">
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
          <input
            type="text"
            id="edit_type"
            name="preference_type"
            placeholder="Type"
            required
            style="padding:10px;border:1px solid #ccc;border-radius:10px;min-width:240px;"
          >
          <input
            type="text"
            id="edit_details"
            name="details"
            placeholder="Details"
            style="padding:10px;border:1px solid #ccc;border-radius:10px;min-width:280px;"
          >
          <button class="btn" type="submit">Update</button>
          <button class="btn outline" type="button" onclick="cancelEdit()">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</main>

<script>
function editPref(id, type, details){
  document.getElementById('editBox').style.display = 'block';
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_type').value = type;
  document.getElementById('edit_details').value = details;
  window.scrollTo({ top: document.getElementById('editBox').offsetTop - 50, behavior: 'smooth' });
}
function cancelEdit(){
  document.getElementById('editBox').style.display = 'none';
}
</script>

</body>
</html>
