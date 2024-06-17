<?php global $dbh; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php";

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $image = '/uploads/' . basename($_FILES['image']['name']);
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    if ($name && $email && $phone && $image) {

        $stmt = $dbh->prepare("INSERT INTO users (name, email, image, phone) VALUES (:name, :email, :image, :phone)");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':phone', $phone);

        if ($stmt->execute()) {
            header("Location: /");
            exit();
        } else {
            echo "Error inserting record: " . implode(", ", $stmt->errorInfo());
        }
    } else {
        echo "Invalid data provided.";
    }
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Користвувачі</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/site.css">
</head>
<body>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/_header.php"; ?>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php"; ?>

<div class="container">
    <h1 class="text-center">
        Додати користувача
    </h1>
    <div class="row">
        <form class="col-md-6 offset-md-3" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">ПІБ</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Фото</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Пошта</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Телефон</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary me-2">Додати</button>
                <a href="/" class="btn btn-light">Скасувати</a>
            </div>

        </form>
    </div>

</div>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
