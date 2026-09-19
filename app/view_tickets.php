<?php

include 'db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View Tickets</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
    background:#0b1020;
    color:white;
    font-family:'Poppins', sans-serif;
}

.container{
    margin-top:50px;
    margin-bottom:50px;
}

.card{
    background:#13161f;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.page-title{
    font-size:32px;
    font-weight:600;
    margin-bottom:25px;
}

.table{
    margin-top:20px;
    border-radius:10px;
    overflow:hidden;
}

.table th{
    background:#323EDD !important;
    color:white;
    border:none;
    text-align:center;
}

.table td{
    vertical-align:middle;
    text-align:center;
}

.priority-low{
    background:#28a745;
    padding:6px 15px;
    border-radius:20px;
    color:white;
    font-size:14px;
}

.priority-medium{
    background:#ffc107;
    padding:6px 15px;
    border-radius:20px;
    color:black;
    font-size:14px;
}

.priority-high{
    background:#dc3545;
    padding:6px 15px;
    border-radius:20px;
    color:white;
    font-size:14px;
}

.top-buttons{
    display:flex;
    gap:15px;
    margin-bottom:25px;
}

.home-btn{
    background:#323EDD;
    color:white;
    text-decoration:none;
    padding:10px 20px;
    border-radius:10px;
}

.home-btn:hover{
    background:#4c57f0;
    color:white;
}

.logout-btn{
    background:#dc3545;
    color:white;
    text-decoration:none;
    padding:10px 20px;
    border-radius:10px;
}

.logout-btn:hover{
    background:#ff4d63;
    color:white;
}

.no-tickets{
    background:#1d2130;
    padding:20px;
    border-radius:10px;
    text-align:center;
    margin-top:20px;
}

</style>

</head>

<body>

<div class="container">

    <div class="card">

        <div class="top-buttons">

            <a href="Ticket Submission.php" class="home-btn">
                Back
            </a>

            <a href="Logout.php" class="logout-btn">
                Logout
            </a>

        </div>

        <h1 class="page-title">
            Submitted Tickets
        </h1>

        <table class="table table-dark table-bordered table-hover">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Issue Title</th>
                    <th>Description</th>
                    <th>SAP Number</th>
                    <th>Outlet</th>
                    <th>Priority</th>

                </tr>

            </thead>

            <tbody>

<?php

$sql = "SELECT * FROM software_issue_tickets ORDER BY ticket_id DESC";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

        $priority = strtolower($row['priority']);

        $priorityClass = "priority-low";

        if($priority == "medium"){
            $priorityClass = "priority-medium";
        }

        if($priority == "high" || $priority == "critical"){
            $priorityClass = "priority-high";
        }

?>

<tr>

<td><?php echo $row['ticket_id']; ?></td>

<td><?php echo $row['issue_title']; ?></td>

<td><?php echo $row['issue_description']; ?></td>

<td><?php echo $row['sap_number']; ?></td>

<td><?php echo $row['outlet_location']; ?></td>

<td>

    <span class="<?php echo $priorityClass; ?>">

        <?php echo $row['priority']; ?>

    </span>

</td>

</tr>

<?php

    }

}else{

?>

<tr>

<td colspan="6">

    <div class="no-tickets">
        No submitted tickets found.
    </div>

</td>

</tr>

<?php
}
?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>