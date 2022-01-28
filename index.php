<?php 

    session_start();
    require_once "config/db.php";


    // if (isset($_POST['submit'])) {
    //     $firstname = $_POST['firstname'];
    //     $lastname = $_POST['lastname'];
    //     $position = $_POST['position'];
    //     $img = $_FILES['img'];

    //     $allow = array('jpg', 'jpeg', 'png');
    //     $extension = explode(".", $img['name']);
    //     $fileActExt = strtolower(end($extension));
    //     $fileNew = rand() . "." . $fileActExt;
    //     $filePath = "uploads/".$fileNew;

    //     if (in_array($fileActExt, $allow)) {
    //         if ($img['size'] > 0 && $img['error'] == 0) {
    //             if (move_uploaded_file($img['tmp_name'], $filePath)) {
    //                 $sql = $conn->prepare("INSERT INTO users(firstname, lastname, position, img) VALUES(:firstname, :lastname, :position, :img)");
    //                 $sql->bindParam(":firstname", $firstname);
    //                 $sql->bindParam(":lastname", $lastname);
    //                 $sql->bindParam(":position", $position);
    //                 $sql->bindParam(":img", $fileNew);
    //                 $sql->execute();

    //                 if ($sql) {
    //                     $_SESSION['success'] = "Data has been inserted succesfully";
    //                 } else {
    //                     $_SESSION['error'] = "Data has not been inserted succesfully";
    //                 }
    //             }
    //         }
    //     }
    // }

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");
        $deletestmt->execute();
        
        if ($deletestmt) {
            echo "<script>alert('Data has been deleted successfully');</script>";
            $_SESSION['success'] = "Data has been deleted succesfully";
            header("refresh:1; url=index.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PDO & Bootstrap 5</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="insert.php" id="formData" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="firstname" class="col-form-label">First Name:</label>
                <input type="text" required class="form-control" name="firstname">
            </div>
            <div class="mb-3">
                <label for="lastname" class="col-form-label">Last Name:</label>
                <input type="text" required class="form-control" name="lastname">
            </div>
            <div class="mb-3">
                <label for="position" class="col-form-label">Position:</label>
                <input type="text" required class="form-control" name="position">
            </div>
            <div class="mb-3">
                <label for="img" class="col-form-label">Image:</label>
                <input type="file" required class="form-control" id="imgInput" name="img">
                <img width="100%" id="previewImg" alt="">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="submit" name="submit" class="btn btn-success">Submit</button>
            </div>
            </form>
        </div>
        
        </div>
    </div>
    </div>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>CRUD Bootstrap</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">Add User</button>
            </div>
        </div>
        <hr>
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <!-- Users Data -->
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Firstname</th>
                <th scope="col">Lastname</th>
                <th scope="col">Position</th>
                <th scope="col">Img</th>
                <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $stmt = $conn->query("SELECT * FROM users");
                    $stmt->execute();
                    $users = $stmt->fetchAll();

                    if (!$users) {
                        echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
                    } else {
                        foreach ($users as $user) {
                ?>
                <tr>
                    <th scope="row"><?= $user['id']; ?></th>
                    <td><?= $user['firstname']; ?></td>
                    <td><?= $user['lastname']; ?></td>
                    <td><?= $user['position']; ?></td>
                    <td width="250px"><img width="100%" src="uploads/<?= $user['img']; ?>" class="rounded" alt=""></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-warning">Edit</a>
                        <a data-id="<?= $user['id']; ?>" href="?delete=<?= $user['id']; ?>" class="btn btn-danger delete-btn">Delete</a>
                    </td>
                </tr>
                <?php } 
                    } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        }

        $(".delete-btn").click(function(e) {
            var userId = $(this).data('id');
            e.preventDefault();
            deleteConfirm(userId);
        })

        function deleteConfirm(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "It will be deleted permanently!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                                url: 'index.php',
                                type: 'GET',
                                data: 'delete=' + userId,
                            })
                            .done(function() {
                                Swal.fire({
                                    title: 'success',
                                    text: 'Data deleted successfully!',
                                    icon: 'success',
                                }).then(() => {
                                    document.location.href = 'index.php';
                                })
                            })
                            .fail(function() {
                                Swal.fire('Oops...', 'Something went wrong with ajax !', 'error')
                                window.location.reload();
                            });
                    });
                },
            });
        }
    </script>

</body>
</html>
