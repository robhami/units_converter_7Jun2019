<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$error = "";
$units_from='Klbs';
$units_to='Klbs';
$value=0;
$units_cat='Mass';

	#connect and echo if working, NEED to add DIE
	
	$link = mysqli_connect("shareddb-g.hosting.stackcp.net","units-2019-373172b1", "Booboo111", "units-2019-373172b1");
	
	if (mysqli_connect_error()) {	
		$error = "Database error";	
		} else {
			
			$error = "Database working";
		}
	echo $error;
	
	#set variables from POSTs add default values if not set
	if(isset($_POST["units_cat"])){
		$units_cat=($_POST["units_cat"]);
		}else
		{
			$_POST["units_cat"]="Mass";
		
		}
	
	if(isset($_POST["units_from"])){
		$units_from=mysqli_real_escape_string($link,$_POST["units_from"]);
		}else
		{
			$_POST["units_from"]="Klbs";
		
		}
	if(isset($_POST["units_to"])){
		$units_to=mysqli_real_escape_string($link,$_POST["units_to"]);
		}else
		{
			$_POST["units_to"]="Klbs";
		}
	if(isset($_POST["value"])){
		$value=mysqli_real_escape_string($link,(int)$_POST["value"]);
		}else
		{
			$_POST["value"]=0;	
		}
		
		
	# debug output to show units from and to
	echo "<br>";
	print_r($units_cat);
	echo "<br>";
	print_r($units_from);
	echo "<br>";
	if(is_string($units_cat)) {
		
		echo "is stringx";
	} else {
		
		echo "is not string";
	}
	
	echo "<br>";
	print_r($units_to);
	echo "<br>";
	echo ($link->error);
	
	
	
	
	
	# select table row from SQL that matches '$units_cat' and '$units_from' selected in dropdown
	#$query=("SELECT * FROM ".($units_cat)." WHERE from_value ='".($units_from)."'");
	# can't prepare and bind from table, need to white-list tables
	$query= $link->prepare("SELECT * FROM ".mysqli_real_escape_string($link, $units_cat)." WHERE from_value = ?");
	$query->bind_param("s", $units_from);
	$query->execute();
	echo "<br>";
	# if query works assign array to $row variable, can't use mysqli_query with prepared statement
	if ($result = $query->get_result()) {
		echo nl2br (" \n ARRAY FOR CONVERSION FACTORS");
		$row = mysqli_fetch_array($result);
		
	# debug output to show array selected, if query worked, conversion factor
		echo nl2br (" \n Query was successful \n");
		print_r($row);
		echo "<br>";
		
	} else {	
		echo nl2br(" \n Query failed");
	}
	
	# Describe table for selected units, to get column values
	$query2=("DESCRIBE ".($units_cat));
	
	
	if ($result2 = mysqli_query($link, $query2)) {
		$table_desc = mysqli_fetch_all($result2);
		$rowcount=mysqli_num_rows ($result2);

	#debug to check describe table query worked and show output	
		echo nl2br (" \n TABLE DESCRIPTION");
		echo nl2br (" \n Query was successful \n");
		
		print_r($table_desc);
		echo "<br>";
		
		echo nl2br (" \n TABLE DESCRIPTION [2][0]");
		print_R ($table_desc[2][0]);
		echo "<br>";
		print_r($rowcount);
		echo "<br>";
		
		
	#Create an array of column values	
		foreach($table_desc as $val) {	
			$col[]=$val[0];	
		}
			
	#debug to check create column array worked and show output		
	echo "<br>";
	echo "COLUMNS ARRAY";
	echo "<br>";
	print_r($col);	
	echo "<br>";	
	print_r($col[2]);		
		
	
	# debug output to show array selected, if query worked, conversion factor
			
	} else {	
		echo nl2br(" \n Query failed");
	}
	
	#work out blank rows, need to understand how preg_match works from stackoverflow: https://stackoverflow.com/questions/7638660/get-count-of-substring-occurrence-in-an-array
	$matches = count(preg_grep ("/^Blank/", $col));
	
	echo nl2br (" \n BLANK MATCHES");
	print_R ($matches);
	echo "<br>";
	echo nl2br (" \n DROPDOWN LENGTH = ");
	$dropdown_length = ($rowcount-$matches-2);
	print_r ($dropdown_length);
	
	# select conversion factor from called array 
	$conversion=$row["$units_to"];
	
	# debug output to show conversion factor
	echo "<br>";
	echo "<br>Conversion factor: ";
	print_r ($conversion);
	echo "<br>";
	
	# debug output to show POST values for value, units_from, units_to
	if(isset($_POST["value"])){
		echo  (" \n Units value is: ".$_POST["value"]);
		echo nl2br(" \n Units FROM is: ".$_POST["units_from"]);  
		echo nl2br(" \n Units TO is: ".$_POST["units_to"]); 
	}
	echo "<br>";
	
	# debug output to show conversion factor with units from and to
	echo ("1 " .$row[1]." equals ".$row[$units_to]." ".$units_to);
	echo "<br>";
	
	# get answer
	$answer = ($value * $conversion);
	# debug answer
	echo($answer);
	
	
	

