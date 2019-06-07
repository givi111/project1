<?php
    session_start();
    if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        // Connect to Database.
        $db = mysqli_connect('localhost', 'root', '', 'homework5');
        // if errors.
        $errors = [];
        // Register user.
        $username = mysqli_real_escape_string($db, $_POST['name']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $age = mysqli_real_escape_string($db, $_POST['age']);
        $password1 = mysqli_real_escape_string($db, $_POST['password']);
        $password2 = mysqli_real_escape_string($db, $_POST['check-password']);
        // Form validation.
        if( $username == '' ) { array_push($errors, 'Username is empty'); }
        if( $email == '' ) { 
            array_push($errors, 'Email is empty');
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, 'Invalid email');
            }
        }
        if( $age == '' ) { array_push($errors, 'age is empty'); }
        if( $password1 == '' ) { array_push($errors, 'password is empty'); }
        if( $password2 == '' ) { array_push($errors, 'password is empty'); }
        if( $password1 != $password2 ) { array_push($errors, 'Passwords do not match'); };
        // if user exists in db.
        $user_check_query = "SELECT * FROM users WHERE name = '$username' or email = '$email'";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        if($user) {
            if( $user['name'] == $username ) { array_push($errors, 'Username already exists'); }
            if( $user['email'] == $email ) { array_push($errors, 'E-mail already exists'); }
        }
        // if no error
        if( count($errors) == 0 ) {
            $password = md5($passowrd1); //Encrypt password md5.
            $query = "INSERT INTO users (name, email, age, password) VALUES ('$username', '$email', '$age', '$password') ";
            mysqli_query($db, $query); // Insert into db.
            $_SESSION['username'] = $username;
            header('location: index.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="name" require />
        <input type="text" name="email" placeholder="e-mail" require />
        <input type="number" name="age" placeholder="age" require />
        <input type="password" name="password" placeholder="password" require />
        <input type="password" name="check-password" placeholder="password again" require />
        <button type="submit">Register</button>
    </form>
</body>
</html>