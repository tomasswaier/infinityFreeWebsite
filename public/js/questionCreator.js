// I am not changing kinda dumb logic begind option_number_x_y to
// option_number_x[] because it's already working and I don't see reason why . I
// konw it's an option just don't see much use in it
//
// One might ask themself "why are there so many events in here ? in half the
// places they dont need to be. I do not know myself and I'm not going to
// rewrite it if it works. it's not too unreadable so few random events won't
// hurt
// one default class who the others would inherit from would solve SO MANY
// THINGS IN THIS CODE. like at first it was fine no class needed but now it
// kinda is needed and bcs the insides of classes are SO MESSY
if (!document) {
  const {document} = require("postcss");
}

function display_input_image() {
  // displays  selected user image to user for confirmation/better ux
  var image = document.getElementById("user_image").files[0];
  var reader = new FileReader();
  reader.onload =
      function(
          e) { document.getElementById("display_image").src = e.target.result; }

      reader.readAsDataURL(image);
}
var option_number = 0;

class OpenAnswer {
  constructor(event, button) {}
  add_child_option(event) {
    const preceding_text_input_field = document.createElement("textarea");
    const new_option_number = option_number;
    const parent = document.getElementById("options_table");
    parent.appendChild(preceding_text_input_field);
    preceding_text_input_field.setAttribute("cols", "50");
    preceding_text_input_field.setAttribute("hidden", "true");
    preceding_text_input_field.setAttribute("rows", "2");
    preceding_text_input_field.setAttribute(
        "name", "preceding_text_open_answer_" + new_option_number);
    preceding_text_input_field.setAttribute(
        "placeholder", "Here goes preceding text(can be left blank)");
  }
}

class MultipleChoice {
  constructor(event, button) {
    var self = this;
    this.column_number = 0;
    this.initialize_column_row();
    this.newOptionFieldButton = button;
    button.setAttribute("title", "add row");
    button.onclick = function(event) { self.add_child_option(event) };
  }
  add_child_option(event, option = null) {
    this.column_number = 0;
    var self = this;
    const new_option_number = option_number;
    this.initialize_column_row();
    const wrapper = document.createElement("tr");
    const preceding_text_input_field = document.createElement("textarea");
    wrapper.appendChild(preceding_text_input_field);
    preceding_text_input_field.setAttribute("cols", "50");
    preceding_text_input_field.setAttribute("rows", "2");
    preceding_text_input_field.setAttribute(
        "name", "preceding_text_multiple_choice_" + new_option_number);
    preceding_text_input_field.setAttribute(
        "placeholder", "Here goes preceding text(can be left blank)");
    const table = document.createElement("table");
    this.table = table;
    wrapper.appendChild(table);
    const parent = document.getElementById("options_table");
    parent.appendChild(wrapper);
    this.table.appendChild(this.initialize_column_row(new_option_number));
    const button_add_row = document.createElement("button");
    this.table.parentElement.appendChild(button_add_row);
    button_add_row.onclick = function(
        event) { self.add_row(event, table, new_option_number) };
    button_add_row.innerText = "+";
    button_add_row.setAttribute('title', 'Add row');

    parent.parentElement.insertBefore(document.createElement("br"),
                                      this.newOptionFieldButton);
    option_number += 1;
    if (option) {
      preceding_text_input_field.value = option['preceding_text'];
      for (var column_name of option['data']['column_names']) {
        this.add_column(event, new_option_number, this.columns, column_name);
      }

      for (var row_data of option['data']['row_array']) {
        this.add_row(event, table, new_option_number, row_data);
      }
    } else {
      this.add_column(event, new_option_number, this.columns);
      this.add_column(event, new_option_number, this.columns);
    }
  }
  initialize_column_row(new_option_number) {
    var self = this;
    const table_head = document.createElement("thead");
    const column_names_row = document.createElement("tr");
    column_names_row.setAttribute("id", "column_names_row");
    self.columns = column_names_row;
    column_names_row.classList.add("flex", "flex-nowrap", "block");
    table_head.appendChild(column_names_row);
    const button_add_columns = document.createElement("button");
    button_add_columns.setAttribute("type", "button");
    button_add_columns.setAttribute("title", "Add column");
    button_add_columns.setAttribute("name", "button_add_columns");
    self.button_add_columns = button_add_columns;
    button_add_columns.classList.add(
        "border",
        "rounded-md",
        "w-10",
        "border-black",
    );
    button_add_columns.innerHTML = "+";
    const columns = this.columns;
    button_add_columns.onclick = function(
        event) { self.add_column(event, new_option_number, columns) };
    column_names_row.appendChild(button_add_columns)
    return table_head;
  }