?>



<!DOCTYPE html>
<html lang="en">
  <head>
  
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="norton-safeweb-site-verification" content="letrdxujkunglh0t7k65mmnivdbycwh7zq49hxtnzhurkca81jybivhr72fdsqlgecpi4busrjjlosrwc80ealajcc8en-hjavdovln7i9rkg39-0miefuviosm7prtk" />
    <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<!-- Jquery from https://developers.google.com/speed/libraries/#ext-core -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script><title>Units Converter</title>
  
  </head>

<style>
</style>

<body>

<script type ="text/javascript">

	if (typeof jQuery == "undefined") {
		
		alert ("jQuery is not installed");
		
	} else {
		
		alert ("jQuery is installed correctly")
	}
</script>
<!-- need to look at PHP security issues !-->
<form method="POST" onchange="this.form.submit()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] );?>">
	
	Units category: 
	<select name="units_cat" onchange="this.form.submit()">
	<!--php is there to make selected DD stick not sure how it works from stack overflow- need to look at it!-->	
		<option value="Mass"<?php if($_POST['units_cat']=='Mass') echo 'selected="selected"';?>>Mass</option>
		<option value="Flow"<?php if($_POST['units_cat']=='Flow') echo 'selected="selected"';?>>Flow</option>
		<option value="Pressure"<?php if($_POST['units_cat']=='Pressure') echo 'selected="selected"';?>>Pressure</option>
		<option value="Torque"<?php if($_POST['units_cat']=='Torque') echo 'selected="selected"';?>>Torque</option>
		<option value="Density"<?php if($_POST['units_cat']=='Density') echo 'selected="selected"';?>>Density</option>
		<!--
		<option value="Temperature"<?php if($_POST['units_cat']=='dn') echo 'selected="selected"';?>>Temperature</option>
		
		<option value="Velocity"<?php if($_POST['units_from']=='kn') echo 'selected="selected"';?>>KN</option>!-->
	</select>
	
	<br>
	
	Value from: <input type="number" name="value">
	<br>
	Units from: 
	<select name="units_from" id="u_from" > 
	
		<option value="<?php echo($col[2]) ?>"<?php if($_POST['units_from']==($col[2])) echo 'selected="selected"';?>><?php echo($col[2]) ?></option>
		<option value="<?php echo($col[3]) ?>"<?php if($_POST['units_from']==($col[3])) echo 'selected="selected"';?>><?php echo($col[3]) ?></option>
		<option value="<?php echo($col[4]) ?>"<?php if($_POST['units_from']==($col[4])) echo 'selected="selected"';?>><?php echo($col[4]) ?></option>
		<option value="<?php echo($col[5]) ?>"<?php if($_POST['units_from']==($col[5])) echo 'selected="selected"';?>><?php echo($col[5]) ?></option>
		<option value="<?php echo($col[6]) ?>"<?php if($_POST['units_from']==($col[6])) echo 'selected="selected"';?>><?php echo($col[6]) ?></option>
		<option value="<?php echo($col[7]) ?>"<?php if($_POST['units_from']==($col[7])) echo 'selected="selected"';?>><?php echo($col[7]) ?></option>
		<option value="<?php echo($col[8]) ?>"<?php if($_POST['units_from']==($col[8])) echo 'selected="selected"';?>><?php echo($col[8]) ?></option>
		<option value="<?php echo($col[9]) ?>"<?php if($_POST['units_from']==($col[9])) echo 'selected="selected"';?>><?php echo($col[9]) ?></option>
		
		<!--<option value="klbs"<?php if($_POST['units_from']=='klbs') echo 'selected="selected"';?>>Klbs</option>
		<option value="pound"<?php if($_POST['units_from']=='pound') echo 'selected="selected"';?>>Pound</option>
		<option value="ton"<?php if($_POST['units_from']=='ton') echo 'selected="selected"';?>>Ton</option>
		<option value="kg"<?php if($_POST['units_from']=='kg') echo 'selected="selected"';?>>Kg</option>
		<option value="newton"<?php if($_POST['units_from']=='newton') echo 'selected="selected"';?>>Newton</option>
		<option value="dn"<?php if($_POST['units_from']=='dn') echo 'selected="selected"';?>>DN</option>
		<option value="kn"<?php if($_POST['units_from']=='kn') echo 'selected="selected"';?>>KN</option>
		!-->
	</select>
	
	<br>
	
	
	
	<br>
	Units to: 
	<select name="units_to" id="u_to">
	
		<option value="<?php echo($col[2]) ?>"<?php if($_POST['units_to']==($col[2])) echo 'selected="selected"';?>><?php echo($col[2]) ?></option>
		<option value="<?php echo($col[3]) ?>"<?php if($_POST['units_to']==($col[3])) echo 'selected="selected"';?>><?php echo($col[3]) ?></option>
		<option value="<?php echo($col[4]) ?>"<?php if($_POST['units_to']==($col[4])) echo 'selected="selected"';?>><?php echo($col[4]) ?></option>
		<option value="<?php echo($col[5]) ?>"<?php if($_POST['units_to']==($col[5])) echo 'selected="selected"';?>><?php echo($col[5]) ?></option>
		<option value="<?php echo($col[6]) ?>"<?php if($_POST['units_to']==($col[6])) echo 'selected="selected"';?>><?php echo($col[6]) ?></option>
		<option value="<?php echo($col[7]) ?>"<?php if($_POST['units_to']==($col[7])) echo 'selected="selected"';?>><?php echo($col[7]) ?></option>
		<option value="<?php echo($col[8]) ?>"<?php if($_POST['units_to']==($col[8])) echo 'selected="selected"';?>><?php echo($col[8]) ?></option>
		<option value="<?php echo($col[9]) ?>"<?php if($_POST['units_to']==($col[9])) echo 'selected="selected"';?>><?php echo($col[9]) ?></option>
	
	
	
	
	
	
		<!--<option value="klbs"<?php if($_POST['units_to']=='klbs') echo 'selected="selected"';?>>Klbs</option>
		<option value="pound"<?php if($_POST['units_to']=='pound') echo 'selected="selected"';?>>Pound</option>
		<option value="ton"<?php if($_POST['units_to']=='ton') echo 'selected="selected"';?>>Ton</option>
		<option value="kg"<?php if($_POST['units_to']=='kg') echo 'selected="selected"';?>>Kg</option>
		<option value="newton"<?php if($_POST['units_to']=='newton') echo 'selected="selected"';?>>Newton</option>
		<option value="dn"<?php if($_POST['units_to']=='dn') echo 'selected="selected"';?>>DN</option>
		<option value="kn"<?php if($_POST['units_to']=='kn') echo 'selected="selected"';?>>KN</option>
		!-->
	</select>
	
	<br>
	
	<output id="output_value">Result = <?php echo($answer.$units_to) ?> </output>
	
	<br>
	
	<input type="submit">
	
</form>

<script>

	$(window).load(function(){
		alert("hello");
		jQuery("#u_from option:contains('Blank')").remove();
		jQuery("#u_to option:contains('Blank')").remove();
	});
	
	

	
	
</script>