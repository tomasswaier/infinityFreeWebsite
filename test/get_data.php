<?php
	$number_of_questions=number_format($_COOKIE["number_of_questions"]);
	//$number_of_questions=intval($_COOKIE["number_of_questions"]);
	$test_id=number_format($_COOKIE["test_id"]);
	//$test_id=1;
	require $_SERVER['DOCUMENT_ROOT'] . '/config/initiate_connection.php';
	mysqli_set_charset($connection, "utf8");
	$questionsQuery = "SELECT question_id,question,question_type,question_image FROM question WHERE test_id = $test_id ORDER BY RAND() LIMIT $number_of_questions";

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
		else if ($questionType === 'write-in') {
	    	    	$optionsQuery = "SELECT options_id,option_text,is_correct FROM options WHERE question_id = $questionId ";
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

