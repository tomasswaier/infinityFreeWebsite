<?php
/*
 * THIS CODE IS INSANELY ASS. EVERY TYPE OF QUESTION SHOULD HAVE IT'S OWN FUNCTION BUT
 * I COULD NOT FORSEE THIS ISSUE WHEN FIRST WRITING THIS CODE... PLEASE SOMEONE REWRITE IT
 * i CAN'T LOOK AT THIS NO MORE
 */

class OneFromMany{
	function execute_class($connection){

		//echo "<hr><pre>";
		//print_r($_POST);
		//echo "<pre><hr>";
		$last_id=get_highest_id($connection);
		$option_number=0;
		
		$flag='0';
		foreach ($_POST as $key => $val) {
		  $key = mysqli_real_escape_string($connection, $key);
		  $val = mysqli_real_escape_string($connection, $val);
		  if (strpos($key, "option_number_") !== false) {
				$arr=explode('_',$key);
				$curr_num=(int)end($arr);
				if ($curr_num==0) {
					$option_number++;
				}
		  	$query = "INSERT INTO options (question_id, option_text, is_correct,belongs_to) VALUES (?, ?, ?,?)";
				$stmt=mysqli_prepare($connection,$query);
				mysqli_stmt_bind_param($stmt, "isii", $last_id,$val,$flag,$option_number);
  	  	    
		  	  	//echo "<br>$query";
				if (mysqli_stmt_execute($stmt)){
		  	  echo "Option inserted successfully<br>";
		  	} else {
		  	  echo "Problem with option insertion: " . mysqli_error($connection) . "<br>";
		  	}
				$flag='0';
		  }elseif (strpos($key, "correct_option_one_from_many_") !== false) {
		  	$flag='1';
		  }
		}
		
	}


}

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
function validate_token($con,$test_id,$token){
	if(!is_numeric($test_id)){
		echo "test id isn't numeric";
		exit(0);
	}
	$query_correct_token="SELECT test_secret FROM test WHERE test_id = ?";
	$stmt=mysqli_prepare($con,$query_correct_token);
	mysqli_stmt_bind_param($stmt, "i", $test_id);
	mysqli_stmt_execute($stmt);
    	$result = mysqli_stmt_get_result($stmt);
	$row = $result->fetch_assoc();
	$correct_token = $row['test_secret'];
	return hash_equals($correct_token, $token);
}
function get_key_flag($key,$val){
	if (strpos($key, "correct_option_boolean_choice_") !== false) {
	    return ($val === 'true' || $val == 1)? '1':'0'; 
	}elseif (strpos($key, "correct_option_multiple_choice_") !== false) {
	    return $val;
	}	
	return 1;
}
function get_extras(){
	$result_string="";
	foreach ($_POST as $key => $val) {
		if (strpos($key, "multiple_choice_option_name_") !== false) {
			if (strlen($result_string)>0){
				$result_string.=";";
			}
			$result_string.=$val;
		}
	}
	return $result_string;
}

if ($_POST['submit']){
	require $_SERVER['DOCUMENT_ROOT'] . '/config/initiate_connection.php';
	if(!$connection){
		echo " connection problem";
		exit(0);
	}
	else{
		mysqli_set_charset($connection, "utf8");
	}
	$test_id=$_POST["test_number"];
	$token=$_POST["token"];
    	$valid=validate_token($connection, $test_id, $token);

    	if ($valid === false) {
    	    echo "Invalid token!";
    	    exit(0);
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
			echo "$file_path<br>";
			//echo "<br>$file_tmp";
			if(move_uploaded_file($file_tmp,$file_path)){
				echo" file uploaded";
				$file_name=$last_id . "." . $file_extension;
			}
			else{
				echo" file upload failed";
			}

		}
	}
	else{
		echo"file:None<br>";
	}
	/*
	echo "<hr><pre>";
	print_r($_POST);
	echo "<pre><hr>";
	 */


	$question_text=$_POST['question_text'];
	$question_text=str_replace("'",'"',$question_text);
	//echo "<br>question :";
	//echo $question_text;
	$question_type="";
	if(isset($_POST['correct_option_boolean_choice_1'])){
		$question_type="boolean-choice";
	}elseif (isset($_POST['correct_option_multiple_choice_0'])) {
		$question_type="multiple-choice";
	}elseif (isset($_POST['correct_option_one_from_many_0'])) {
		$question_type="one-from-many";
	}else{
		$question_type="write-in";
	}
	$test_id=$_POST['test_number'];

	if ($question_type=="multiple-choice") {
		
		$extras=get_extras();
		$query="INSERT INTO question (question,question_type,test_id,question_image,question_extras) VALUES (?,?,?,?,?)";
		$stmt=mysqli_prepare($connection,$query);
		mysqli_stmt_bind_param($stmt, "ssiss", $question_text, $question_type, $test_id, $file_name,$extras);
	}else {
		$query="INSERT INTO question (question,question_type,test_id,question_image) VALUES (?,?,?,?)";
		$stmt=mysqli_prepare($connection,$query);
		mysqli_stmt_bind_param($stmt, "ssis", $question_text, $question_type, $test_id, $file_name);
	}

	if (mysqli_stmt_execute($stmt)){
		echo"<br>Question successfully inserted<br>";
	}
	else{
		echo"<br>Question insertion failed<br>";
	}
	if ($question_type=="one-from-many") {
		$object=new OneFromMany();
		$object->execute_class($connection);
		
	}else {
		$flag = '1';
		//we ask for the highes id again which should be our question_num
		
		$last_id=get_highest_id($connection);
		
		
		foreach ($_POST as $key => $val) {
		    $key = mysqli_real_escape_string($connection, $key);
		    $val = mysqli_real_escape_string($connection, $val);
		
		    if (strpos($key, "option_number_") !== false) {
					if ($question_type=="one-from-many") {
		    	  $query = "INSERT INTO options (question_id, option_text, is_correct,belongs_to) VALUES (?, ?, ?,?)";
		    	  //$query = "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$last_id', '$val', '$flag')";
					  $stmt=mysqli_prepare($connection,$query);
					  mysqli_stmt_bind_param($stmt, "isii", $last_id,$val,$flag,);
					}
		    	$query = "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
		    	//$query = "INSERT INTO options (question_id, option_text, is_correct) VALUES ('$last_id', '$val', '$flag')";
					$stmt=mysqli_prepare($connection,$query);
					mysqli_stmt_bind_param($stmt, "isi", $last_id,$val,$flag);
  	  		    
		  		  	//echo "<br>$query";
					if (mysqli_stmt_execute($stmt)){
		  		  	    echo "Option inserted successfully<br>";
		  		  	} else {
		  		  	    echo "Problem with option insertion: " . mysqli_error($connection) . "<br>";
		  		  	}
		  		  	$flag = '1';
		
		  	}elseif (strpos($key, "correct_option_") !== false) {
		  		$flag=get_key_flag($key,$val);	
		  	}
		}
	}


	}


?>
