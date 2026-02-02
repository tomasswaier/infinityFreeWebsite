<?php

namespace app\Helpers\QuestionCreators;

use App\Models\Option;
class QuestionType{
    //sorry for bad namizng
    public $questionNumber;
    public $precedingText;
    public $questionId; // questionId is needed for inserting optionId
    public $optionId;//id of CREATED option or of UPDATED option
    public $optionNumber; // number read from the option_number_  key
    public $input; // input with all data from request
    public $data;

    function __construct($questionNumber,$questionId,$precedingText,$input,$optionId=null){
        $this->questionNumber=$questionNumber;
        $this->precedingText=$precedingText;
        $this->questionId=$questionId;
        $this->optionId=$optionId;
        $this->input=$input;
        $this->data=collect();
    }
    function __toString(){
        return "question-type";
    }
    function __destruct()
    {
        $this->optionId=$this->createQuestion($this->__toString());
    }
    function createQuestion($option_type){
        $option = Option::find($this->optionId);
        if (!$option) {
            $option= new Option;
        }
        if ($this->optionId) {
            $option->id=$this->optionId;
        }
        $option->questions_id=$this->questionId;
        $option->preceding_text=$this->precedingText;
        $option->option_type=$option_type;
        $option->data=$this->data;
        $option->save();
    }
    function readOption($key,$val){


    }
}
