<?php
require_once "includes/db.php";
require_once "includes/header.php";
$stmt = $conn->prepare("SELECT * FROM library_items WHERE type='movie' ORDER BY created_at DESC LIMIT 4");
$stmt->execute();
$movies = $stmt->get_result();

$stmt = $conn->prepare("SELECT * FROM library_items WHERE type='tvshow' ORDER BY created_at DESC LIMIT 4");
$stmt->execute();
$tvshows = $stmt->get_result();
?>

<div class="container">
    <h1>Latest Movies</h1>

    <div class="grid">
        <?php if ($movies->num_rows === 0): ?>
            <p>Nu există filme în baza de date.</p>
        <?php else: ?>
            <?php while ($item = $movies->fetch_assoc()): ?>
                <div class="card">
                    <a href="single.php?id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <a class="see-more" href="archive.php?type=movie">See more movies</a>

    <h1>Latest TV Shows</h1>

    <div class="grid">
        <?php if ($tvshows->num_rows === 0): ?>
            <p>Nu există seriale în baza de date.</p>
        <?php else: ?>
            <?php while ($item = $tvshows->fetch_assoc()): ?>
                <div class="card">
                    <a href="single.php?id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <a class="see-more" href="archive.php?type=tvshow">See more TV Shows</a>
</div>

<?php require_once "includes/footer.php"; ?>
