<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TestController;
use App\Models\Question;
use App\Models\School;
use App\Models\Test;

/*
 * Endpoints used by the local MCP server (see mcp/ folder). They are protected by the
 * shared MCP_API_KEY (EnsureMcpKey middleware).
 * InfinityFree blocks requests that don't come from a real browser so the MCP server
 * doesn't call these directly. It sends them to the mcp/bridge page open in the
 * user's browser which then calls them with fetch().
 *
 * The LLM sends nicely structured json and this controller turns it into the same
 * flat key format the question creator form sends (preceding_text_write_in_0, option_number_0 ...)
 * so that TestController::insertOptionsFromArray can be reused.
 */
class McpController extends Controller
{
    private const QUESTION_TYPES = ['write_in', 'boolean_choice', 'boolean_choice_one_correct', 'one_from_many', 'open_answer', 'multiple_choice', 'fill_in_table'];

    public function bridge()
    {
        return view('mcpBridge');
    }

    public function listTests()
    {
        $tests = Test::orderBy('school_id')->orderBy('test_name')->withCount('questions')->get()
            ->map(fn ($test) => [
                'test_id' => $test->id,
                'test_name' => $test->test_name,
                'school_id' => $test->school_id,
                'school_name' => School::find($test->school_id)?->name,
                'number_of_questions' => $test->questions_count,
            ])->values();
        return response()->json(['success' => true, 'tests' => $tests]);
    }

    public function listQuestions($testId)
    {
        $test = Test::find($testId);
        if (!$test) {
            return $this->error('test_id does not exist', 404);
        }
        $questions = Question::where('tests_id', '=', $testId)->with('options')->get()
            ->map(fn ($question) => [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'explanation_text' => $question->explanation_text,
                'options' => $question->options->map(fn ($option) => [
                    'option_type' => $option->option_type,
                    'preceding_text' => $option->preceding_text,
                    'data' => $option->data,
                ]),
            ]);
        return response()->json(['success' => true, 'test_name' => $test->test_name, 'questions' => $questions]);
    }

    public function addQuestion(Request $request)
    {
        $request->validate([
            'test_id' => 'required|integer',
            'type' => 'required|in:'.implode(',', self::QUESTION_TYPES),
            'question_text' => 'required|string',
            'question_explanation' => 'nullable|string',
        ]);
        $test = Test::find($request['test_id']);
        if (!$test) {
            return $this->error('test_id does not exist', 404);
        }

        $options = match ($request['type']) {
            'write_in' => $this->writeInOptions($request),
            'boolean_choice' => $this->booleanChoiceOptions($request),
            'boolean_choice_one_correct' => $this->booleanChoiceOneCorrectOptions($request),
            'one_from_many' => $this->oneFromManyOptions($request),
            'open_answer' => $this->openAnswerOptions($request),
            'multiple_choice' => $this->multipleChoiceOptions($request),
            'fill_in_table' => $this->fillInTableOptions($request),
        };

        return (new TestController)->mcpAddQuestion([
            'test_id' => $test->id,
            'question_text' => $request['question_text'],
            'question_explanation' => $request['question_explanation'],
            'options' => $options,
        ]);
    }

    private function writeInOptions(Request $request)
    {
        $request->validate([
            'blanks' => 'required|array|min:1',
            'blanks.*.preceding_text' => 'nullable|string',
            'blanks.*.correct_answer' => 'required|string',
            'after_text' => 'nullable|string',
        ]);
        // after_text goes first. TestController::mcpAddQuestion moves the first element to the end as after_text
        $options = $request['after_text'] ? ['after_text' => $request['after_text']] : [];
        foreach (array_values($request['blanks']) as $i => $blank) {
            $options['preceding_text_write_in_'.$i] = $blank['preceding_text'] ?? '';
            $options['option_number_'.$i] = $blank['correct_answer'];
        }
        return $options;
    }

