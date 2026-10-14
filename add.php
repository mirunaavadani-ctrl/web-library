<?php
require_once "includes/db.php";
require_once "includes/header.php";
$errors = [];
$type = $_POST['type'] ?? '';
$title = $_POST['title'] ?? '';
$rating = $_POST['rating'] ?? '';
$year_start = $_POST['year_start'] ?? '';
$year_end = $_POST['year_end'] ?? '';
$creator = $_POST['creator'] ?? '';
$stars = $_POST['stars'] ?? '';
$description = $_POST['description'] ?? '';
$image_path = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$title) {
        $errors[] = "Titlul este obligatoriu.";
    }

    if ($rating !== '' && (!is_numeric($rating) || $rating < 0 || $rating > 10)) {
        $errors[] = "Rating-ul trebuie să fie un număr între 0 și 10.";
    }

    if ($year_start !== '' && ($year_start < 1800 || $year_start > 2100)) {
        $errors[] = "Anul de început trebuie să fie între 1800 și 2100.";
    }

    if ($year_end !== '' && ($year_end < 1800 || $year_end > 2100)) {
        $errors[] = "Anul de final trebuie să fie între 1800 și 2100.";
    }

    if ($year_start !== '' && $year_end !== '' && $year_end < $year_start) {
        $errors[] = "Anul de final trebuie să fie mai mare sau egal cu anul de început.";
    }

    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Imaginea trebuie să fie jpg, jpeg, png sau webp.";
        }

        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $errors[] = "Imaginea nu poate depăși 5MB.";
        }

        if (empty($errors)) {
            $new_name = uniqid() . "." . $file_ext;
            $upload_path = "uploads/" . $new_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = $upload_path;
            } else {
                $errors[] = "Eroare la încărcarea imaginii.";
            }
        }
    } 

    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO library_items 
            (type, title, rating, year_start, year_end, creator, stars, description, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssdiissss",
            $type,
            $title,
            $rating,
            $year_start,
            $year_end,
            $creator,
            $stars,
            $description,
            $image_path
        );

        if ($stmt->execute()) {
            header("Location: archive.php?type=$type&success=1");
            exit;
        } else {
            $errors[] = "Eroare la inserare în baza de date.";
        }
    }
}
?>

<div class="container">
    <h1>Add New</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Type *</label>
        <select name="type" required>
            <option value="movie" <?= $type === 'movie' ? 'selected' : '' ?>>Movie</option>
            <option value="tvshow" <?= $type === 'tvshow' ? 'selected' : '' ?>>TV Show</option>
        </select>

        <label>Title *</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($title) ?>">

        <label>Rating (0–10)</label>
        <input type="number" step="0.1" name="rating" value="<?= htmlspecialchars($rating) ?>">

        <label>Year Start</label>
        <input type="number" name="year_start" value="<?= htmlspecialchars($year_start) ?>">

        <label>Year End</label>
        <input type="number" name="year_end" value="<?= htmlspecialchars($year_end) ?>">

        <label>Creator</label>
        <input type="text" name="creator" value="<?= htmlspecialchars($creator) ?>">

        <label>Stars</label>
        <input type="text" name="stars" value="<?= htmlspecialchars($stars) ?>">

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea>

        <label>Image (optional)</label>
        <input type="file" name="image">

        <button type="submit">Add</button>
    </form>
</div>

<?php require_once "includes/footer.php"; ?>
