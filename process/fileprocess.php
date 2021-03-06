<?php

session_start();
include "functions.php";
$db = connect();
if(!isset($_SESSION['admin'])){
	header('Location: ../index.php?error2');
}

if(isset($_POST['sub'])){
	if(!empty(getmeet())){ //check if there are already meetings
		//cannot automatically register students in sanction if there is
		header('Location: ../pages/students.php?error=1');
	}else{
		if(is_uploaded_file($_FILES['csv']['tmp_name'])){
				echo $_FILES['csv']['type'];
				if($_FILES['csv']['type'] == "application/vnd.ms-excel" 
					|| $_FILES['csv']['type'] == "application/octet-streamfile"
					|| $_FILES['csv']['type'] == "text/csv"){
				$uploaddir = '../uploads/';
				$uploadfile = $uploaddir . basename($_FILES['csv']['name']);
				echo $uploadfile.'<br>';
				if(move_uploaded_file($_FILES['csv']['tmp_name'],$uploadfile)){
					echo "good!<br>";
				}
				else{
					echo "not good!<br>";
				}

				$name = $_FILES['csv']['name'];
				$query = $db->prepare("LOAD DATA LOCAL INFILE '$uploadfile' INTO TABLE `student` FIELDS
				TERMINATED BY ',' LINES TERMINATED BY '\n' (`surname` , `firstname` , `year`, `cpnum`, `name`)");

				//this is for the fullname cloumn
				if($query->execute()){
					echo 'successfully uploaded <br>';
					foreach(disfullname() as $full){
						$id = $full->s_id; $name = $full->firstname.' '.$full->surname;
						$que = $db->prepare("UPDATE student SET	name = :name	WHERE s_id = :id");
						$que->bindValue('name',$name); $que->bindValue('id',$id);
						$que->execute();
					}

					//get id from recently uploaded csv
					foreach(disid() as $name){
						//loop into sanction the names and with a total default value
						$query2 = $db->prepare("INSERT INTO sanction SET s_id = ?, total = 0");
						$query2->bindParam(1,$name->s_id);
						$query2->execute();
					}
							//CLEAN
					header('Location: ../pages/students.php?success');
				}
				else{
					$query->closeCursor();
					$_SESSION['QUE_ERROR'] += 1;
					header('Location: ../pages/students.php?error=3');
				}
			}
			else{
				header('Location: ../pages/students.php?error=6');
			}

		}
		else{
			header('Location: ../pages/students.php?error=nofile');
		}
	}
} //end of isset post submit

if(isset($_POST['submit'])){
	if(!empty(getmeet())){ //check if there are already meetings
		//cannot automatically register students in sanction if there is
		header('Location: ../pages/students.php?error=1');
	}else{
		if(is_uploaded_file($_FILES['userlast']['tmp_name']) &&
			is_uploaded_file($_FILES['userfirst']['tmp_name']) &&
			is_uploaded_file($_FILES['yrs']['tmp_name']) &&
			is_uploaded_file($_FILES['cpnum']['tmp_name'])){
			if($_FILES['userlast']['type'] != "text/plain" &&
				$_FILES['userfirst']['type'] != "text/plain" &&
				$_FILES['yrs']['type'] != "text/plain" &&
				$_FILES['cpnum']['type'] != "text/plain" ){
				echo "Invalid Filetype";
			}
			elseif($_FILES['userlast']['size'] > 5000 &&
				$_FILES['userfirst']['size'] > 5000 &&
				$_FILES['yrs']['size'] > 5000 &&
				$_FILES['cpnum']['size'] > 5000){
				echo "File too large";
			}
			elseif($_FILES['userlast']['size'] < 50 &&
				$_FILES['userfirst']['size'] < 50 &&
				$_FILES['yrs']['size'] < 50 &&
				$_FILES['cpnum']['size'] < 50){
				echo "File too small";
			}
			elseif($_FILES['userlast']['error'] > 0 &&
				$_FILES['userfirst']['error'] > 0 &&
				$_FILES['yrs']['error'] > 0 &&
				$_FILES['cpnum']['error'] > 0){
				echo "Invalid File/ No file";
			}
			else{
				//$name = $_FILES['userfile']['name'].'<br>';
				$string = file_get_contents($_FILES['userlast']['tmp_name'], "r");
				$string1 = file_get_contents($_FILES['userfirst']['tmp_name'], "r");
				$string2 = file_get_contents($_FILES['yrs']['tmp_name'], "r");
				$string3 = file_get_contents($_FILES['cpnum']['tmp_name'], "r");
				$lname = explode("\n", $string);
				$fname = explode("\n", $string1);
				$yrs = explode("\n", $string2);
				$cpnum = explode("\n", $string3);

				$arraycount = count($lname);
				$arraycount1 = count($fname);
				$arraycount2 = count($yrs);
				$arraycount3 = count($cpnum);
				if($arraycount == $arraycount1 &&
					$arraycount1 == $arraycount2 &&
					$arraycount == $arraycount3 &&
					$arraycount2 == $arraycount3){

					for($i = 0; $i < $arraycount; $i++){
					$name = $fname[$i].' '.$lname[$i];
					$yrs[$i] = substr($yrs[$i],0,3);
						$query = $db->prepare("INSERT INTO student SET
									surname = ?, firstname = ?, year = ?, cpnum = ?, name = ?");
					$query->bindParam(1,$lname[$i]);
					$query->bindParam(2,$fname[$i]);
					$query->bindParam(3,$yrs[$i]);
					$query->bindParam(4,$cpnum[$i]);
					$query->bindParam(5,$name);
					$query->execute();
					}

					//get id from recently uploaded csv
					foreach(disid() as $name){
						//loop into sanction the names and with a total default value
						$query2 = $db->prepare("INSERT INTO sanction SET s_id = ?, total = 0");
						$query2->bindParam(1,$name->s_id);
						$query2->execute();
					}

				header('Location: ../pages/students.php?success');
				}
				else{
					echo "Data are inconsistent!";
				}
			}
		}
		else{
			echo "insufficient files";
		}
	}

}

 ?>
