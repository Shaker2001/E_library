<?php

require_once("../classes/class_addbook.php");
$AddBook = new addbook();
if ((!$AddBook->auto_header_home())||(!$AddBook->check_admin()) )
{
    header("location: ../index.php");
}



if (isset($_GET['bookname'])) {
    $bookname = $_GET['bookname'];
} else {
    $bookname = 0;
}

$bookData = ($AddBook->getData("book", "*", "bookname = '$bookname' "))->fetch(PDO::FETCH_ASSOC);
// print_r($bookData);


if (isset($_POST['submit'])) {
    if (!empty($_FILES['bookimage']['name'])  && !empty($_POST['description'])   && !empty($_POST['date'])  && !empty($_POST['price'])  && !empty($_POST['books'])) 
    {
        $image = $AddBook->valid_image($_FILES, $bookData['bookname']);
        if ($image) 
        {
            $bookimage = $AddBook->get_bookimage();
            $AddBook->set_description($_POST['description']);
            $description = $AddBook->get_description();
            $AddBook->set_date($_POST['date']);
            $date = $AddBook->get_date();
            $AddBook->set_price($_POST['price']);
            $price = $AddBook->get_price();
            $AddBook->set_books($_POST['books']);
            $books = $AddBook->get_books();
            $AddBook->updateData("`book`","`bookimage`='$bookimage',`booktopic`='$books',`bookdate`='$date',`bookprice`='$price',`bookdesc`='$description'" ,"`bookname`='{$bookData['bookname']}'");
            $message = "update success";
        } 
        else
        {
            $message = "Cant upload this file";
        }
    }
    else
    {
        $message = "Please fill the empties";                                                     //if any fileds empty

    }
    
    if (isset($message)){echo ($message);die();}
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../lib/style.css">
    <link rel="stylesheet" href="../lib/bootstrap.min.css">
    <link rel="shortcut icon" href="https://i.pinimg.com/originals/49/f7/25/49f725a9f2b62ea80603f3fe51289735.jpg" type="image/x-icon">
    <style>
        .rent {
            width: 500px;
            margin: 100px auto;
            text-align: left;
        }

        .btn-success {
            border: none;
            padding: 10px !important;
        }
    </style>
</head>

<body>
    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark  bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../home.php">E-Book</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../home.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="history.php">Historical books</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="children.php">Children's books</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="magic.php">Science Fiction books</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="education.php">Educational books</a></li>
                        </ul>
                    </li>

                    <?php
                    require_once("../classes/class_user.php");
                    $user = new user();
                    if (($user->check_admin())) {
                        echo ('<li class="nav-item"> <a class="nav-link active" aria-current="page" href="../addbook.php">Add book</a> </li>');
                    }
                    ?>
                </ul>

                <img src="../image_user/<?php echo ($user->get_userhomeimage()); ?>" class="profileimg">
                <form class="d-flex">
                    <button class="btn btn-danger"><a href="../logout.php" style="text-decoration: none; color: white;">Log Out</a></button>
                </form>
            </div>
        </div>
    </nav>

    <div class="rent">
        <h2 class="text-center m-5">Update Book "<?php echo ($bookData['bookname']) ?>"</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Book Description</label>
                <textarea class="form-control" name="description" role="4" cols="5"><?php echo ($bookData['bookdesc']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Book image</label>
                <input type="file" name="bookimage" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Publish Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo ($bookData['bookdate']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" value="<?php echo ($bookData['bookprice']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Select Category</label>
                <select class="form-select" name="books" width=70%>
                    <option selected>Open this select menu</option>
                    <option value="historical">Historical books</option>
                    <option value="children">Children's books</option>
                    <option value="science">Science Fiction books</option>
                    <option value="educational">Educational books</option>
                </select>
            </div>

            <input type="submit" name="submit" value="Add Book" class="btn-success">
        </form>
    </div>

    <script src="lib/bootstrap.bundle.js"></script>
</body>

</html>