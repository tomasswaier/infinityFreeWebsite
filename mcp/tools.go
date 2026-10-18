package main

import (
	"context"
	"fmt"
	"net/url"

	"github.com/modelcontextprotocol/go-sdk/mcp"
)

// Inputs are kept flat and simple on purpose. LLMs kept messing up deeply nested
// key->value maps in the previous version so everything here is plain lists of objects.

type questionBase struct {
	TestID              int    `json:"test_id" jsonschema:"id of the test the question is added to. Get it from list_tests"`
	QuestionText        string `json:"question_text" jsonschema:"the question/assignment shown above the answer fields"`
	QuestionExplanation string `json:"question_explanation,omitempty" jsonschema:"optional explanation shown to the student after they submit the test"`
}

type listTestsInput struct{}

type listQuestionsInput struct {
	TestID int `json:"test_id" jsonschema:"id of the test. Get it from list_tests"`
}

type writeInBlank struct {
	PrecedingText string `json:"preceding_text,omitempty" jsonschema:"text shown before this blank"`
	CorrectAnswer string `json:"correct_answer" jsonschema:"the exact answer the student has to type into this blank. Grading is an exact case sensitive match. To accept several answers separate them with ; and no spaces, e.g. \"a;A\" accepts both a and A"`
}

type writeInInput struct {
	questionBase
	Blanks    []writeInBlank `json:"blanks" jsonschema:"one or more blanks the student fills in, in order"`
	AfterText string         `json:"after_text,omitempty" jsonschema:"optional text shown after the last blank"`
}

type booleanStatement struct {
	Text   string `json:"text" jsonschema:"statement the student marks as true or false"`
	IsTrue bool   `json:"is_true" jsonschema:"whether the statement is true"`
}

type booleanGroup struct {
	PrecedingText string             `json:"preceding_text,omitempty" jsonschema:"optional text shown above this group of statements"`
	Statements    []booleanStatement `json:"statements" jsonschema:"statements the student marks as true or false"`
}

type booleanChoiceInput struct {
	questionBase
	Groups []booleanGroup `json:"groups" jsonschema:"groups of true/false statements. Usually just one group"`
}

type singleCorrectInput struct {
	questionBase
	PrecedingText string   `json:"preceding_text,omitempty" jsonschema:"optional text shown above the options"`
	Options       []string `json:"options" jsonschema:"answer options (at least 2). Exactly one of them is correct"`
	CorrectIndex  int      `json:"correct_index" jsonschema:"0 based index of the correct option in options"`
}

type dropdownSelect struct {
	PrecedingText string   `json:"preceding_text,omitempty" jsonschema:"text shown before the dropdown"`
	Options       []string `json:"options" jsonschema:"options in the dropdown (at least 2)"`
	CorrectIndex  int      `json:"correct_index" jsonschema:"0 based index of the correct option in options"`
}

type dropdownInput struct {
	questionBase
	Selects []dropdownSelect `json:"selects" jsonschema:"one or more dropdowns, in order"`
}

type openAnswerInput struct {
	questionBase
	PrecedingText string `json:"preceding_text,omitempty" jsonschema:"optional text shown above the answer box"`
}

type choiceTableRow struct {
	Text          string `json:"text" jsonschema:"label shown at the end of the row"`
	CorrectColumn int    `json:"correct_column" jsonschema:"0 based index (into columns) of the correct column for this row"`
}

type choiceTable struct {
	PrecedingText string           `json:"preceding_text,omitempty" jsonschema:"optional text shown above the table"`
	Columns       []string         `json:"columns" jsonschema:"column headers (at least 2), e.g. [\"True\",\"False\",\"Not given\"]"`
	Rows          []choiceTableRow `json:"rows" jsonschema:"rows of the table. The student picks exactly one column in every row"`
}

type multipleChoiceInput struct {
	questionBase
	Tables []choiceTable `json:"tables" jsonschema:"one or more tables. Usually just one"`
}

type tableCell struct {
	Text     string `json:"text" jsonschema:"if is_answer is false: text shown in the cell (can be empty). If is_answer is true: the correct answer the student has to type. Exact case sensitive match; separate several accepted answers with ; and no spaces, e.g. \"a;A\""`
	IsAnswer bool   `json:"is_answer" jsonschema:"true = the student has to type this cell, false = the text is shown"`
}

type fillTable struct {
	PrecedingText string        `json:"preceding_text,omitempty" jsonschema:"optional text shown above the table"`
	Rows          [][]tableCell `json:"rows" jsonschema:"rows of cells, top to bottom. Every row must have the same number of cells. There is no separate header, use the first row for it"`
}

type fillInTableInput struct {
	questionBase
	Tables []fillTable `json:"tables" jsonschema:"one or more tables. Usually just one"`
}

