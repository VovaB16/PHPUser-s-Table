<?php global $dbh; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="/css/icons/bootstrap-icons.min.css">
</head>
<body>

<?php include $_SERVER["DOCUMENT_ROOT"]."/_header.php"; ?>
<?php include $_SERVER["DOCUMENT_ROOT"]."/connection_database.php"; ?>
<?php include $_SERVER["DOCUMENT_ROOT"]."/Delete_modal.php"; ?>
<?php include $_SERVER["DOCUMENT_ROOT"]."/edit_modal.php"; ?>


<div class="container">
    <h1 class="text-center">
        Список користувачів
    </h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">ПІБ</th>
            <th scope="col">Фото</th>
            <th scope="col">Пошта</th>
            <th scope="col">Телефон</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $sql = 'SELECT * FROM users';
        foreach ($dbh->query($sql) as $row) {
            $id = $row["id"];
            $name = $row["name"];
            $image = $row["image"];
            $email = $row["email"];
            $phone = $row["phone"];

            echo "
            <tr>
                <th scope='row'>$id</th>
                <td>$name</td>
                <td>
                <img src='$image' alt='$name' width='150'>
                </td>
                <td>$email</td>
                <td>$phone</td>
                <td>
                    <button type='button' class='btn btn-outline-warning edit-user' data-bs-toggle='modal' data-bs-target='#editModal' data-userid='$id' data-name='$name' data-email='$email' data-phone='$phone'>
                        Редагувати
                    </button>
                    <button type='button' class='btn btn-outline-danger delete-user' data-bs-toggle='modal' data-bs-target='#deleteModal' data-userid='$id'>
                        Видалити
                    </button>
                </td>
            </tr>
            ";
        }
        ?>
        </tbody>
    </table>
</div>



<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var userId = button.getAttribute('data-userid');
            var deleteUserIdInput = document.getElementById('deleteUserId');
            deleteUserIdInput.value = userId;
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        var editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var email = this.getAttribute('data-email');
                var phone = this.getAttribute('data-phone');
                var image = this.getAttribute('data-image');

                document.getElementById('editUserId').value = id;
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = phone;
                document.getElementById('editImage').value = '';

                var modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            });
        });
    });
</script>


    <script src="/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/js/bootstrap.bundle.min.js">
</body>
</html>