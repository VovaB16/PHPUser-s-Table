<?php global $dbh; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php";

    $id = $_POST["id"];

    $stmt = $dbh->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: /");
        exit();
    } else {
        echo "Error deleting record.";
    }
}
?>