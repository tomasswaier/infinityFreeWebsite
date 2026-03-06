<?php
namespace app\Helpers\QuestionCreators;
use Illuminate\Support\Facades\Log;

use App\Helpers\QuestionCreators\QuestionType;

class WriteIn extends QuestionType{
    public function __toString(){
        return "write_in";
    }
    function __destruct()
    {
        Log::info($this->questionNumber);
        $this->data['correct_answer']=$this->input['option_number_'.$this->questionNumber];
        Log::info($this->data);

        $this->optionId=$this->createQuestion($this->__toString());
    }
    public function readOption($key,$val){
        if (str_contains($key,"after_text")) {
            $this->data['after_text']=$val;
        }
        return;
    }
}
