<?php
	
	$running_localy=true;
	$number_of_questions=2;
	//$number_of_questions=$_POST['number'];
	$connection;
	if($running_localy){

		$config = require $_SERVER['DOCUMENT_ROOT'] . '/config/local.php';
		$connection = mysqli_connect(
		    $config['DB_HOST'],
		    $config['DB_USER'],
		    $config['DB_PASSWORD'],
		    $config['DB_NAME']
		);
		//echo "running localy";
	}else{
		//echo "not runninglocaly";
		$config = require $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
		
		$connection = mysqli_connect(
		    $config['DB_HOST'],
		    $config['DB_USER'],
		    $config['DB_PASSWORD'],
		    $config['DB_NAME']
		);
	}
	mysqli_set_charset($connection, "utf8");
	$questionsQuery = "SELECT question_id,question,question_type FROM question WHERE test_id = 1 ORDER BY RAND() LIMIT $number_of_questions";

	$result=mysqli_query($connection,$questionsQuery);
	$formated=mysqli_fetch_all($result, MYSQLI_ASSOC);


	$questions = [];
	foreach($formated as $question){
	    	$questionId = $question['question_id'];
	    	$questionType = $question['question_type'];
	
	    	$options = [];
	    	if ($questionType === 'multiple-choice') {
	    	    	$optionsQuery = "SELECT options_id,option_text,is_correct FROM options WHERE question_id = $questionId ORDER BY RAND()";
	    	    	$optionsResult = mysqli_query($connection,$optionsQuery);
	    	    	while ($option= mysqli_fetch_assoc($optionsResult)) {
	    	    	    	$options[] = $option;
	    	    	}
	    	}
	    	$question['options'] = $options;
	    	$questions[] = $question;
	}
	/*
	echo "<hr><pre>";
	print_r($questions);
	echo "<pre><hr>";
	echo "<script> var data = " . json_encode($questions) . "; </script>";
	*/
	
	

	//header('Content-Type: application/json');
	echo json_encode($questions);
?>

