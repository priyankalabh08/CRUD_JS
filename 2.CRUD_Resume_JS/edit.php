<?php
require_once "pdo.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Priyanka Labh edit</title>
</head>
<body>
<div class="container">
<h1>Editing Profile</h1>

<?php

if ( isset($_POST['first_name']) && isset($_POST['last_name']) 
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])) { 
 
     if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 
         || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 | strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = "All values are required";
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    } 
    else {
	     if ( strpos($_POST['email'],'@') === false ) {
	    	$_SESSION['error'] = "Email address must contain @";
	    	header("Location: edit.php?profile_id=".$_POST['profile_id']);
               return;
	    } else {
		
		$sql = "UPDATE Profile SET 
		  profile_id = :pid, first_name = :fn, last_name = :ln , email = :em, headline = :he, summary = :su
		  where profile_id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':pid' => $_POST['profile_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']));
		$_SESSION['success'] = "Profile Updated";
            header("Location: index.php");
            return;
        }
   }
}
               
$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id'])); 
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}    
$a = htmlentities($row['first_name']);
$b = htmlentities($row['last_name']);
$c = htmlentities($row['email']);
$d = htmlentities($row['headline']);
$e = htmlentities($row['summary']);
$profile_id = $row['profile_id']; 
?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" value="<?= $a ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" value="<?= $b ?>"/></p>
<p>Email:
<input type="text" name="email" value="<?= $c ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" value="<?= $d ?>"/></p>
<p>Summary:<br/>
<input type="text" name="summary" value="<?= $e ?>"/></p>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<input type="submit" value="Save">
<a href="index.php">Cancel</a></p>