<?php
session_start();
include "../process/functions.php";
if(!isset($_SESSION['admin'])){
	header('Location: ../index.php?error=2');
}

if($_SESSION['priv'] != 'TREASURER' && $_SESSION['priv'] != 'DEAN'){
	header('Location: index.php?error=4');
}

$super = "";
if($_SESSION['priv'] == "DEAN"){
	$super = '<li id="superuser">
							<a href="superuser.php"><i class="fa fa-fw fa-user-secret"></i> Superuser</a>
						</li>';
}
//convert dates
$meetdate = [];
foreach(getmeet() as $d){
	$month = date('M', strtotime($d->m_date));
	$day = date('d', strtotime($d->m_date));
	$meetdate[] = $d->description." (".$month." ".$day.")";
}

$options = options();

$getdesc = getdescription2(); //2 means FETCH_ASSOC
$arraycount = count($getdesc);
$studentcount = count(getstudents());

if($arraycount < 1 || $arraycount == 0){
	//if no meeting yet redirect to meetings page
	header('Location: meetings.php?error=5');
}
elseif ($studentcount < 1 || $studentcount == 0) {
	//if no students yet, redirect to students page
	header('location: students.php?error=5');
}

$_SESSION['count']=$arraycount;
for ($i = 0; $i <$arraycount; $i++){
	$desc[] = implode(',', $getdesc[$i]);
}
	//for name options in add student-sanction
	$data = disname();
	$option = "";
	foreach ($data as $row) {
		$name = $row->name;
		if(find($name)){
			continue;
		}else{
			$option.='<option value="'.$name.'">'.$name.'</option>';
		}
  }

$table = getsanction();
if(isset($_GET['year'])){
		$table = sancbyyear($_GET['year']);
}