  add_row(event, parent, option_number, row_data = null) {
    // adds one row with this.column_number number of radio buttons + input
    const column_number = parent.children[0].children[0].children.length - 1
    const row_number = parent.children.length - 1;
    console.log(row_number);
    if (event) {
      event.preventDefault();
    }
    // bad
    var button = parent.children[0].children[0].children[0];
    if (button.name == "button_add_columns") {
      // if this is first time we are adding rows this removes the button for
      // adding columns + adds blank cell
      //;j
      button.remove();
      const column_row = parent.children[0].children[0];
      const empty_cell = document.createElement("td");
      empty_cell.classList.add("w-30");
      column_row.appendChild(empty_cell)
    }
    const new_options_row = document.createElement("tr");
    new_options_row.classList.add("flex", "justify-center");
    parent.appendChild(new_options_row);
    for (let i = 0; i < column_number; i++) {
      const option_wrapper = document.createElement("td");
      option_wrapper.classList.add("w-30", "flex", "justify-center");
      new_options_row.appendChild(option_wrapper)
      const radio_button_option = document.createElement("input");
      radio_button_option.setAttribute("type", "radio");
      radio_button_option.setAttribute("value", i);
      radio_button_option.setAttribute(
          "name", "correct_option_" + option_number + "_" + row_number);

      option_wrapper.appendChild(radio_button_option);
      if (i == row_data['correct_answer']) {
        radio_button_option.checked = true;
      }
    }
    const input_wrapper = document.createElement("td")
    new_options_row.appendChild(input_wrapper);
    input_wrapper.classList.add("w-30")
    const input_field = document.createElement("input");
    input_wrapper.appendChild(input_field)
    input_field.setAttribute("id",
                             "row_text_" + option_number + "_" + row_number);
    input_field.setAttribute("name",
                             "row_text_" + option_number + "_" + row_number);
    input_field.classList.add("w-30");
    if (row_data) {
      input_field.value = row_data['row_name'];
    }
  }
  add_column(event, new_option_number, column_row, column_name = null) {
    // collumns at the top providing description for columns
    if (event) {
      event.preventDefault();
    }
    const column_number = column_row.children.length - 1;
    const new_column = document.createElement("td");

    column_row.appendChild(new_column);
    new_column.setAttribute("name", "column_name_" + column_number);
    new_column.classList.add("w-30");
    const data_fieldset = document.createElement("fieldset")

    new_column.appendChild(data_fieldset);
    const column_input_area = document.createElement("input");
    data_fieldset.appendChild(column_input_area)
    column_input_area.setAttribute("id", "column_number_" + new_option_number +
                                             "_" + column_number);
    column_input_area.value = column_name;
    column_input_area.setAttribute(
        "name", "column_number_" + new_option_number + "_" + column_number);

    column_input_area.classList.add("w-30");
    column_input_area.setAttribute("type", "text");

    this.column_number++;
  }
}
class BooleanChoiceClass {
  constructor(event, button) {
    var self = this;
    self.createNewButton = button;
    button.onclick = function(event) { self.add_child_option(event) };
  }
  add_option_boolean_choice(event, parent, my_option_number, choice = null) {
    if (event) {
      event.preventDefault();
    }
    // console.log(my_option_number);
    const table_row = document.createElement("tr");
    parent.appendChild(table_row);
    const fieldset = document.createElement("fieldset");
    table_row.appendChild(fieldset);
    const specific_option_number =
        fieldset.parentElement.parentElement.children.length - 2;
    const answer_true = document.createElement("input");
    fieldset.appendChild(answer_true);
    answer_true.setAttribute("type", "radio");
    // Ill fix it some other time
    answer_true.setAttribute("value", "true");
    if (choice && choice['is_correct'] == true) {
      answer_true.checked = true;
    }

    // we should ask for grand grand parent
    answer_true.setAttribute("id", "option_number_" + my_option_number + "_" +
                                       specific_option_number);
    answer_true.setAttribute("name", "option_number_" + my_option_number + "_" +
                                         specific_option_number);
    const answer_false = document.createElement("input");
    fieldset.appendChild(answer_false);
    answer_false.setAttribute("type", "radio");
    answer_false.setAttribute("value", "false");
    answer_false.setAttribute("id", "option_number_" + my_option_number + "_" +
                                        specific_option_number);
    answer_false.setAttribute("name", "option_number_" + my_option_number +
                                          "_" + specific_option_number);
    if (choice && choice['is_correct'] == false) {
      answer_false.checked = true;
    } else if (!choice) {
      answer_false.checked = true;
    }

    const user_input_field = document.createElement("textarea");
    user_input_field.required = true;
    user_input_field.setAttribute("cols", "50");
    user_input_field.setAttribute("rows", "2");
    user_input_field.setAttribute("name", "option_text_number_" +
                                              my_option_number + "_" +
                                              specific_option_number);
    user_input_field.setAttribute("placeholder", "option text ...");
    if (choice) {
      user_input_field.innerText = choice['option_text'];
    }
    fieldset.appendChild(user_input_field);
  }

