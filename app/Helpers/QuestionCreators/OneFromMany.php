<?php
namespace app\Helpers\QuestionCreators;

use App\Helpers\QuestionCreators\QuestionType;
class OneFromMany extends QuestionType{
    public function __toString(){
        return "one_from_many";
    }
    public function readOption($key,$val){
        if (!isset($this->data['option_array'])) {
            $this->data['option_array']=collect();
        }
        if (str_contains($key,"option_number_")) {
            $this->data['option_array']->push($val);
        }else if (str_contains($key,"correct_option_index_")) {

            //we assume that the user will not fuck with the html..(and that the json data will be in order but i do that quite a bit here bcs i dont see reason why na) like yes you could do it very easily but omg woow gj you did something on a student for fun project ur so kewl
            $this->data['correct_option']=intval($val);
        }else{
            Log::error('$key does not contain option_number_ .Error in input key ');
        }
        return;
    }
}
