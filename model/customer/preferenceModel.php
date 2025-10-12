<?php
require_once(__DIR__ . "/../dbConnect.php");

function pref_all_by_customer(int $customer_id): array {
  $conn = getConnect();
  $stm = $conn->prepare(
    "SELECT * FROM CustomerPreferences
     WHERE customer_id=? ORDER BY is_favorite DESC, item_name ASC"
  );
  $stm->bind_param("i", $customer_id);
  $stm->execute();
  $rows = $stm->get_result()->fetch_all(MYSQLI_ASSOC);
  $stm->close(); $conn->close();
  return $rows;
}

function pref_get(int $id, int $customer_id): ?array {
  $conn = getConnect();
  $stm = $conn->prepare(
    "SELECT * FROM CustomerPreferences WHERE id=? AND customer_id=?"
  );
  $stm->bind_param("ii", $id, $customer_id);
  $stm->execute();
  $row = $stm->get_result()->fetch_assoc();
  $stm->close(); $conn->close();
  return $row ?: null;
}

function pref_create(int $customer_id, string $item_name, string $spice_level, int $is_favorite, ?string $notes): array {
  $conn = getConnect();
  $stm = $conn->prepare(
    "INSERT INTO CustomerPreferences(customer_id,item_name,spice_level,is_favorite,notes)
     VALUES(?,?,?,?,?)"
  );
  $stm->bind_param("issis", $customer_id, $item_name, $spice_level, $is_favorite, $notes);
  $ok = $stm->execute();
  $err = $stm->error;
  $stm->close(); $conn->close();
  return [$ok, $err];
}

function pref_update(int $id, int $customer_id, string $item_name, string $spice_level, int $is_favorite, ?string $notes): array {
  $conn = getConnect();
  $stm = $conn->prepare(
    "UPDATE CustomerPreferences
     SET item_name=?, spice_level=?, is_favorite=?, notes=?
     WHERE id=? AND customer_id=?"
  );
  $stm->bind_param("ssissi", $item_name, $spice_level, $is_favorite, $notes, $id, $customer_id);
  $ok = $stm->execute();
  $err = $stm->error;
  $stm->close(); $conn->close();
  return [$ok, $err];
}

function pref_delete(int $id, int $customer_id): bool {
  $conn = getConnect();
  $stm = $conn->prepare("DELETE FROM CustomerPreferences WHERE id=? AND customer_id=?");
  $stm->bind_param("ii", $id, $customer_id);
  $ok = $stm->execute();
  $stm->close(); $conn->close();
  return $ok;
}

function pref_toggle_fav(int $id, int $customer_id): bool {
  $conn = getConnect();
  $stm = $conn->prepare(
    "UPDATE CustomerPreferences SET is_favorite = 1 - is_favorite WHERE id=? AND customer_id=?"
  );
  $stm->bind_param("ii", $id, $customer_id);
  $stm->execute();
  $ok = $stm->affected_rows > 0;
  $stm->close(); $conn->close();
  return $ok;
}