  add_child_option(event, option = null) {
    if (event) {
      event.preventDefault();
    }
    // create div with preceding text and one option
    const parent = document.getElementById("options_table");
    const wrapper = document.createElement("div");
    parent.appendChild(wrapper);
    const preceding_text_input_field = document.createElement("textarea");
    wrapper.appendChild(preceding_text_input_field);
    const new_option_number = option_number;
    preceding_text_input_field.setAttribute("cols", "50");
    preceding_text_input_field.setAttribute("rows", "2");
    preceding_text_input_field.setAttribute(
        "name", "preceding_text_boolean_choice_" + new_option_number);
    preceding_text_input_field.setAttribute(
        "placeholder", "Here goes preceding text(can be left blank)");
    if (option) {

      preceding_text_input_field.innerText = option['preceding_text'];
    }
    const input_table = document.createElement('table');
    wrapper.appendChild(input_table);
    const indicator = document.createElement("tr");
    input_table.appendChild(indicator);
    const true_indicator = document.createElement("td");
    indicator.appendChild(true_indicator);
    true_indicator.innerText = "true/false";
    if (option) {
      for (var choice of option['data']) {
        this.add_option_boolean_choice(null, input_table, new_option_number,
                                       choice = choice);
      }
    } else {
      this.add_option_boolean_choice(null, input_table, new_option_number);
    }
    var self = this;
    option_number++;

    var add_boolean_choice_button = document.createElement("button");
    wrapper.appendChild(add_boolean_choice_button);
    add_boolean_choice_button.innerText = "+";
    // console.log(new_option_number);
    add_boolean_choice_button.onclick = function(event) {
      self.add_option_boolean_choice(event, input_table, new_option_number);
    };
  }
}

class WriteIn {
  constructor(event, button) {
    var self = this;
    button.onclick = function(event) { self.add_child_option(event) };
  }

