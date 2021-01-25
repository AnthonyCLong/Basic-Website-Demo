<?php

session_start();

if(!isset($_SESSION['username']))
{
    $_SESSION['msg'] = "You must log in first to view this page";
    header("location: login.php");
}

if (isset($_GET['logout']))
{
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>

<body>
    <h1>This is the homepage.</h1>
    <?php
    if(isset($_SESSION['success'])): ?>

    <div>
        <h3>
            <?php
            
            echo $_SESSION['success'];
            unset($_SESSION['success']);

            ?>
        </h3>
    </div>
<?php endif ?>

<!-- //if user logs in, print info -->
<?php if(isset($_SESSION['username'])) : ?>
    <h3>Welcome <strong><?php echo $_SESSION['username']; ?></strong></h3>

    <p><a href="index.php?logout='1'">Logout</a></p>

<?php endif ?>

</body>
</html>
