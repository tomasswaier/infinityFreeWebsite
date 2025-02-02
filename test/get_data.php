<?php
	error_log(print_r($_POST, true));

	$test_id=$_POST['test_id'];
	if(!is_numeric($test_id)){
		echo "test id isn't numeric";
		exit(0);
	}

	$number_of_questions=$_POST['number_of_questions'];
	if(!is_numeric($number_of_questions)){
		echo "number of questions field isn't numeric";
		exit(0);
	}

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
	
	

	echo json_encode($questions);
?>

