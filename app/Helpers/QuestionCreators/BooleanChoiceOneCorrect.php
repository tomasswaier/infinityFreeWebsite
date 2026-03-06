<?php
namespace app\Helpers\QuestionCreators;

use App\Helpers\QuestionCreators\QuestionType;

class BooleanChoiceOneCorrect extends QuestionType{
    public $specificOptioNumber;
    public function __toString(){
        return "boolean_choice_one_correct";
    }
    private function getSpecificOptionNumber($key){
        return intval(substr($key,16,strlen($key)));
    }
    private function getOptionNumber($key){
        if (str_contains($key,'option_text_number_')) {
            $temp = explode('_',$key);
            return intval($temp[3]);
        }else if(str_contains($key,'option_number_')){
            $temp = explode('_',$key);
            return intval($temp[2]);
        }
        Log::error("Error:getOptionNumber was called with unidentifiable input value:".$key);
        return intval(substr($key,19,20));
    }
    public function readOption($key,$val){
        if (!isset($this->data['option_array'])) {
            $this->data['option_array']=collect();
        }
        if (str_contains($key,"option_text_number_")) {
            $this->data['option_array']->push($val);
        }else if(str_contains($key,"correct_option_")) {
            $this->data['correct_index']=$val;
        }

        return;
    }

}
