<?php global $dbh; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php";

    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;

    // Перевірка, чи всі обов'язкові дані передані
    if (!$id || !$name || !$email || !$phone) {
        http_response_code(400);
        echo "Invalid data provided.";
        exit();
    }

    // Зміна зображення, якщо воно відправлене
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
                http_response_code(500);
                echo "Error uploading file.";
                exit();
            }
        } else {
            http_response_code(400);
            echo "File is not an image.";
            exit();
        }
    }

    try {
        // Оновлення користувача в базі даних
        if ($image) {
            $stmt = $dbh->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, image = :image WHERE id = :id");
            $stmt->bindParam(':image', $image);
        } else {
            $stmt = $dbh->prepare("UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id");
        }
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            http_response_code(200);
            header("Location: /");
            exit();
        } else {
            http_response_code(500);
            echo "Error updating record: " . implode(", ", $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Database Error: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Method Not Allowed";
}
?>


