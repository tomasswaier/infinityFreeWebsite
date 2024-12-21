<?php
function get_highest_id($con){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	$result=0;
	$get_last_id_query="SELECT MAX(question_id) as 'max' FROM question";
	$result=mysqli_query($con,$get_last_id_query);
	
	if ($result) {
	    $row = mysqli_fetch_assoc($result);
	    $result= $row['max'];
	}

	
	return $result;
}


if ($_POST['submit']){
	require $_SERVER['DOCUMENT_ROOT'] . '/config/initiate_connection.php';
	if(!$connection){
		echo " connection problem";
	}
	else{
		mysqli_set_charset($connection, "utf8");
	}
	$file_name='NULL';
	if ($_FILES['user_image']['name']){
		$file_name=$_FILES['user_image']['name'];
		$file_tmp=$_FILES['user_image']['tmp_name'];
		$file_extension=pathinfo($file_name,PATHINFO_EXTENSION);
		$allowed_extensions=array('jpeg','jpg','png');
		if(in_array($file_extension,$allowed_extensions)){
			$last_id=get_highest_id($connection);
			$file_path= $_SERVER['DOCUMENT_ROOT'] . '/resources/test_images/' . $last_id . "." . $file_extension;
			echo $file_path;
			echo "<br>$file_tmp";
			if(move_uploaded_file($file_tmp,$file_path)){
				echo"file sent";
				$file_name=$last_id . "." . $file_extension;
			}
			else{
				echo"file upload failed";
			}

		}
	}
	else{
		echo"no file ";
	}
	/*
	echo "<hr><pre>";
	print_r($_POST);
	echo "<pre><hr>";
	*/


	$question_text=$_POST['question_text'];
	$question_type="";
	if(isset($_POST['correct_option_multiple_choice_1'])){
		$question_type="multiple-choice";
	}else{
		$question_type="write-in";
	}
	$test_id=$_POST['test_number'];

	$question_text=$_POST['question_text'];
	$query="INSERT INTO question (question,question_type,test_id,question_image) VALUES ('$question_text','$question_type','$test_id','$file_name')";
	//echo "<br>$query";
	if (mysqli_query($connection,$query)){
		echo"Question successfully inserted<br>";
	}
	else{
		echo"Question insertion failed<br>";
	}

	$flag = '1';
	
	//we ask for the highes id again which should be our question_num
	$last_id=get_highest_id($connection);
	
	foreach ($_POST as $key => $val) {
	    $key = mysqli_real_escape_string($connection, $key);
	    $val = mysqli_real_escape_string($connection, $val);
	
	    if (strpos($key, "correct_option_multiple_choice_") !== false) {
	        $flag = ($val === 'true' || $val == 1)? '1':'0'; 
	    }
	
		// I did not write this and i kinda have no idea why it's doing what it's doing
	    if (strpos($key, "option_number_") !== false) {
	    	$query = "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$last_id', '$val', '$flag')";
        
	    	//echo "<br>$query";
	    	if (mysqli_query($connection, $query)) {
	    	    echo "Data inserted successfully<br>";
	    	} else {
	    	    echo "Problem with data insertion: " . mysqli_error($connection) . "<br>";
	    	}
	    	$flag = '1';
	
	    }
	}


	}


?>