    private function booleanChoiceOptions(Request $request)
    {
        $request->validate([
            'groups' => 'required|array|min:1',
            'groups.*.preceding_text' => 'nullable|string',
            'groups.*.statements' => 'required|array|min:1',
            'groups.*.statements.*.text' => 'required|string',
            'groups.*.statements.*.is_true' => 'required|boolean',
        ]);
        $options = [];
        foreach (array_values($request['groups']) as $i => $group) {
            $options['preceding_text_boolean_choice_'.$i] = $group['preceding_text'] ?? '';
            foreach (array_values($group['statements']) as $j => $statement) {
                $options['option_number_'.$i.'_'.$j] = filter_var($statement['is_true'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
                $options['option_text_number_'.$i.'_'.$j] = $statement['text'];
            }
        }
        return $options;
    }

    private function booleanChoiceOneCorrectOptions(Request $request)
    {
        $request->validate([
            'preceding_text' => 'nullable|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_index' => 'required|integer|min:0|lt:'.count((array) $request['options']),
        ]);
        $options = ['preceding_text_boolean_choice_one_correct_0' => $request['preceding_text'] ?? ''];
        foreach (array_values($request['options']) as $i => $text) {
            $options['option_text_number_0_'.$i] = $text;
        }
        $options['correct_option_number_0'] = (string) $request['correct_index'];
        return $options;
    }

    private function oneFromManyOptions(Request $request)
    {
        $request->validate([
            'selects' => 'required|array|min:1',
            'selects.*.preceding_text' => 'nullable|string',
            'selects.*.options' => 'required|array|min:2',
            'selects.*.options.*' => 'required|string',
            'selects.*.correct_index' => 'required|integer|min:0',
        ]);
        $options = [];
        foreach (array_values($request['selects']) as $i => $select) {
            if ($select['correct_index'] >= count($select['options'])) {
                abort(response()->json(['success' => false, 'message' => "selects.$i.correct_index is out of range"], 422));
            }
            $options['preceding_text_one_from_many_'.$i] = $select['preceding_text'] ?? '';
            foreach (array_values($select['options']) as $j => $text) {
                $options['option_number_'.$i.'_'.$j] = $text;
            }
            $options['correct_option_index_'.$i] = (string) $select['correct_index'];
        }
        return $options;
    }

    private function openAnswerOptions(Request $request)
    {
        $request->validate(['preceding_text' => 'nullable|string']);
        return ['preceding_text_open_answer_0' => $request['preceding_text'] ?? ''];
    }

    private function multipleChoiceOptions(Request $request)
    {
        $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*.preceding_text' => 'nullable|string',
            'tables.*.columns' => 'required|array|min:2',
            'tables.*.columns.*' => 'required|string',
            'tables.*.rows' => 'required|array|min:1',
            'tables.*.rows.*.text' => 'required|string',
            'tables.*.rows.*.correct_column' => 'required|integer|min:0',
        ]);
        $options = [];
        foreach (array_values($request['tables']) as $i => $table) {
            $options['preceding_text_multiple_choice_'.$i] = $table['preceding_text'] ?? '';
            foreach (array_values($table['columns']) as $c => $columnName) {
                $options['column_number_'.$i.'_'.$c] = $columnName;
            }
            foreach (array_values($table['rows']) as $r => $row) {
                if ($row['correct_column'] >= count($table['columns'])) {
                    abort(response()->json(['success' => false, 'message' => "tables.$i.rows.$r.correct_column is out of range"], 422));
                }
                // same order as the form: radio value (index of the correct column) then the row text
                $options['correct_option_'.$i.'_'.$r] = (string) $row['correct_column'];
                $options['row_text_'.$i.'_'.$r] = $row['text'];
            }
        }
        return $options;
    }

    private function fillInTableOptions(Request $request)
    {
        $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*.preceding_text' => 'nullable|string',
            'tables.*.rows' => 'required|array|min:1',
            'tables.*.rows.*' => 'required|array|min:1',
            'tables.*.rows.*.*.text' => 'present|nullable|string',
            'tables.*.rows.*.*.is_answer' => 'required|boolean',
        ]);
        $options = [];
        foreach (array_values($request['tables']) as $i => $table) {
            $rows = array_values($table['rows']);
            $options['preceding_text_fill_in_table_'.$i] = $table['preceding_text'] ?? '';
            foreach ($rows as $r => $cells) {
                // the question creator only makes tables where every row has the same number of cells
                if (count($cells) != count($rows[0])) {
                    abort(response()->json(['success' => false, 'message' => "tables.$i.rows.$r has ".count($cells).' cells but the first row has '.count($rows[0]).'. All rows need the same number of cells'], 422));
                }
                foreach (array_values($cells) as $c => $cell) {
                    $isAnswer = filter_var($cell['is_answer'], FILTER_VALIDATE_BOOLEAN);
                    if ($isAnswer && ($cell['text'] ?? '') === '') {
                        abort(response()->json(['success' => false, 'message' => "tables.$i.rows.$r.$c is an answer cell so its text (the correct answer) can't be empty"], 422));
                    }
                    $options['correct_option_'.$i.'_'.$r.'_'.$c] = $cell['text'] ?? '';
                    // like an html checkbox: only sent when checked
                    if ($isAnswer) {
                        $options['is_answer_'.$i.'_'.$r.'_'.$c] = '1';
                    }
                }
            }
        }
        return $options;
    }

    private function error($message, $status)
    {
        return response()->json(['success' => false, 'message' => $message], $status);
    }
}
