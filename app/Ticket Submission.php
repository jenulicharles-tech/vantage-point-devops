<?php

include 'db_connect.php';

if(isset($_POST['submit_ticket'])){

    $issue_title = $_POST['issue_title'];
    $issue_description = $_POST['issue_description'];
    $sap_number = $_POST['sap_number'];
    $outlet_location = $_POST['outlet_location'];
    $priority = $_POST['priority'];

    $sql = "INSERT INTO software_issue_tickets
    (
        issue_title,
        issue_description,
        sap_number,
        outlet_location,
        priority
    )

    VALUES
    (
        '$issue_title',
        '$issue_description',
        '$sap_number',
        '$outlet_location',
        '$priority'
    )";

    if(mysqli_query($conn, $sql)){

        header("Location: view_tickets.php");
        exit();

    } else {

        echo "Database Error";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Submit Ticket</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
    font-family:'Poppins', sans-serif;
    background:#0b1020;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    margin:0;
    padding:20px;
}

.ticket-container{
    width:100%;
    max-width:900px;
    background:#13161f;
    border-radius:16px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
    color:white;
}

.ticket-title{
    font-size:32px;
    font-weight:600;
}

.ticket-subtitle{
    color:#b8b8c7;
    margin-bottom:30px;
}

.ticket-form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    margin-bottom:8px;
}

.form-group input,
.form-group textarea,
.form-group select{
    background:#1d2130;
    border:1px solid rgba(126, 20, 20, 0.08);
    border-radius:10px;
    padding:14px;
    color:white;
    font-size:15px;
}

.form-group textarea{
    resize:none;
    height:120px;
}

.full-width{
    grid-column:1 / -1;
}

.submit-btn{
    background:#323EDD;
    border:none;
    padding:15px;
    border-radius:10px;
    color:white;
    cursor:pointer;
}

.submit-btn:hover{
    background:#4c57f0;
}

.view-btn{
    background:#28a745;
    color:white;
    text-decoration:none;
    padding:12px 20px;
    border-radius:10px;
    display:inline-block;
    margin-bottom:25px;
}

</style>

</head>

<body>

<div class="ticket-container">

    <a href="view_tickets.php" class="view-btn">
        View Submitted Tickets
    </a>

    <h1 class="ticket-title">
        Submit a Ticket
    </h1>

    <p class="ticket-subtitle">
        Create and manage IT support requests efficiently.
    </p>

    <form class="ticket-form" method="POST">

        <div class="form-group">

            <label>Issue Title</label>

            <input
            type="text"
            name="issue_title"
            placeholder="Enter Issue Title"
            required>

        </div>

        <div class="form-group">

            <label>SAP Number</label>

            <input
            type="text"
            name="sap_number"
            placeholder="Enter SAP Number"
            required>

        </div>

        <div class="form-group full-width">

            <label>Issue Description</label>

            <textarea
            name="issue_description"
            placeholder="Describe the issue"
            required></textarea>

        </div>

        <div class="form-group">

            <label>Outlet Location</label>

            <input
            type="text"
            name="outlet_location"
            placeholder="Enter Outlet Location"
            required>

        </div>

        <div class="form-group">

            <label>Priority</label>

            <select name="priority">

                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
                <option>Critical</option>

            </select>

        </div>

        <div class="form-group full-width">

            <button
            type="submit"
            name="submit_ticket"
            class="submit-btn">

                Submit Ticket

            </button>

        </div>

    </form>

</div>

</body>
</html>