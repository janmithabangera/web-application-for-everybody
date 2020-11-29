<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['email']))
    {
        die('ACCESS DENIED');
    }
	
if(isset($_POST['logout'])){
	header('Location:index.php');
	return;
}

//$make = htmlentities($_POST['make']);
//$year = htmlentities($_POST['year']);
//$mileage = htmlentities($_POST['mileage']);

if (isset($_POST['add'])) {
	unset($_SESSION['make']);
	unset($_SESSION['model']);
	unset($_SESSION['year']);
	unset($_SESSION['mileage']);

	if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
		$_SESSION['error']  = "All values are required";
		header('Location: add.php');
		return;
	}
	elseif (! is_numeric($_POST['year']) || (! is_numeric($_POST['mileage']))) {
		$_SESSION['error'] = "Mileage and year must be numeric";
		header('Location: add.php');
		return;
	}
	else {

		$make = htmlentities($_POST['make']);
		$model = htmlentities($_POST['model']);
		$year = htmlentities($_POST['year']);
		$mileage = htmlentities($_POST['mileage']);

		$_SESSION['make'] = $make;
		$_SESSION['model'] = $model;
		$_SESSION['year'] = $year;
		$_SESSION['mileage'] = $mileage;

		$stmt = $pdo->prepare('INSERT INTO autos
        (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
    	$stmt->execute(array(
        ':mk' => $make,
        ':md' => $model,
        ':yr' => $year,
        ':mi' => $mileage)
    );
    	$_SESSION['success'] = "Record added";
    	header('Location:index.php');
    	return;

	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>janmitha bangera's Automobile Tracker</title>

<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Tracking Automobiles for <?= htmlentities($_SESSION['email']); ?></h1>

<?php
//Flash message
if (isset($_SESSION['error']) ) {
    echo('<p style="color:red">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>

<form method="post" action="add.php">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Model:
<input type="text" name="model" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" name="add" value="Add">
<input type="submit" name="logout" value="Cancel">
</form>


</div>
</body>
</html>