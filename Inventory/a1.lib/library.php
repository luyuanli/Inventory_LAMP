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

//Validate the item name
function nameValidation(){
  $name = trim($_POST['name']);
  $dataValid = preg_match("/^[a-zA-z0-9:;\-',][a-zA-z0-9:;\-', ]*$/", $name);
  return $dataValid;
}

//Validate the description
function descValidation(){
  $desc = trim($_POST['description']);
  $dataValid = preg_match("/^[a-zA-Z0-9.,'\-]([a-zA-Z0-9.,'\-\n ]|\r\n)*$/", $desc);
  return $dataValid;
}

//Validate the supply code
function supplyValidation(){
  $supply = trim($_POST['supplyCode']);
  $dataValid = preg_match("/^[a-zA-Z0-9\-]([a-zA-Z0-9\- ])*$/", $supply);
  return $dataValid;
}

//Validate the cost
function costValidtion(){
  $cost = trim($_POST['cost']);
  $dataValid = preg_match("/^[0-9]+\.[0-9]{2}$/", $cost);
  return $dataValid;
}

//Validate the price
function priceValidation(){
  $price = trim($_POST['price']);
  $dataValid = preg_match("/^[0-9]+\.[0-9]{2}$/", $price);
  return $dataValid;
}

//Validate the onhand
function onhandValidation(){
  $numStore = trim($_POST['numStore']);
  $dataValid = preg_match("/^[0-9]+$/", $numStore);
  return $dataValid;
}

//Validate the onhand
function reorderPointValidation(){
  $reorderPoint = trim($_POST['reorderPoint']);
  $dataValid = preg_match("/^[0-9]+$/", $reorderPoint);
  return $dataValid;
}

//validate the search term
function searchtermValidation($term){
  $term = trim($term);
  $dataValid = preg_match("/^[0-9a-zA-Z]+$/", $term);
  return $dataValid;  
}

//validate the user name
function usernameValidation($term){
  $term = trim($term);
  $dataValid = preg_match("/^[0-9a-zA-Z_\.@]+$/", $term);
  return $dataValid;  
}

//the class used to connect to database
class DBlink{
  private $link; //link identitier
  
  //establish a link
  public function __construct(){
    $lines = file('/home/int322_143a07/secret/topsecret');
    $dbserver = trim($lines[0]);
    $uid = trim($lines[1]);
    $pw = trim($lines[2]);
    $dbname = trim($lines[3]);
    $link = mysqli_connect($dbserver, $uid, $pw, $dbname) or die('Could not connect: ' . mysqli_error($link));
    $this->link = $link;
  }
  //conduct SQL query
  public function query($sql_query){
    $result = mysqli_query($this->link, $sql_query) or die('query failed'. mysqli_error($this->link));
    $this->last_result = $result;
    return $result;
  }
  //close a link
  public function __desctruct(){
    mysqli_close($this->link);
  }
  //get link identitier
  public function getLink(){
    return $this->link;
  }
}
?>