  add_child_option(event, option = null) {
    if (event) {
      event.preventDefault();
    }
    const wrapper = document.getElementById("options_table");
    const table_row = document.createElement("tr");
    wrapper.appendChild(table_row);
    const fieldset = document.createElement("fieldset");
    table_row.appendChild(fieldset);

    const preceding_text = document.createElement("textarea");
    fieldset.appendChild(preceding_text);
    preceding_text.setAttribute("cols", "50");
    preceding_text.setAttribute("rows", "2");
    preceding_text.setAttribute("name",
                                "preceding_text_write_in_" + option_number);
    preceding_text.setAttribute("placeholder",
                                "preceding text ...(can be left blank)");

    const user_input_field = document.createElement("input");
    fieldset.appendChild(user_input_field);
    user_input_field.setAttribute("type", "text");
    user_input_field.setAttribute("id", "option_number_" + option_number);
    user_input_field.setAttribute("name", "option_number_" + option_number);
    user_input_field.setAttribute("placeholder", "expected answer");
    user_input_field.required = true;

    if (option) {
      console.log(option['data']['correct_answer']);
      preceding_text.innerText = option['preceding_text'];
      user_input_field.value = option['data']['correct_answer'];
    }
    option_number++;
  }
}
class OneFromMany {
  // thi si exactly why you use oop from the start. I should've made ONE CLASS
  // which then is being used as idk what it's called by the rest. Such a bad
  // design todo:redo classes
  constructor(event, button) {
    var self = this;
    button.onclick = function(event) { self.add_child_option(event) };
  }
  add_select_option(event, parent_element, select_element, my_option_number,
                    option = null, is_correct = null) {
    if (event) {
      event.preventDefault();
    }
    const example_option = document.createElement("option");
    select_element.appendChild(example_option);
    const table_row = document.createElement("tr");
    parent_element.appendChild(table_row);
    const fieldset = document.createElement("fieldset");
    table_row.appendChild(fieldset);

    const private_option_num = parent_element.children.length - 1;
    const is_correct_field = document.createElement("input");
    is_correct_field.required = true;
    fieldset.appendChild(is_correct_field);
    is_correct_field.setAttribute("type", "radio");
    is_correct_field.setAttribute("name",
                                  "correct_option_index_" + my_option_number);
    is_correct_field.setAttribute("value", private_option_num);

    const user_input_field = document.createElement("input");
    fieldset.appendChild(user_input_field);
    user_input_field.setAttribute("type", "text");
    user_input_field.setAttribute("placeholder", "option text ...");
    user_input_field.setAttribute("id", "option_number_" + my_option_number +
                                            "_" + private_option_num);
    user_input_field.setAttribute("name", "option_number_" + my_option_number +
                                              "_" + private_option_num);
    user_input_field.required = true;
    user_input_field.onchange = function() {
      let newText = user_input_field.value;
      example_option.innerText = newText;
    };
    if (option) {
      if (is_correct == private_option_num) {
        is_correct_field.checked = true;
      }
      user_input_field.value = option;
    }
  }
  add_child_option(event, select = null) {
    if (event) {
      event.preventDefault();
    }
    const new_option_number = option_number;
    const wrapper = document.getElementById("options_table");
    const table_row = document.createElement("tr");
    wrapper.appendChild(table_row);

    table_row.classList.add("small_border");
    const preceding_text_input_field = document.createElement("textarea");
    table_row.appendChild(preceding_text_input_field);
    preceding_text_input_field.setAttribute("cols", "50");
    preceding_text_input_field.setAttribute("rows", "2");
    preceding_text_input_field.setAttribute(
        "name", "preceding_text_one_from_many_" + new_option_number);
    preceding_text_input_field.setAttribute(
        "placeholder", "Here goes preceding text(can be left blank)");
    const example_selector = document.createElement("select");
    table_row.appendChild(example_selector);
    const hint_text = document.createElement("span");
    table_row.appendChild(hint_text);
    hint_text.innerText = "How the selector will look like in test";
    const options_wrapper = document.createElement("div");
    table_row.appendChild(options_wrapper);
    const new_select_option_button = document.createElement("button");
    new_select_option_button.classList.add(
        "border",
        "rounded-md",
        "w-10",
        "border-black",
    );
    table_row.appendChild(new_select_option_button);
    new_select_option_button.innerText = "+";
    var self = this;
    new_select_option_button.onclick = function(event) {
      self.add_select_option(event, options_wrapper, example_selector,
                             new_option_number);
    };
    if (select) {
      for (var option of select['data']['option_array']) {
        this.add_select_option(event, options_wrapper, example_selector,
                               new_option_number, option,
                               select['data']['correct_option']);
      }
      preceding_text_input_field.value = select['preceding_text'];
    } else {
      this.add_select_option(event, options_wrapper, example_selector,
                             new_option_number);
    }
    option_number++;
  }
}

function display_option(event, used_class, options = null, input_button,
                        options_table) {
  var myClass = new used_class(event, input_button);
  if (options) {
    for (var option of options) {
      myClass.add_child_option(event, option);
    }
  } else {
    myClass.add_child_option(event);
  }
}

function process_option_type(event, user_option, question = null) {
  // reset the number counter
  option_number = 0;
  // remove every previous option
  const question_type_user_input_wrapper =
      document.getElementById("question_type_user_input_wrapper");
  question_type_user_input_wrapper.innerHTML = "";
  const options_table = document.createElement("table");
  question_type_user_input_wrapper.appendChild(options_table);
  options_table.setAttribute("id", "options_table");
  options_table.setAttribute("name", "options_table");
  // I could just change the onClick attribute to be more clean but I kinda
  // don't care that much
  const option_input_creator = document.createElement("button");

  option_input_creator.classList.add("border", "rounded-md", "w-10",
                                     "border-black", "!bg-green-200");
  // console.log(option_input_creator);
  option_input_creator.setAttribute("type", "button");
  option_input_creator.innerHTML = "+";
  question_type_user_input_wrapper.appendChild(option_input_creator);

  var options = !question ? null : question['options'];
  if (user_option == "boolean-choice") {
    display_option(event, BooleanChoiceClass, options, option_input_creator,
                   options_table, options_table);
    // var boolean_choice_object =
    //     new BooleanChoiceClass(event, option_input_creator, options_table);

    // if (question) {

    //  for (var option of question['options']) {
    //    boolean_choice_object.add_child_option(option = option);
    //  }
    //} else {
    //  boolean_choice_object.add_child_option();
    //}

  } else if (user_option == "write-in") {

    display_option(event, WriteIn, options, option_input_creator, options_table,
                   options_table);
    // var write_in_object =
    //     new WriteIn(event, option_input_creator, options_table)
    // write_in_object.add_child_option(event)
  } else if (user_option == "multiple-choice") {
    // function that initiates
    display_option(event, MultipleChoice, options, option_input_creator,
                   options_table, options_table);
    // var multiple_choice_object =
    //     new MultipleChoice(event, option_input_creator, options_table)
    // multiple_choice_object.add_child_option();
    //  option_input_creator.onclick = add_child_option;
    //  add_child_option(event);
  } else if (user_option == "one-from-many") {
    // function that initiates
    display_option(event, OneFromMany, options, option_input_creator,
                   options_table, options_table);
    // var one_from_many_object =
    //     new OneFromMany(event, option_input_creator, options_table)
    // one_from_many_object.add_child_option()
  } else if (user_option == 'open-answer') {
    display_option(event, OpenAnswer, options, option_input_creator,
                   options_table, options_table);
    // var open_answer = new OpenAnswer(event, option_input_creator,
    // options_table) open_answer.add_child_option()
  }
}