if($arraycount >= 1){$meet1 = $desc[0];}
if($arraycount >= 2){$meet2 = $desc[1];}
if($arraycount >= 3){$meet3 = $desc[2];}
if($arraycount >= 4){$meet4 = $desc[3];}
if($arraycount >= 5){$meet5 = $desc[4];}
if($arraycount >= 6){$meet6 = $desc[5];}
if($arraycount >= 7){$meet7 = $desc[6];}
if($arraycount >= 8){$meet8 = $desc[7];}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sanctions</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../icons/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
		<link href="https://fonts.googleapis.com/css?family=Play|Squada+One" rel="stylesheet">
		<link rel="icon" type="image/png" href="../img/favicon.png">
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">SRS Admin</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                        </li>
                        <li>
                            <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">View All</a>
                        </li>
                    </ul>
                </li> -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user">
                    </i> &nbsp <?php echo $_SESSION['priv']; ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="help.php"><i class="fa fa-fw fa-gear"></i> Help</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="../index.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
										<?php echo $super; ?>
                    <li>
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="students.php"><i class="fa fa-fw fa-group"></i> Students</a>
                    </li>
                    <li>
                        <a href="meetings.php"><i class="fa fa-fw fa-bar-chart-o"></i> Meetings</a>
                    </li>
                    <li  class="active">
                        <a href="#"><i class="fa fa-fw fa-table"></i> Sanctions</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-download"></i> Downloads <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
													<li>
															<a href="../process/filedownload.php?file=1">
																<i class="fa fa-database"></i> &nbsp Sql File</a>
													</li>
													<li>
															<a href="../process/filedownload.php?file=2">
																<i class="fa fa-file-excel-o"></i> &nbsp CSV format</a>
													</li>
													<li>
															<a href="../process/filedownload.php?file=3">
																<i class="fa fa-file-text-o"></i> &nbsp Txt format</a>
													</li>
													<li>
															<a href="../process/filedownload.php?file=4">
																<i class="fa fa-list-alt"></i> &nbsp Sanction Record</a>
													</li>
                        </ul>
                    </li>
										<li>
                        <a href="help.php"><i class="fa fa-fw fa-file-text"></i> User's Manual </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid" style="height: 600px !important; overflow: auto;">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-table"></i> Sanctions
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                  <div class="col-lg-4">
										<div class="panel panel-yellow">
											<div class="panel-heading">
												<a href="javascript:;" data-toggle="collapse"
												data-target="#panelbody" style="text-decoration: none;
												color: black;">	<h3 class="panel-title">
												<i class="fa fa-tasks"></i>	&nbsp Add Student-Sanction
												&nbsp <i class="fa fa-caret-down"></i></h3></a>
											</div> <!-- End of panel heading -->
											<div class="collapse panel-body" id="panelbody">
													<div class="row">
														<div class="col-md-12">
															<form class="form-group" method="POST" action="../process/sanctionprocess.php">
																<select class="form-control" name="name" required="required"
																style="margin-bottom: 10px;">
																			<option disabled selected hidden value="">Student Name</option>
														    				<?php echo $option?>
																</select>
																<div class="col-md-6">
																	<?php if($arraycount >= 1)
																	{echo '<select class="form-control"
																		name="'.$desc[0].'" style="margin-bottom: 5px;" required>';
																	 echo '<option disabled selected hidden value="">'.$desc[0].'</option>';
																	 echo $options.'</select>';}?>
																	 <?php if($arraycount >= 3)
	 																{echo '<select class="form-control"
																		name="'.$desc[2].'" style="margin-bottom: 5px;" required>';
	 																 echo '<option disabled selected hidden value="">'.$desc[2].'</option>';
	 																 echo $options.'</select>';}?>
	 																 <?php if($arraycount >= 5)
	  																{echo '<select class="form-control"
																			name="'.$desc[4].'" style="margin-bottom: 5px;" required>';
	  																 echo '<option disabled selected hidden value="">'.$desc[4].'</option>';
	  																 echo $options.'</select>';}?>
	 																 <?php if($arraycount >= 7)
	  																{echo '<select class="form-control"
																			name="'.$desc[6].'" style="margin-bottom: 5px;" required>';
	  																 echo '<option disabled selected hidden value="">'.$desc[6].'</option>';
	  																 echo $options.'</select>';}?>
																</div>
																<div class="col-md-6" style="margin-bottom: 10px;">
																	<?php if($arraycount >= 2)
																	{echo '<select class="form-control"
																		name="'.$desc[1].'" style="margin-bottom: 5px;" required>';
																	 echo '<option disabled selected hidden value="">'.$desc[1].'</option>';
																	 echo $options.'</select>';}?>
																	 <?php if($arraycount >= 4)
	 																{echo '<select class="form-control"
																		name="'.$desc[3].'" style="margin-bottom: 5px;" required>';
	 																 echo '<option disabled selected hidden value="">'.$desc[3].'</option>';
	 																 echo $options.'</select>';}?>
																	 <?php if($arraycount >= 6)
																	 {echo '<select class="form-control"
																		 name="'.$desc[5].'" style="margin-bottom: 5px;" required>';
																		echo '<option disabled selected hidden value="">'.$desc[5].'</option>';
																		echo $options.'</select>';}?>
																		<?php if($arraycount >= 8)
	 																{echo '<select class="form-control" name="'.$desc[7].'" required>';
	 																 echo '<option disabled selected hidden value="">'.$desc[7].'</option>';
	 																 echo $options.'</select>';}?>

																</div>
																<input class="btn btn-primary btn-block" style="margin-top: 15px;"
																type="submit" name="addsanc" value="Submit Entry">
																</form>
														</div>
													</div>
													<!-- End of row for adding student-sanction -->

											</div>
										</div>
                  </div>
									<div class="col-lg-8">
										<div class="panel panel-green">
											<div class="panel-heading">
												<a href="javascript:;" data-toggle="collapse"
												data-target="#panelbody2" style="text-decoration: none;
												color: white;"> <h3 class="panel-title">
												<i class="fa fa-mortar-board"></i> &nbsp Edit by year
												&nbsp <i class="fa fa-caret-down"></i></h3>	</a>
											</div> <!-- End of panel heading -->
											<div class="collapse panel-body" id="panelbody2">
												<div class="row">
													<div class="col-lg-3" style="margin-bottom: 10px;">
														<a data-toggle="modal" data-id="1st" title="Add this item"
															class="editSanctionyear btn btn-primary btn-block" data-target="#edit-sanction-by-year">
														<i class="fa fa-edit"></i> 1st Year</a>
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;">
														<a data-toggle="modal" data-id="2nd" title="Add this item"
															class="editSanctionyear btn btn-success btn-block" data-target="#edit-sanction-by-year">
														<i class="fa fa-edit"></i> 2nd Year</a>
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;">
														<a data-toggle="modal" data-id="3rd" title="Add this item"
															class="editSanctionyear btn btn-warning btn-block" data-target="#edit-sanction-by-year">
														<i class="fa fa-edit"></i> 3rd Year</a>
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;">
														<a data-toggle="modal" data-id="4th" title="Add this item"
															class="editSanctionyear btn btn-danger btn-block" data-target="#edit-sanction-by-year">
														<i class="fa fa-edit"></i> 4th Year</a>
													</div>
												</div>
											</div>
										</div>
									</div>
                </div>
                <!-- /.row -->
								<div class="row">
									<div class="col-lg-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<div class="row">
													<div class="col-lg-3">
														<h1 style="font-size: 1.5em;" class="panel-title">
														<i class="fa fa-th"></i>
														&nbsp Sanction List</h1>
													</div>
													<div class="col-lg-3">
														<a class="btn btn-default btn-sm" href="sanctions.php?year=1st">1st</a>
														<a class="btn btn-default btn-sm" href="sanctions.php?year=2nd">2nd</a>
														<a class="btn btn-default btn-sm" href="sanctions.php?year=3rd">3rd</a>
														<a class="btn btn-default btn-sm" href="sanctions.php?year=4th">4th</a>
													</div>
													<div class="col-lg-2 text-right" style="padding-left: 0px; padding-right: 0px;">
														<a class="btn btn-success btn-sm" href="../process/filedownload.php?file=4">
															<i class="fa fa-download"></i></a>
														<a class="btn btn-warning btn-sm" value="print"
													 	onclick="PrintDiv();"><i class="fa fa-print"></i></a>
													</div>
													<div class="col-lg-4 text-right">
														<div class="input-group">
																<span style="font-size: 10px;"
																class="input-group-addon"><i class="fa fa-search"></i></span>
																<input style="background-color: #F4F4F4; height: 25px;"
																class="form-control" type="text" name="searchname"
																value="" id="searchname" placeholder="Search here by name">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">

												</div>
											</div>
											<div class="panel-body" style="padding-top: 0px;">
												<div class="row" style="overflow: auto;">
													<div class="flot-chart" id="divToPrint">
			<table class="table table-responsive table-striped">
				<thead class="text-center">
					<tr class="">
						<th>Student</th>
						<?php foreach ($meetdate as $g): ?>
						<th><?php echo $g; ?></th>
						<?php endforeach;?>
						<th>Total</th>
						<th class="text-center">Option</th>
					</tr>
				</thead>
				<tbody id="sanctions-table">
					<?php foreach ($table as $k):?>
						<tr>
							<td><?php echo $k->surname.', '.$k->firstname; ?></td>
							<?php $total = 0;?>
							<?php if($arraycount >= 1){echo '<td>'.$k->$meet1.'</td>';
											if(is_numeric($k->$meet1)){$total += $k->$meet1;}} ?>
							<?php if($arraycount >= 2){echo '<td>'.$k->$meet2.'</td>';
											if(is_numeric($k->$meet2)){$total += $k->$meet2;}} ?>
							<?php if($arraycount >= 3){echo '<td>'.$k->$meet3.'</td>';
											if(is_numeric($k->$meet3)){$total += $k->$meet3;}} ?>
							<?php if($arraycount >= 4){echo '<td>'.$k->$meet4.'</td>';
											if(is_numeric($k->$meet4)){$total += $k->$meet4;}} ?>
							<?php if($arraycount >= 5){echo '<td>'.$k->$meet5.'</td>';
											if(is_numeric($k->$meet5)){$total += $k->$meet5;}} ?>
							<?php if($arraycount >= 6){echo '<td>'.$k->$meet6.'</td>';
											if(is_numeric($k->$meet6)){$total += $k->$meet6;}} ?>
							<?php if($arraycount >= 7){echo '<td>'.$k->$meet7.'</td>';
											if(is_numeric($k->$meet7)){$total += $k->$meet7;}} ?>
							<?php if($arraycount >= 8){echo '<td>'.$k->$meet8.'</td>';
											if(is_numeric($k->$meet8)){$total += $k->$meet8;}} ?>
							<td><?php if($total == 0){ echo "CLEARED";}else{echo $total;} ?></td>
							<td class="text-center"><a data-toggle="modal" data-id="<?php echo $k->sanc_id;?>" title="Add this item"
								class="editSanction btn btn-primary" data-target="#edit-sanction">
							<i class="fa fa-edit"></i></a>
							<a class="deleteSanction btn btn-danger" data-id="<?php echo $k->sanc_id?>">
							<i class="fa fa-trash"></i></a></td>
						</tr>
						<?php endforeach;?>
				</tbody>
			</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="modal fade" id="edit-sanction" role="dialog">
									<div class="modal-dialog modal-md">
										<div class="modal-content form-group">
											<form class="form-group" action="../process/sanctionprocess.php" method="post">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<div class="text-center">
													<h3 class="modal-title font2"> <i class="fa fa-edit"></i> &nbspSanction Information</h3>
												</div>
											</div>
											<div class="modal-body" id="sanction_details">

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default"
												data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary" name="updatesanc">Save Changes</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<!-- end of modal -->

								<div class="modal fade" id="edit-sanction-by-yr" role="dialog">
									<div class="modal-dialog modal-lg">
										<div class="modal-content form-group" style="">
											<form class="form-group" action="../process/sanctionprocess.php" method="post">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<div class="text-center">
													<h3 class="modal-title font2"> <i class="fa fa-edit"></i> &nbspSanction Information</h3>
												</div>
											</div>
											<div class="modal-body" id="sanction_details-by-yr"
											style="overflow: auto; height: 450px;">

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default"
												data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary" name="updatebyyr">Save Changes</button>
											</div>
											</form>
										</div>
									</div>
								</div>
								<!-- end of modal -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

		<!-- Custom JS -->
		<script src="../js/master.js"></script>

		<script type="text/javascript">
		//i dont know why this code wont run on master.js (external source)
		// take this PROBLEM up later
		$(function(){
				$('#searchname').keyup(function(event){
						var keyCode = event.which; // check which key was pressed
						var name = $(this).val(); // get the complete input
						var nothing = 'nothingLOL';
						if(name != '')
							{
									 $.ajax({
												url:"editsanctions.php",
												method:"POST",
												data:{searchname:name},
												success:function(data){
														 $('#sanctions-table').html(data);
														 console.log('success!');
												}
									 });
							}
						else{ //if he erases everythin, the table goes back to normal
							$.ajax({
									 url:"editsanctions.php",
									 method:"POST",
									 data:{show:nothing},
									 success:function(data){
												$('#sanctions-table').html(data);
												console.log('success!');
									 }
							});
						}
				});
		});

		function PrintDiv() {
		   var divToPrint = document.getElementById('divToPrint');
		   var popupWin = window.open('', '_blank', 'width=900,height=500');
		   popupWin.document.open();
		   popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
		   popupWin.document.close();
		  }
		</script>

</body>

</html>
