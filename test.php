<?php
include 'process/functions.php';

echo "im here";
$getdesc = getdescription2();
$arraycount = count($getdesc);
for ($i = 0; $i <$arraycount; $i++){
	$desc[] = implode(',', $getdesc[$i]);
}

$table = getsanction();
if(isset($_GET['year'])){
		$table = sancbyyear($_GET['year']);
}

print_r($desc);
var_dump($desc);
// print_r($table);
echo $desc[0];
foreach ($table as $k) {
	echo $k->$desc[0];
}


 ?>
