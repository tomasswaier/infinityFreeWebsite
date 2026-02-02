<?php
namespace app\Helpers\QuestionCreators;

use App\Helpers\QuestionCreators\QuestionType;
class FillInTable extends QuestionType{
    public function __toString(){
        return "fill_in_table";
    }
    public function readOption($key,$val){
        if (!isset($this->data['row_array'])) {
            $this->data['row_array']=collect();
        }
        if(str_contains($key,"is_answer_") || str_contains($key,"option_id_")){
            return;
        }else if (str_contains($key,"correct_option_")) {
            $rowNumber=explode("_",$key)[3];
            if(!isset($this->data['row_array'][intval($rowNumber)])){
                $this->data['row_array'][intval($rowNumber)]=collect();
            }
            $this->data['row_array'][$rowNumber]->push([
                "cellText"=>$val,
                "isAnswer"=>isset($this->input["is_answer_".substr($key,15,strlen($key))])
            ]);
        }else {
            Log::error('$key does not contain option_number_ .Error in input key:'.$key);
        }
        return;
    }
}
