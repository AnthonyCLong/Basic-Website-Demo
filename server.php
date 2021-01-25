<?php

session_start();

//initalize vars

$username = "";
$email = "";

$errors = array();

//connect to db

$db = mysqli_connect('localhost', 'root', '', 'php website') or die("could not connect to db");

//resister users

if (isset($_POST['reg_user']))
{
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    //validation
    if(empty($username)) {array_push($errors, "Username is required");}
    if(empty($email)) {array_push($errors, "Email is required");}
    if(empty($password_1)) {array_push($errors, "Password is required");}
    if($password_1 != $password_2) {array_push($errors, "Passwords do not match");}


    //check for existing username

    $user_check_query = "SELECT * FROM user WHERE username = '$username' or email = '$email' LIMIT 1";

    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if($user)
    {
        if($user['username'] === $username) array_push($errors, "Username already exists");
        if($user['email'] === $email) array_push($errors, "Email has already been used to register");
    }

    if(count($errors)== 0)
    {
        $password = password_hash($password_1, PASSWORD_DEFAULT);
        $query = "INSERT INTO user (username, email, password) VALUES ('$username','$email','$password')";

        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";

        header('location: index.php');
    }
}

if(isset ($_POST['login_user']))
{
   $username = mysqli_real_escape_string($db, $_POST['username']);
   $password = mysqli_real_escape_string($db, $_POST['password_1']); 

   if(empty($username))
   {
       array_push($errors, "Username is required");
   }
   if(empty($password))
   {
       array_push($errors, "Password is required");
   }

   if(count($errors)== 0)
   {
        $query = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($db, $query);
        $res = mysqli_fetch_array($result);
        $dbpassword = $res['password'];
        if(password_verify($password, $dbpassword))
        {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Logged in successfully";
            header('location: index.php');
        }
        else
        {
            array_push($errors, "Wrong username or password, please try again.");
        }
   }
}
?>