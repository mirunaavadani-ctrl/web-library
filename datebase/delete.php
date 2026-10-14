<?php
require_once "includes/db.php";

$id = $_GET['id'] ?? 0;
if (!is_numeric($id)) {
    die("ID invalid.");
}
$stmt = $conn->prepare("SELECT type, image_path FROM library_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Elementul nu există.");
}

$type = $item['type'];
$image_path = $item['image_path'];

if ($image_path && str_starts_with($image_path, "uploads/")) {
    @unlink($image_path);
}
$stmt = $conn->prepare("DELETE FROM library_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: archive.php?type=$type&deleted=1");
exit;
