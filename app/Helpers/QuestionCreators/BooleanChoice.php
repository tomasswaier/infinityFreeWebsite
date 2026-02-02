<?php
namespace app\Helpers\QuestionCreators;
use Illuminate\Support\Facades\Log;
use App\Helpers\QuestionCreators\QuestionType;

class BooleanChoice extends QuestionType{
    public $specificOptioNumber;
    public function __toString(){
        return "boolean_choice";
    }
    private function getSpecificOptionNumber($key){
        return intval(substr($key,16,strlen($key)));
    }
    private function getOptionNumber($key){
        //we assume that there will be less than 11 boolean choice tables dunnu why the first if is here like it's def a option_numeber_ but wahtevs
        if (str_contains($key,'option_text_number_')) {
            $temp = explode('_',$key);
            return intval($temp[3]);
        }else if(str_contains($key,'option_number_')){
            $temp = explode('_',$key);
            return intval($temp[2]);
        }
        Log::error("Error:getOptionNumber was called with unidentifiable input value:".$key);
        return intval(substr($key,14,15));
    }
    public function readOption($key,$val){
        if (str_contains($key,"option_text_number_")) {
            if (!isset($this->optionNumber) || $this->getOptionNumber($key)!=$this->optionNumber) {
                $this->optionNumber=$this->getOptionNumber($key);// By incrementing by 1 there could be errors
            }
            $splitKey=explode('_',$key);
            $isCorrect=$this->input['option_number_'.$splitKey[3]."_".$splitKey[4]];
            if ($isCorrect!='true' && $isCorrect!='false') {
                Log::error('Bad boolean choice input');
                exit;
            }
            $this->specificOptioNumber=$this->getSpecificOptionNumber($key);
            $this->data->push([
                'is_correct'=>($isCorrect=='true'? True :False),
                'option_text'=>$val,
                ]);

        }
        return;
    }
}