function load_input_field(
    question = null,
    event) { // question which is inserted when editing question
  if (event) {
    event.preventDefault();
  }
  // load and clear everything
  const form_element = document.getElementById("user-list");
  // form_element.innerHTML = "";
  //  apend field for question text input
  const question_name_wrapper = document.createElement("div");
  question_name_wrapper.setAttribute("class", "block");
  const question_name_input = document.createElement("textarea");
  question_name_input.required = true;
  question_name_input.setAttribute("rows", "4");
  question_name_input.setAttribute("cols", "50");
  question_name_input.setAttribute("name", "question_text");
  question_name_input.setAttribute("placeholder",
                                   "Question : who has dog with 4 eyes?");
  if (question) {
    question_name_input.innerText = question['question_text'];
  }
  question_name_wrapper.appendChild(question_name_input);
  form_element.appendChild(question_name_wrapper);
  // append field for optional user image
  if (!document.getElementById('user_image')) {
    const has_image = document.createElement("div");
    has_image.innerHTML =
        '<input id="user_image" name="user_image" type="file" onChange="display_input_image()" /><br><img id="display_image" src="" />';
    form_element.appendChild(has_image);
  }
  // apend options select element
  const question_type_selector_wrapper = document.createElement("div");
  form_element.append(question_type_selector_wrapper);
  const question_type_selector = document.createElement("select");
  question_type_selector_wrapper.appendChild(question_type_selector);
  question_type_selector.setAttribute("id", "question_type_selector");
  question_type_selector.setAttribute("onChange",
                                      "process_option_type(event,this.value)");
  const question_type_user_input_wrapper = document.createElement("div");
  question_type_user_input_wrapper.classList.add("main_table");
  question_type_user_input_wrapper.setAttribute(
      "id", "question_type_user_input_wrapper");
  form_element.append(question_type_user_input_wrapper);
  // we could do it by some php x sql bullshit where we receive all the enum
  // options but idc i want it to work + it wouldn't work half the time bcs
  // webhosting issues
  // append options to selector
  const question_types = [
    "boolean-choice", "write-in", "multiple-choice", "one-from-many",
    "open-answer"
  ];
  for (const question_type of question_types) {
    const question_type_option = document.createElement("option");
    question_type_option.setAttribute("value", question_type);
    question_type_option.innerHTML = question_type;
    question_type_selector.appendChild(question_type_option);
  }
  if (question) {
    question_type_selector.value =
        question['options'][0]['option_type'].replaceAll('_', '-');
  }

  // append default option(boolean-choice) to form
  if (question) {

    process_option_type(
        event, question['options'][0]['option_type'].replaceAll('_', '-'),
        question = question);
  } else {
    process_option_type(event, "boolean-choice");
  }
  form_element.appendChild(document.createElement("br"));
  const explanation_input = document.createElement("textarea");
  form_element.appendChild(explanation_input);
  explanation_input.setAttribute("rows", "4");
  if (question) {
        explanation_input.innerText = question['explanation_text'];
    }
    explanation_input.setAttribute("cols", "50");
    explanation_input.setAttribute("name", "question_explanation");
    explanation_input.setAttribute("placeholder",
        "Explenation:No one has dog with 4 eyes because dogs have 2 eyes");
    form_element.appendChild(document.createElement("br"));

    // append final button
    const test_submit_button = document.createElement("button");
    test_submit_button.setAttribute("type", "submit");
    test_submit_button.setAttribute("name", "submit");
    test_submit_button.innerHTML = "submit";
    test_submit_button.classList.add("border", "border-black");
    form_element.appendChild(test_submit_button);
}
