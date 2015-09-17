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
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['role']))
  header('Location: login.php');

//If user is logged in, log it out and forward to login page
unset($_SESSION);
session_destroy();
setcookie("PHPSESSID", "", time() - 61200, "/");
header("Location: login.php");
?>