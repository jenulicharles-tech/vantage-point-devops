<?php

session_start();

include 'db_connect.php';

$message = "";

/*
=========================================
SEND MESSAGE
=========================================
*/

if(isset($_POST['send_message'])){

    $name = $_POST['name'];

    $email = $_POST['email'];

    $service = $_POST['service'];

    $user_message = $_POST['user_message'];

    /*
    INSERT MESSAGE
    */

    $sql = "INSERT INTO contact_messages
    (
        name,
        email,
        service,
        message
    )

    VALUES
    (
        '$name',
        '$email',
        '$service',
        '$user_message'
    )";

    if(mysqli_query($conn, $sql)){

        $message = "Message Sent Successfully!";

    } else {

        $message = mysqli_error($conn);

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Contact Us</title>

<style>

body{
    background:#0b1020;
    font-family:Arial;
    color:white;
    padding:50px;
}

.container{
    width:500px;
    margin:auto;
    background:#13161f;
    padding:30px;
    border-radius:10px;
}

h1{
    margin-bottom:20px;
}

.logout-btn{
    background:red;
    color:white;
    padding:10px 20px;
    text-decoration:none;
    border-radius:5px;
    display:inline-block;
    margin-bottom:20px;
}

input,
select,
textarea{
    width:100%;
    padding:15px;
    margin-bottom:15px;
    border:none;
    border-radius:5px;
}

textarea{
    height:120px;
}

button{
    width:100%;
    padding:15px;
    background:#323EDD;
    border:none;
    color:white;
    font-size:18px;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#4c57f0;
}

.success{
    background:green;
    padding:15px;
    margin-bottom:20px;
    border-radius:5px;
}

.error{
    background:red;
    padding:15px;
    margin-bottom:20px;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h1>

Contact Us

</h1>

<!-- LOGOUT BUTTON -->

<a
href="logout.php"
class="logout-btn">

Logout

</a>

<?php

if($message == "Message Sent Successfully!"){

    echo "<div class='success'>$message</div>";

}

elseif($message != ""){

    echo "<div class='error'>$message</div>";

}

?>

<form method="POST">

<input
type="text"
name="name"
placeholder="Your Name"
required>

<input
type="email"
name="email"
placeholder="Your Email"
required>

<select
name="service"
required>

<option value="">

Select Service

</option>

<option>

Software Support

</option>

<option>

Hardware Support

</option>

<option>

POS System

</option>

<option>

Network Support

</option>

</select>

<textarea
name="user_message"
placeholder="Enter Message"
required></textarea>

<button
type="submit"
name="send_message">

Send Message

</button>

</form>

</div>

</body>

</html>