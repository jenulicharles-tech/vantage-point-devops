<?php

session_start();

$error = "";

/*
DATABASE CONNECTION
*/

$conn = new mysqli(
    getenv('DB_HOST') ?: 'localhost',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASSWORD') ?: '',
    getenv('DB_NAME') ?: 'vantage_point_international'
);

/*
CHECK DATABASE CONNECTION
*/

if($conn->connect_error){

    die("Connection Failed : " . $conn->connect_error);

}

/*
=========================================
ADMIN LOGIN
=========================================
*/

if(isset($_POST['admin_login'])){

    $username = trim($_POST['username']);

    $password = trim($_POST['password']);

    /*
    ADMIN CREDENTIALS
    */

    if(
        $username === "admin"
        &&
        $password === "AdMiN#010"
    ){

        /*
        CREATE ADMIN SESSION
        */

        $_SESSION['admin'] = "admin";

        /*
        REMOVE USER SESSION
        */

        unset($_SESSION['username']);
        unset($_SESSION['full_name']);

        /*
        REDIRECT ADMIN
        */

        header("Location: index.html");
        exit();

    } else {

        $error = "Invalid Admin Username or Password";

    }

}

/*
=========================================
USER LOGIN
=========================================
*/

if(isset($_POST['user_login'])){

    $username = trim($_POST['username']);

    $password = trim($_POST['password']);

    /*
    REMOVE ADMIN SESSION
    */

    unset($_SESSION['admin']);

    /*
    CHECK USER
    */

    $stmt = $conn->prepare(
        "SELECT * FROM user_information
        WHERE username = ?
        AND password = ?"
    );

    $stmt->bind_param(
        "ss",
        $username,
        $password
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        /*
        CREATE USER SESSION
        */

        $_SESSION['username'] = $row['username'];

        $_SESSION['full_name'] = $row['full_name'];

        /*
        REDIRECT USER
        */

        header("Location: index2.php");
        exit();

    } else {

        $error = "Invalid Username or Password";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Login Portal</title>

<link
href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap"
rel="stylesheet">

<style>

*, *::before, *::after{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

:root{
    --bg:#0c0e14;
    --surface:#13161f;
    --border:rgba(255,255,255,0.08);
    --accent:#323EDD;
    --text:#f0eef8;
    --muted:rgba(240,238,248,0.45);
    --err:#e24b4a;
    --radius:14px;
}

body{
    min-height:100vh;
    background:#0c0e14;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'DM Sans', sans-serif;
    color:var(--text);
    padding:2rem 1rem;
}

.card{
    width:100%;
    max-width:450px;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:2.5rem 2rem;
}

.logo{
    font-family:'DM Serif Display', serif;
    font-size:1.8rem;
    margin-bottom:0.3rem;

    background:linear-gradient(
    135deg,
    #ffffff,
    #323EDD
    );

    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.subtitle{
    color:var(--muted);
    margin-bottom:2rem;
    font-size:0.9rem;
}

.login-title{
    margin-top:1.5rem;
    margin-bottom:1rem;
    font-size:1rem;
    color:#ffffff;
    border-left:4px solid var(--accent);
    padding-left:10px;
}

.field{
    margin-bottom:1rem;
}

.field label{
    display:block;
    margin-bottom:0.4rem;
    font-size:0.8rem;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:1px;
}

.input-wrap input{
    width:100%;
    background:rgba(255,255,255,0.04);
    border:1px solid var(--border);
    border-radius:9px;
    padding:0.75rem 0.9rem;
    font-size:0.9rem;
    color:var(--text);
    outline:none;
}

.input-wrap input:focus{
    border-color:var(--accent);
}

.btn-submit{
    width:100%;
    background:var(--accent);
    border:none;
    border-radius:9px;
    padding:0.8rem;
    color:white;
    font-size:0.9rem;
    cursor:pointer;
    margin-top:0.5rem;
}

.btn-submit:hover{
    opacity:0.9;
}

.error-msg{
    background:rgba(226,75,74,0.12);
    border:1px solid rgba(226,75,74,0.3);
    border-radius:8px;
    color:#f09595;
    font-size:0.82rem;
    padding:0.6rem 0.85rem;
    margin-bottom:1.25rem;
}

.divider{
    height:1px;
    background:var(--border);
    margin:2rem 0;
}

</style>

</head>

<body>

<div class="card">

<div class="logo">

    Login Portal

</div>

<p class="subtitle">

    Sign in to continue

</p>

<?php

if($error != ""){

    echo "<div class='error-msg'>$error</div>";

}

?>

<!-- USER LOGIN -->

<div class="login-title">

    User Login

</div>

<form method="POST">

<input
type="hidden"
name="role"
value="user">

<div class="field">

<label>

    Username

</label>

<div class="input-wrap">

<input
type="text"
name="username"
required>

</div>

</div>

<div class="field">

<label>

    Password

</label>

<div class="input-wrap">

<input
type="password"
name="password"
required>

</div>

</div>

<button
type="submit"
name="user_login"
class="btn-submit">

    User Login

</button>

</form>

<div class="divider"></div>

<!-- ADMIN LOGIN -->

<div class="login-title">

    Admin Login

</div>

<form method="POST">

<input
type="hidden"
name="role"
value="admin">

<div class="field">

<label>

    Admin Username

</label>

<div class="input-wrap">

<input
type="text"
name="username"
required>

</div>

</div>

<div class="field">

<label>

    Admin Password

</label>

<div class="input-wrap">

<input
type="password"
name="password"
required>

</div>

</div>

<button
type="submit"
name="admin_login"
class="btn-submit">

    Admin Login

</button>

</form>

</div>

</body>
</html>