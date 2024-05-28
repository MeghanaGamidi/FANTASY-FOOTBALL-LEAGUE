<?php
$import_attempted = false;
$import_succeeded=false;
$import_error_message="";
 if($_SERVER[ "REQUEST_METHOD"] =="POST") {
     $import_attempted = true;

     //$con = mysql_connect( hostname "localhost",
             // username"s_mgamidi " , password "u5gLaEVD " , database "s_mgamidi ");
     $con=mysqli_connect("localhost","s_mgamidi","u5gLaEVD","s_mgamidi");

	if (mysqli_connect_errno())
    {
        $import_error_message ="Failed to connect to Mysql ".mysqli_connect_error();
    }
    else{
        try{

            $contents = file_get_contents( $_FILES["importFile"]["tmp_name"]);
			$lines = explode( "\n", $contents );
			
			foreach($lines as $line)
			{
			  $parsed_csv_line = str_getcsv($line);
			}
			
	}
	catch( Error $exception) {
		$import_error_message = $exception->getMessage()
			." at: ".$exception->getFile()
			." (line ".$exception->getLine() . ") <br/>";
		}
	}
}  

?>
<?php include_once("header.php");?>

<div class ="container">
    <h1> Data Import</h1>
	
	<?php
	if($import_attempted) {
	if($import_succeeded) {
	 ?>
		<h2> <span class = "text-success"> Import Succeeded! </span>
		</h2>
		<?php } else { ?>
		  <h2> <span class = "text-danger"> Import Failed! </span></h2>
		 <?php echo $import_error_message ;?>
		  <br/> <br/>
	 <?php
	 }
    }
	 ?>

 <form method= "post" enctype="multipart/form-data">
     <div class = "input-group mb-3">
	<span class="input-group-text"> File : </span> <input class= "form-control" type = "file" name ="importFile"/>

        </div>

	<input class ="btn btn-primary" type = "submit" value= "upload data"/>
	</form>
        </div>
       <?php include_once("Footer.php");