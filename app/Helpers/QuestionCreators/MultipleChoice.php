<?php
namespace app\Helpers\QuestionCreators;

use App\Helpers\QuestionCreators\QuestionType;
class MultipleChoice extends QuestionType{
    public function __toString(){
        return "multiple_choice";
    }
    public function readOption($key,$val){

        if (!isset($this->data['column_names'])) {
            $this->data['column_names']=collect();
            $this->data['row_array']=collect();
        }
        if (str_contains($key,"column_number_")) {
            $this->data['column_names']->push($val);
        }else if (str_contains($key,"correct_option_")) {
            $rowName=$this->input['row_text_'.substr($key,15,strlen($key))];
            $this->data['row_array']->push(['row_name'=>$rowName,'correct_answer'=>$val]);
        }else if (str_contains($key,"row_text_")) {
            return;
        }else {
            Log::error('$key does not contain option_number_ .Error in input key:'.$key);
        }
        return;
    }
}
