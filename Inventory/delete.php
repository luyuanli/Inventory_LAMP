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
if(!isset($_SESSION['username']) || !isset($_SESSION['role']))
  header('Location: login.php');
$db = new DBlink();

//"delete" or restore record by setting flag
if($_GET['deleted'] == "n")
  $sql_query = "UPDATE inventory set deleted='y' WHERE id = '". $_GET['id']. "';";
else
  $sql_query = "UPDATE inventory set deleted='n' WHERE id = '". $_GET['id']. "';";
  
$db->query($sql_query);

header("Location: view.php");
exit;
?>