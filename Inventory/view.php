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

include "a1.lib/library.php";

//Determine if user has log in by checking session
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['role']))
  header('Location: login.php');

$searchValid = false;
$db = new DBlink();

//if user search some thing
if($_POST && isset($_POST['search']) && $_POST['search'] != ""){
  if(searchtermValidation($_POST['search'])){ // if the keyword entered is valid
    $searchValid = true;
    $search = strtoupper($_POST['search']);
    $_SESSION["search"] = $search; //used for sord records just searched by the search key word
    $search = mysqli_real_escape_string($db->getLink(), $search); //prevent SQL injection
    $sql_query = "SELECT * from inventory WHERE UPPER(description) LIKE '%". $search. "%'";
    if(isset($_COOKIE['sorted']))
      $sql_query .= " order by ". $_COOKIE['sorted'];
    $result = $db->query($sql_query);
  }
}
//Clike on one column header
else if($_GET){
  $searchValid = true;
  setcookie("sorted", $_GET['sorted'], time() + 60 * 60 * 24 * 30); //set up cookie of sorted column so that next time the records are still sorted by this specific column 
  if(isset($_SESSION['search']) && $_SESSION['search'] != "") //if some records have been searched out
    $sql_query = "SELECT * from inventory WHERE UPPER(description) LIKE '%". $_SESSION["search"]. "%' order by ". $_GET['sorted'];
  else
    $sql_query = "SELECT * from inventory order by ". $_GET['sorted'];
  $result = $db->query($sql_query);
}
//user clink view menu or search term is empty
else{
  $searchValid = true;
  $_SESSION["search"] = "";
  $sql_query = "SELECT * from inventory";
  if(isset($_COOKIE['sorted']))
    $sql_query .= " order by ". $_COOKIE['sorted'];
  $result = $db->query($sql_query);
}

?>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>View items</title>
    <link rel="stylesheet" media="screen" href="a1.lib/sitecss.css" type="text/css" />
  </head>
  <body>
    <p class="companyName">Luyuan Li's Commputer Components</p>
    <p>User: <?php echo $_SESSION['username']; ?>, Role: <?php echo $_SESSION['role']; ?> &nbsp;&nbsp;<a href="logout.php">Logout</a></p>
    <nav>
      <ul>
        <li><a href="add.php">Add</a></li>
	<li><a href="view.php">View All</a></li>
      </ul>
    </nav>
    <p>
      <form class="search" action="view.php" method="post">
        Search in description: <input name = "search" value="<?php if(isset($_POST['search'])) echo htmlentities($_POST['search']); ?>"/> <input type="submit" value="Search" />
      </form> 
    </p>
    <?php
	//if no record has been matched
    if(($searchValid && mysqli_num_rows($result) < 1) || !$searchValid){
    ?>
    <p class="message">The search term is illegal or no record matches your search.</p>
    <?php
    }
    else {
    ?>
    <table class="showTable">
      <tr>
	  <!--column header -->
        <th class="title"><a href="view.php?sorted=id">ID</a></th>
        <th class="title"><a href="view.php?sorted=itemName">Item Name</a></th>
        <th class="title"><a href="view.php?sorted=description">Description</a></th>
        <th class="title"><a href="view.php?sorted=supplierCode">supplierCode</a></th>
        <th class="title"><a href="view.php?sorted=cost">Cost</a></th>
        <th class="title"><a href="view.php?sorted=price">Price</a></th>
        <th class="title"><a href="view.php?sorted=onHand">Number On Hand</a></th>
        <th class="title"><a href="view.php?sorted=reorderPoint">Reorder Level</a></th>
        <th class="title"><a href="view.php?sorted=backOrder">On Back order?</a></th>
        <th class="title"><a href="view.php?sorted=deleted">Delete/Restore</a></th>
      </tr>
      <?php
        while($row = mysqli_fetch_assoc($result)){
      ?>
      <tr>
	<td class="field"><a href="add.php?id=<?php echo $row['id'];?>"><?php print $row['id']; ?></a></td> <!-- use id column to update record -->
	<td class="field"><?php print $row['itemName']; ?></td>
	<td class="field"><?php print $row['description']; ?></td>
	<td class="field"><?php print $row['supplierCode']; ?></td>
	<td class="field"><?php print $row['cost']; ?></td>
	<td class="field"><?php print $row['price']; ?></td>
        <td class="field"><?php print $row['onHand']; ?></td>
	<td class="field"><?php print $row['reorderPoint']; ?></td>
        <td class="field"><?php print $row['backOrder']; ?></td>
	<td class="field"><a class="dynamicLink" href="delete.php?id=<?php echo $row['id'] ?>&deleted=<?php echo $row['deleted']; ?>"><?php if($row['deleted'] == 'n') echo "Delete"; else echo "Restore"; ?></a></td>
      </tr>
      <?php
	}
      }     
      ?>
    </table>
    <p class="appendix">Copyright @ 2014 Luyuan Li</p>
  </body>
</html>