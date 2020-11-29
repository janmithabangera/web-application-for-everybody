<?php
require_once "pdo.php";

$failure=false;
$success=false;

if(!isset($_GET['name'])){
	die("Name parameter missing");
}else if(isset($_POST['logout'])&& $_POST['logout']=='logout'){
	header('Location:index.php');
}else if( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
		 if (!is_numeric($_POST['year'])||!is_numeric($_POST['mileage'])){
			 $failure='Mileage and year must be numeric';
		 }
		 else if(strlen($_POST['make'])<1){
			 $failure='Make is required';
		 }else{
    $sql = "INSERT INTO autos (make, year, mileage) 
              VALUES (:make, :year, :mileage)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));
		$success='Record inserted';
	 }
}


$stmt = $pdo->query("SELECT make, year, mileage, auto_id FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
	<?php require_once "bootstrap.php";?>
	<title> janmitha bangera's Automobile tracker</title>
</head>
<body>
<div class="container">
<h1>Tracking Autos for<?php echo $_GET['name']; ?></h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
if ( $success !== true ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="texr" name="mileage"></p>
<input type="submit" value="Add New"/>
<input type="submit" name="logout" value="logout">
</form>

<h2>Automobiles</h2>
<ul>

	<?php
	foreach($rows as $row){
		echo'<li>';
		echo htmlentities($row['make']). ' ' . $row['year'] . ' / ' . $row['mileage'];
	};
	echo'</li><br/>';
	?>
	</ul>
	</div>
</body>
