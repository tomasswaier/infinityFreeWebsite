// I am not changing kinda dumb logic begind option_number_x_y to
// option_number_x[] because it's already working and I don't see reason why . I
// konw it's an option just don't see much use in it
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
class MultipleChoice {
  constructor(event, button, options_table) {
    var self = this;
    this.parent = options_table;
    this.column_number = 0;
    this.initialize_column_row();
    this.newOptionFieldButton = button;
    button.setAttribute("title", "add row");
    button.onclick = function(
        event) { self.add_child_type_multiple_choice(event) };
  }
  add_child_type_multiple_choice(event) {
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
    this.parent.appendChild(wrapper);
    this.table.appendChild(this.initialize_column_row(new_option_number));
    this.add_column(event, new_option_number, this.columns);
    this.add_column(event, new_option_number, this.columns);
    const button_add_row = document.createElement("button");
    this.table.parentElement.appendChild(button_add_row);
    button_add_row.onclick = function(
        event) { self.add_row(event, table, new_option_number) };
    button_add_row.innerText = "+";
    button_add_row.setAttribute('title', 'Add row');

    this.parent.parentElement.insertBefore(document.createElement("br"),
                                           this.newOptionFieldButton);
    option_number += 1;
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

  add_row(event, parent, option_number) {
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
  }
  add_column(event, new_option_number, column_row) {
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
    column_input_area.setAttribute(
        "name", "column_number_" + new_option_number + "_" + column_number);

    column_input_area.classList.add("w-30");
    column_input_area.setAttribute("type", "text");

    this.column_number++;
  }
}
class BooleanChoiceClass {
  constructor(event, button, parent) {
    var self = this;
    self.parent = parent;
    self.createNewButton = button;
    button.onclick = function(
        event) { self.add_child_type_boolean_choice(event) };
  }
  add_option_boolean_choice(event, parent, my_option_number) {
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
    answer_false.checked = true;
    // answer_false.checked = true;

    const user_input_field = document.createElement("textarea");
    user_input_field.required = true;
    user_input_field.setAttribute("cols", "50");
    user_input_field.setAttribute("rows", "2");
    user_input_field.setAttribute("name", "option_text_number_" +
                                              my_option_number + "_" +
                                              specific_option_number);
    user_input_field.setAttribute("placeholder", "option text ...");
    fieldset.appendChild(user_input_field);
  }

  add_child_type_boolean_choice(event) {
    if (event) {
      event.preventDefault();
    }
    // create div with preceding text and one option
    const wrapper = document.createElement("div");
    this.parent.appendChild(wrapper);
    const preceding_text_input_field = document.createElement("textarea");
    wrapper.appendChild(preceding_text_input_field);
    const new_option_number = option_number;
    preceding_text_input_field.setAttribute("cols", "50");
    preceding_text_input_field.setAttribute("rows", "2");
    preceding_text_input_field.setAttribute(
        "name", "preceding_text_boolean_choice_" + new_option_number);
    preceding_text_input_field.setAttribute(
        "placeholder", "Here goes preceding text(can be left blank)");
    const input_table = document.createElement('table');
    wrapper.appendChild(input_table);
    const indicator = document.createElement("tr");
    input_table.appendChild(indicator);
    const true_indicator = document.createElement("td");
    indicator.appendChild(true_indicator);
    true_indicator.innerText = "true/false";
    this.add_option_boolean_choice(null, input_table, new_option_number);
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
    button.onclick = function(event) { self.add_child_type_write_in(event) };
  }

  add_child_type_write_in(event) {
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
    option_number++;
  }
}
class OneFromMany {
  // thi si exactly why you use oop from the start. I should've made ONE CLASS
  // which then is being used as idk what it's called by the rest. Such a bad
  // design todo:redo classes
  constructor(event, button) {
    var self = this;
    button.onclick = function(event) { self.create_new_select_tag(event) };
  }
  add_child_type_one_from_many(event, parent_element, select_element,
                               my_option_number) {
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
  }
  create_new_select_tag(event) {
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
      self.add_child_type_one_from_many(event, options_wrapper,
                                        example_selector, new_option_number);
    };
    this.add_child_type_one_from_many(event, options_wrapper, example_selector,
                                      new_option_number);
    option_number++;
  }
}

function display_option_type(event, user_option) {
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
  if (user_option == "boolean-choice") {

    var boolean_choice_object =
        new BooleanChoiceClass(event, option_input_creator, options_table);
    boolean_choice_object.add_child_type_boolean_choice();

  } else if (user_option == "write-in") {
    var write_in_object =
        new WriteIn(event, option_input_creator, options_table)
    write_in_object.add_child_type_write_in(event)
  } else if (user_option == "multiple-choice") {
    // function that initiates
    var multiple_choice_object =
        new MultipleChoice(event, option_input_creator, options_table)
    multiple_choice_object.add_child_type_multiple_choice();
    // option_input_creator.onclick = add_child_type_multiple_choice;
    // add_child_type_write_in(event);
  } else if (user_option == "one-from-many") {
    // function that initiates
    var one_from_many_object =
        new OneFromMany(event, option_input_creator, options_table)
    one_from_many_object.create_new_select_tag()
  }
}

function load_input_field(event) {
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
  question_name_wrapper.appendChild(question_name_input);
  form_element.appendChild(question_name_wrapper);
  // append field for optional user image
  const has_image = document.createElement("div");
  has_image.innerHTML =
      '<input id="user_image" name="user_image" type="file" onChange="display_input_image()" /><br><img id="display_image" src="" />';
  form_element.appendChild(has_image);
  // apend options select element
  const question_type_selector_wrapper = document.createElement("div");
  form_element.append(question_type_selector_wrapper);
  const question_type_selector = document.createElement("select");
  question_type_selector_wrapper.appendChild(question_type_selector);
  question_type_selector.setAttribute("id", "question_type_selector");
  question_type_selector.setAttribute("onChange",
                                      "display_option_type(event,this.value)");
  const question_type_user_input_wrapper = document.createElement("div");
  question_type_user_input_wrapper.classList.add("main_table");
  question_type_user_input_wrapper.setAttribute(
      "id", "question_type_user_input_wrapper");
  form_element.append(question_type_user_input_wrapper);
  // we could do it by some php x sql bullshit where we receive all the enum
  // options but idc i want it to work + it wouldn't work half the time bcs
  // webhosting issues
  // append options to selector
  const question_types =
      [ "boolean-choice", "write-in", "multiple-choice", "one-from-many" ];
  for (const question_type of question_types) {
    const question_type_option = document.createElement("option");
    question_type_option.setAttribute("value", question_type);
    question_type_option.innerHTML = question_type;
    question_type_selector.appendChild(question_type_option);
  }

  // append default option(boolean-choice) to form
  display_option_type(event, "boolean-choice");
  form_element.appendChild(document.createElement("br"));
  const explenation_input = document.createElement("textarea");
  form_element.appendChild(explenation_input);
    explenation_input.setAttribute("rows", "4");
    explenation_input.setAttribute("cols", "50");
    explenation_input.setAttribute("name", "question_explanation");
    explenation_input.setAttribute("placeholder",
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
