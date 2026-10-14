<?php
require_once "includes/db.php";
require_once "includes/header.php";
$id = $_GET['id'] ?? 0;
if (!is_numeric($id)) {
    die("<p>ID invalid.</p>");
}
$stmt = $conn->prepare("SELECT * FROM library_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    echo "<div class='container'><h2>Elementul nu a fost găsit.</h2></div>";
    require_once "includes/footer.php";
    exit;
}
?>

<div class="container single-page">

    <div class="single-left">
        <?php if ($item['image_path']): ?>
            <img src="<?= htmlspecialchars($item['image_path']) ?>" 
                 alt="<?= htmlspecialchars($item['title']) ?>" 
                 class="single-image">
        <?php endif; ?>
    </div>

    <div class="single-right">
        <h1><?= htmlspecialchars($item['title']) ?></h1>

        <p><strong>Rating:</strong> <?= $item['rating'] ?: 'N/A' ?></p>
        <p><strong>Start Year:</strong> <?= $item['year_start'] ?: 'N/A' ?></p>
        <p><strong>End Year:</strong> <?= $item['year_end'] ?: 'N/A' ?></p>
        <p><strong>Creator:</strong> <?= htmlspecialchars($item['creator']) ?: 'N/A' ?></p>
        <p><strong>Stars:</strong> <?= htmlspecialchars($item['stars']) ?: 'N/A' ?></p>

        <h3>Description</h3>
        <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>

    </div>
</div>
    <a href="archive.php?type=<?= $item['type'] ?>" class="back-btn">Înapoi la listă</a>
    <a href="delete.php?id=<?= $item['id'] ?>" class="delete-btn">Delete</a>

<?php require_once "includes/footer.php"; ?>
