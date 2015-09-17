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
  
if($_POST){
  //validate each form
  $nameValid = nameValidation();
  $descValid = descValidation();
  $supplyValid = supplyValidation();
  $costValid = costValidtion();
  $priceValid = priceValidation();
  $onhandValid = onhandValidation();
  $reorderPointValid = reorderPointValidation();
  
  //prevent XSS attack
  $_POST['name'] = htmlentities($_POST['name']);
  $_POST['description'] = htmlentities($_POST['description']);
  $_POST['supplyCode'] = htmlentities($_POST['supplyCode']);
  $_POST['cost'] = htmlentities($_POST['cost']);
  $_POST['price'] = htmlentities($_POST['price']);
  $_POST['numStore'] = htmlentities($_POST['numStore']);
  $_POST['reorderPoint'] = htmlentities($_POST['reorderPoint']);

  if($nameValid && $descValid && $supplyValid && $costValid && $priceValid && $onhandValid && $reorderPointValid){ //if all field are valid
    $db = new DBlink();
    
	//format the field
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $supplyCode = trim($_POST['supplyCode']);
    $cost = trim($_POST['cost']);
    $price = trim($_POST['price']);
    $numStore = trim($_POST['numStore']);
    $reorderPoint = trim($_POST['reorderPoint']);
    $onBackOrder = isset($_POST['onBackOrder']) ? "y": "n";
	
    //prevent SQL injection attack
    $name = mysqli_real_escape_string($db->getLink(), $_POST['name']);
    $description = mysqli_real_escape_string($db->getLink(), $_POST['description']);
    $supplyCode = mysqli_real_escape_string($db->getLink(), $_POST['supplyCode']);
    $cost = mysqli_real_escape_string($db->getLink(), $_POST['cost']);
    $price = mysqli_real_escape_string($db->getLink(), $_POST['price']);
    $numStore = mysqli_real_escape_string($db->getLink(), $_POST['numStore']);
    $reorderPoint = mysqli_real_escape_string($db->getLink(), $_POST['reorderPoint']);
    
    if(isset($_POST['id'])) //For insert data
      $sql_query = 'UPDATE inventory set itemName="'. $name . '", description="'. $description . '", supplierCode="'. $supplyCode . '", cost="'. $cost
        . '", price="'. $price . '", onHand="'. $numStore . '", reorderPoint="'. $reorderPoint . '", backOrder="'. $onBackOrder . '" WHERE id="'. $_POST['id']. '";';
    else //For update data
      $sql_query = 'INSERT INTO inventory VALUES("", "' . $name . '", "' . $description . '", "' . $supplyCode . '", "'
        . $cost . '", "' . $price . '", "' . $numStore . '", "' . $reorderPoint . '", "' . $onBackOrder . '", "n");';
    $result = $db->query($sql_query);
    
	//Jump to view.php to see all the record
    header("Location: view.php");
  }
}

if($_GET){ //For update, use id to receive the data to populate fields
  $db = new DBlink();
  $sql_query = "SELECT * FROM inventory WHERE id='". $_GET['id']. "';";
  $result = $db->query($sql_query);
  $row = mysqli_fetch_assoc($result);
}
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
    <p>User: <?php echo $_SESSION['username']; ?>, Role: <?php echo $_SESSION['role']; ?> &nbsp;&nbsp;<a href="logout.php">Logout</a></p>
    <nav>
      <ul>
	<li><a href="add.php">Add</a></li>
	<li><a href="view.php">View All</a></li>
      </ul>
    </nav>
    <p class="appendix">All field mandatory except: "On Black Order"</p>
    <form method="post" action="add.php">
      <?php
      if($_GET || isset($_POST['id'])){ //For update, show the readonly field of ID
      ?>
      <p><label class="label">ID:</label><input name="id" value="<?php if($_GET) echo $row['id']; else echo $_POST['id'];?>" readonly="readonly" /></p>
      <?php
      }
      ?>
      <p><label class="label">Item Name: </label><input name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; else if($_GET) echo $row['itemName'];?>"/>
        <?php if($_POST && !$nameValid) echo "<br /><span>Item name don't meet the condition. It can include letters, spaces, colon, semi-colon, dash, comma, apostrophe and numeric character only, and cannot be blank</span>"; ?></p>
      <p><label class="label">Description: </label><textarea name="description"><?php if(isset($_POST['description'])) echo $_POST['description']; else if($_GET) echo $row['description']?></textarea>
        <?php if($_POST && !$descValid) echo "<br /><span>Description don't meet the condition. It can include letters, digits, periods, commas, apostrophes, dashes and spaces only, and can have multiple lines, but cannot be blank</span>"; ?></p>
      <p><label class="label">Supplier Code: </label><input name="supplyCode" value="<?php if(isset($_POST['supplyCode'])) echo $_POST['supplyCode']; else if($_GET) echo $row['supplierCode']?>"/>
        <?php if($_POST && !$supplyValid) echo "<br /><span>Supply code don't meet the condition. It can include letters, spaces, numeric characters (0-9) and dashes only, and cannot be blank</span>"; ?></p>
      <p><label class="label">Cost: </label><input name="cost" value="<?php if(isset($_POST['cost'])) echo $_POST['cost']; else if($_GET) echo $row['cost']?>"/>
        <?php if($_POST && !$costValid) echo "<br /><span>Cost don't meet the condition. It can include monetary amounts only i.e. one or more digits, then a period, then two digits, and cannot be blank</span>"; ?></p>	
      <p><label class="label">Selling price: </label><input name="price" value="<?php if(isset($_POST['price'])) echo $_POST['price']; else if($_GET) echo $row['price']?>"/>
        <?php if($_POST && !$priceValid) echo "<br /><span>Selling price don't meet the condition. It can include monetary amounts only i.e. one or more digits, then a period, then two digits, and cannot be blank</span>"; ?></p>	
      <p><label class="label">Number on hand: </label><input name="numStore" value="<?php if(isset($_POST['numStore'])) echo $_POST['numStore']; else if($_GET) echo $row['onHand']?>"/>
        <?php if($_POST && !$onhandValid) echo "<br /><span>Number on hand don't meet the condition. It can include digits only, and cannot be blank</span>"; ?></p>
      <p><label class="label">Reorder Point: </label><input name="reorderPoint" value="<?php if(isset($_POST['reorderPoint'])) echo $_POST['reorderPoint']; else if($_GET) echo $row['reorderPoint']?>"/>
        <?php if($_POST && !$reorderPointValid) echo "<br /><span>Reorder Point don't meet the condition. It can include digits only, and cannot be blank</span>"; ?></p>	
      <p><label class="label">On Back Order:</label><input type="checkbox" name="onBackOrder" class="checkbox" <?php if(isset($_POST['onBackOrder'])) echo "checked='checked'"; else if($_GET && $row['backOrder'] == 'y') echo "checked='checked'"?>/></p>
      <P><input type="submit" value="submit" class="buttom"/></P>
	<input type="hidden" name="isvalid" value="N" />
    </form>
    <p class="appendix">Copyright @ 2014 Luyuan Li</p>
  </body>
</html>
