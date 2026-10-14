<?php
require_once "includes/db.php";
require_once "includes/header.php";
$type = $_GET['type'] ?? 'movie';
if ($type !== 'movie' && $type !== 'tvshow') {
    $type = 'movie';
}
$success = isset($_GET['success']);
$deleted = isset($_GET['deleted']);

$stmt = $conn->prepare("SELECT * FROM library_items WHERE type = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $type);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h1><?= $type === 'movie' ? 'Movies' : 'TV Shows' ?></h1>

    <?php if ($success): ?>
        <div class="success-box">Element adăugat cu succes!</div>
    <?php endif; ?>

    <?php if ($deleted): ?>
        <div class="success-box">Element șters cu succes!</div>
    <?php endif; ?>

    <div class="grid">
        <?php if ($result->num_rows === 0): ?>
            <p>Nu există elemente în această categorie.</p>
        <?php else: ?>
            <?php while ($item = $result->fetch_assoc()): ?>
                <div class="card">
                    <a href="single.php?id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
