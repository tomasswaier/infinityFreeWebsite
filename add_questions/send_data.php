<?php


if ($_POST['submit']){
	include_once("initiate_connection.php");
	if(!$connection){
		echo " connection problem";
	}
	$file_name="None";
	if ($_FILES['user_image']['name']){
		$file_name=$_FILES['user_image']['name'];
		$file_tmp=$_FILES['user_image']['tmp_name'];
		$file_extension=pathinfo($file_name,PATHINFO_EXTENSION);
		$allowed_extensions=array('jpeg','jpg','png');
		if(in_array($file_extension,$allowed_extensions)){
			$file_path= $_SERVER['DOCUMENT_ROOT'] . '/resources/test_images/' . $file_name;
			echo $file_path;
			echo "<br>$file_tmp";
			if(move_uploaded_file($file_tmp,$file_path)){
				echo"file sent";
			}
			else{
				echo"file upload failed";
			}

		}
	}
	else{
		echo"no file ";
	}
	echo "<hr><pre>";
	print_r($_POST);
	echo "<pre><hr>";
	

	$question_text=$_POST['question_text'];
	$question_type="";
	if(isset($_POST['correct_option_multiple_choice_1'])){
		$question_type="multiple-choice";
	} elseif (isset($_POST['correct_option_write_in_1'])) {
		$question_type="write-in";
	}else{
		$question_type="unidentified-question-type";
	}
	$test_id=$_POST['test_id'];

	$question_text=$_POST['question_text'];
	$query="INSERT INTO questions (question,question_typ,test_id,question_image) VALUES ('$question_text,$question_type,$test_id,$file_name')";
	echo "<br>$query";
}


?>
