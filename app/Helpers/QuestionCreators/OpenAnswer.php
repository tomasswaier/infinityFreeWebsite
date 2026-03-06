<?php
namespace app\Helpers\QuestionCreators;

use App\Helpers\QuestionCreators\QuestionType;
class OpenAnswer extends QuestionType{
    public function __toString(){
        return "open_answer";
    }
    public function readOption($key,$val){
        return;
    }
}