func registerTools(server *mcp.Server, bridge *Bridge) {
	mcp.AddTool(server, &mcp.Tool{
		Name:        "list_tests",
		Description: "Lists all tests on the website with their test_id, school and number of questions.",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, _ listTestsInput) (*mcp.CallToolResult, any, error) {
		return call(ctx, bridge, "GET", "/mcp/tests", nil)
	})

	mcp.AddTool(server, &mcp.Tool{
		Name:        "list_questions",
		Description: "Lists the questions already in a test. Use it to avoid adding duplicate questions.",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in listQuestionsInput) (*mcp.CallToolResult, any, error) {
		return call(ctx, bridge, "GET", "/mcp/tests/"+url.PathEscape(fmt.Sprint(in.TestID))+"/questions", nil)
	})

	mcp.AddTool(server, &mcp.Tool{
		Name: "add_write_in_question",
		Description: "Adds a fill in the blank question. The student types the answer into each blank. " +
			"Example: preceding_text \"The capital of France is\", correct_answer \"Paris\", after_text \".\" " +
			"Answers are matched exactly (case sensitive, spaces count). If several answers should count as correct, put them " +
			"all into correct_answer separated by ; without spaces around it: \"Paris;paris;PARIS\" accepts any of the three.",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in writeInInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "write_in", in.questionBase, map[string]any{
			"blanks":     in.Blanks,
			"after_text": in.AfterText,
		})
	})

	mcp.AddTool(server, &mcp.Tool{
		Name:        "add_true_false_question",
		Description: "Adds a question where the student marks each statement as true or false. Any number of statements can be true.",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in booleanChoiceInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "boolean_choice", in.questionBase, map[string]any{
			"groups": in.Groups,
		})
	})

	mcp.AddTool(server, &mcp.Tool{
		Name:        "add_single_choice_question",
		Description: "Adds a question where the student picks exactly one correct option out of a list (radio buttons).",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in singleCorrectInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "boolean_choice_one_correct", in.questionBase, map[string]any{
			"preceding_text": in.PrecedingText,
			"options":        in.Options,
			"correct_index":  in.CorrectIndex,
		})
	})

	mcp.AddTool(server, &mcp.Tool{
		Name:        "add_dropdown_question",
		Description: "Adds a question with one or more dropdowns, each with exactly one correct option.",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in dropdownInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "one_from_many", in.questionBase, map[string]any{
			"selects": in.Selects,
		})
	})

	mcp.AddTool(server, &mcp.Tool{
		Name:        "add_open_answer_question",
		Description: "Adds a question with a free text answer box. It isn't graded automatically, so put the model answer into question_explanation.",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in openAnswerInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "open_answer", in.questionBase, map[string]any{
			"preceding_text": in.PrecedingText,
		})
	})
}

func registerTableTools(server *mcp.Server, bridge *Bridge) {
	mcp.AddTool(server, &mcp.Tool{
		Name: "add_choice_table_question",
		Description: "Adds a grid question: column headers on top, and every row has one radio button per column. " +
			"The student picks exactly one column in each row. Good for classifying items or true/false/not given. " +
			"Example: columns [\"Mammal\",\"Bird\"], rows [{text \"Dog\", correct_column 0}, {text \"Eagle\", correct_column 1}].",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in multipleChoiceInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "multiple_choice", in.questionBase, map[string]any{
			"tables": in.Tables,
		})
	})

	mcp.AddTool(server, &mcp.Tool{
		Name: "add_fill_in_table_question",
		Description: "Adds a table where some cells are shown as text and others (is_answer true) are empty fields the student types into. " +
			"Answer cells are graded like write in answers: exact case sensitive match, and several accepted answers can be given " +
			"separated by ; without spaces, e.g. \"Paris;paris\". All rows need the same number of cells; use the first row as a header. " +
			"Example rows: [[{text \"Country\"},{text \"Capital\"}], [{text \"France\"},{text \"Paris\", is_answer true}]].",
	}, func(ctx context.Context, _ *mcp.CallToolRequest, in fillInTableInput) (*mcp.CallToolResult, any, error) {
		return addQuestion(ctx, bridge, "fill_in_table", in.questionBase, map[string]any{
			"tables": in.Tables,
		})
	})
}

func addQuestion(ctx context.Context, bridge *Bridge, questionType string, base questionBase, fields map[string]any) (*mcp.CallToolResult, any, error) {
	fields["type"] = questionType
	fields["test_id"] = base.TestID
	fields["question_text"] = base.QuestionText
	if base.QuestionExplanation != "" {
		fields["question_explanation"] = base.QuestionExplanation
	}
	return call(ctx, bridge, "POST", "/mcp/questions", fields)
}

// call returns the website's json answer as the tool result so the model sees validation errors and can fix its input
func call(ctx context.Context, bridge *Bridge, method, path string, body any) (*mcp.CallToolResult, any, error) {
	status, respBody, err := bridge.Do(ctx, method, path, body)
	if err != nil {
		return errorResult(err.Error()), nil, nil
	}
	if status < 200 || status >= 300 {
		return errorResult(fmt.Sprintf("website returned status %d: %s", status, respBody)), nil, nil
	}
	return &mcp.CallToolResult{Content: []mcp.Content{&mcp.TextContent{Text: respBody}}}, nil, nil
}

func errorResult(message string) *mcp.CallToolResult {
	return &mcp.CallToolResult{IsError: true, Content: []mcp.Content{&mcp.TextContent{Text: message}}}
}
