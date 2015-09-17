<?php
/************************************************************************************************************/
/*                                                                                                          */
/* INT322A Luyuan Li Submitted on Nov 30, 2014                                                              */
/*                                                                                                          */
/* Student Declaration                                                                                      */ 
/* I/we declare that the attached assignment is my/our own work in accordance with Seneca Academic          */
/* Policy. No part of this assignment has been copied manually or electronically from any other source      */
/* (including web sites) or distributed to other students.                                                  */
/*                                                                                                          */
/*  Name Luyuan Li                                                                                          */
/*  Student ID 057-841-132                                                                                  */
/*                                                                                                          */
/************************************************************************************************************/

//Determine if user has log in by checking session
include "a1.lib/library.php";
session_start();
if(isset($_SESSION['username']) && isset($_SESSION['role']))
  header('Location: view.php');
$dataValid = false;

if($_POST){
  //prevent XSS attack
  if(isset($_POST['name'])) $_POST['name'] = htmlentities($_POST['name']);
  if(isset($_POST['password'])) $_POST['password'] = htmlentities($_POST['password']);
  
  if(usernameValidation($_POST['name'])){
    $db = new DBlink();
    $_POST['name'] = mysqli_real_escape_string($db->getLink(), $_POST['name']); //prevent SQL injection attack
    $sql_query = "SELECT * from users WHERE username = '". $_POST['name']. "';";
    $result = $db->query($sql_query);
	
	//if the crediential pair match one in database
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_assoc($result);
      if(password_verify($_POST['password'], $row['password'])){
	     $dataValid = true;
		 //set up session variable
	     $_SESSION['username'] = $row['username'];
	     $_SESSION['role'] = $row['role'];
      }
    }
  }
  
  //Once log in, go to view page
  if($dataValid) header('Location: view.php');
}

//The page of retrieving password hint
if($_GET){
?>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Add Item</title>
    <link rel="stylesheet" media="screen" href="a1.lib/sitecss.css" type="text/css" />
  </head>
  <body>
    <p class="companyName">Luyuan Li's Commputer Components</p>
    <p class="login">Enter your email address</p>
    <form method="post" action="login.php">
      <input name="nameCheck"/>
      <br /><br />
      <input type="submit" value="Submit" />
    </form>
  </body>
</html>
<?php
}
//get the password hint and mail to user's email
else if(isset($_POST['nameCheck'])){
  $db = new DBlink();
  $sql_query = "SELECT * FROM users WHERE USERNAME = '". $_POST['nameCheck']. "';";
  $result = $db->query($sql_query);
  //if the email entered exist in database, sent password hint to it; otherwise do nothing
  if(mysqli_num_rows($result) == 1){
    $row = mysqli_fetch_assoc($result);
    $to = "int322_143a07@zenit.senecac.on.ca";
    $subject = "Password Setting";
    $message = "User name :". $row['username']. "\r\n"."Password  :". $row['passwordHint'];
    $headers = "From: Server<int322_143a07@zenit.senecac.on.ca>\r\nReply-to: Client<int322_143a07@zenit.senecac.on.ca>";
    mail($to, $subject, $message, $headers);
  }
  //come to login interface
  header("Location: login.php");
}

//user first go to login page or the crediential entered is incorrect
else if(!$_POST || !$dataValid){
?>
<!DOCTYPE html />
<html>
  <head>
    <meta charset="utf-8" />
    <title>Add Item</title>
    <link rel="stylesheet" media="screen" href="a1.lib/sitecss.css" type="text/css" />
  </head>
  <body>
    <p class="companyName">Luyuan Li's Commputer Components</p>
    <p class="login">Login</p>
    <form method="post" action="login.php">
      User Name : <input name="name" value=""/>
      <br /><br />
      Password &nbsp;&nbsp;&nbsp;: <input name="password" type="password" value=""
      />
      <br /><br />
      <?php
	  //if the crediential entered is incorrect, show error message
      if($_POST){
      ?>
      Either username or password is incorrect.
      <br /><br />
      <?php
      }
      ?>
      <input type="submit" value="Login" />
    </form>
    <p><a href="login.php?pwset=y">Forgot your password?</a></p>
  </body>
</html>
<?php
}
?>