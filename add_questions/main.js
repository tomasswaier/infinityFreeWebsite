function display_input_image() {
  // displays  selected user image to user for confirmation/better ux
  var image = document.getElementById("user_image").files[0];
  var reader = new FileReader();
  reader.onload =
      function(
          e) { document.getElementById("display_image").src = e.target.result; }

      reader.readAsDataURL(image);
}
var option_number = 1;
class MultipleChoice {
  constructor(event, button, options_table) {
    var self = this;
    this.table = options_table;
    this.number_of_columns = 0;
    this.initialize_column_row();
    this.row_number = 0;
    var self = this;
    button.onclick = function(
        event) { self.add_child_type_multiple_choice(event) };
  }
  initialize_column_row() {
    const table_head = document.createElement("thead");
    this.table.appendChild(table_head);
    const column_names_row = document.createElement("tr");
    column_names_row.setAttribute("id", "column_names_row");
    column_names_row.classList.add("block");
    table_head.appendChild(column_names_row);
    const button_add_columns = document.createElement("button");
    button_add_columns.setAttribute("type", "button");
    button_add_columns.setAttribute("id", "button_add_columns");
    button_add_columns.innerHTML = "+";
    const self = this;
    button_add_columns.onclick = function(
        event) { self.add_table_column(event) };
    column_names_row.appendChild(button_add_columns)
  }
  add_child_type_multiple_choice(event) {
    // adds one row with this.number_of_columns number of radio buttons + input
    if (event) {
      event.preventDefault();
    }
    const button = document.getElementById("button_add_columns")
    if (button) {
      // if this is first time we are adding rows this removes the button for
      // adding columns + adds blank cell
      button.remove()
      const column_row = document.getElementById("column_names_row");
      const empty_cell = document.createElement("td");
      empty_cell.classList.add("cell")
      column_row.appendChild(empty_cell)
    }
    const new_options_row = document.createElement("tr");
    new_options_row.classList.add("even_margin");
    this.table.appendChild(new_options_row);
    for (let i = 0; i < this.number_of_columns; i++) {
      const option_wrapper = document.createElement("td");
      option_wrapper.classList.add("cell", "even_margin")
      new_options_row.appendChild(option_wrapper)
      const radio_button_option = document.createElement("input");
      radio_button_option.setAttribute("type", "radio");
      radio_button_option.setAttribute("value", i);
      radio_button_option.setAttribute(
          "name", "correct_option_multiple_choice_" + this.row_number);
      option_wrapper.appendChild(radio_button_option);
    }
    const input_wrapper = document.createElement("td")
    new_options_row.appendChild(input_wrapper);
    input_wrapper.classList.add("cell")
    const input_field = document.createElement("input");
    input_wrapper.appendChild(input_field)
    input_field.setAttribute("id", "option_number_" + this.row_number);
    input_field.setAttribute("name", "option_number_" + this.row_number);
    input_field.classList.add("cell");
    this.row_number++;
  }
  add_table_column(event) {
    // collumns at the top providing description for columns
    if (event) {
      event.preventDefault();
    }
    const new_column = document.createElement("td");

    const column_row = document.getElementById("column_names_row");
    column_row.appendChild(new_column);
    new_column.setAttribute("id", "column_name_" + this.number_of_columns);
    new_column.classList.add("cell");
    const data_fieldset = document.createElement("fieldset")

    new_column.appendChild(data_fieldset);
    const column_input_area = document.createElement("input");
    data_fieldset.appendChild(column_input_area)
    column_input_area.setAttribute("id", "multiple_choice_option_name_" +
                                             this.number_of_columns);
    column_input_area.setAttribute("name", "multiple_choice_option_name_" +
                                               this.number_of_columns);
    column_input_area.setAttribute("type", "text");

    this.number_of_columns++;
  }
}
class BooleanChoiceClass {
  constructor(event, button, options_table) {
    const indicator = document.createElement("tr");
    options_table.appendChild(indicator);
    const true_indicator = document.createElement("td");
    true_indicator.innerText = "true/false";
    indicator.appendChild(true_indicator);
    var self = this;
    button.onclick = function(
        event) { self.add_child_type_boolean_choice(event) };
  }
  add_child_type_boolean_choice(event) {
    if (event) {
      event.preventDefault();
    }
    const wrapper = document.getElementById("options_table");
    const table_row = document.createElement("tr");
    wrapper.appendChild(table_row);
    const fieldset = document.createElement("fieldset");
    table_row.appendChild(fieldset);

    const answer_true = document.createElement("input");
    answer_true.setAttribute("type", "radio");
    answer_true.setAttribute("value", "true");
    answer_true.setAttribute("id",
                             "correct_option_boolean_choice_" + option_number);
    answer_true.setAttribute("name",
                             "correct_option_boolean_choice_" + option_number);
    fieldset.appendChild(answer_true);
    const answer_false = document.createElement("input");
    answer_false.setAttribute("type", "radio");
    answer_false.setAttribute("value", "false");
    answer_false.setAttribute("id",
                              "correct_option_boolean_choice_" + option_number);
    answer_false.setAttribute("name",
                              "correct_option_boolean_choice_" + option_number);
    answer_false.checked = true;
    // answer_false.checked = true;

    fieldset.appendChild(answer_false);
    const user_input_field = document.createElement("textarea");
    user_input_field.required = true;
    user_input_field.setAttribute("cols", "50");
    user_input_field.setAttribute("rows", "2");
    user_input_field.setAttribute("name", "option_number_" + option_number);
    user_input_field.setAttribute("placeholder", "option text ...");
    fieldset.appendChild(user_input_field);
    option_number++;
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
    const user_input_field = document.createElement("input");
    user_input_field.setAttribute("type", "text");
    user_input_field.setAttribute("id", "option_number_" + option_number);
    user_input_field.setAttribute("name", "option_number_" + option_number);
    user_input_field.setAttribute("placeholder", "option text ...");
    user_input_field.required = true;
    fieldset.appendChild(user_input_field);
    option_number++;
  }
}
class OneFromMany {
  constructor(event, button) {
    var self = this;
    button.onclick = function(event) { self.create_new_select_tag(event) };
  }
  add_child_type_one_from_many(event, parent_element, select_element) {
    if (event) {
      event.preventDefault();
    }
    const example_option = document.createElement("option");
    select_element.appendChild(example_option);
    const table_row = document.createElement("tr");
    parent_element.appendChild(table_row);
    const fieldset = document.createElement("fieldset");
    table_row.appendChild(fieldset);
    const user_input_field = document.createElement("input");
    fieldset.appendChild(user_input_field);
    user_input_field.setAttribute("type", "text");
    user_input_field.setAttribute("placeholder", "option text ...");
    user_input_field.setAttribute("id", "option_number_" + option_number);
    user_input_field.setAttribute("name", "option_number_" + option_number);
    user_input_field.required = true;
    user_input_field.onchange = function() {
      let newText = user_input_field.value;
      example_option.innerText = newText;
    };

    const is_correct_field = document.createElement("input");
    fieldset.appendChild(is_correct_field);
    is_correct_field.setAttribute("type", "checkbox");
    is_correct_field.setAttribute("id", "correct_option_one_from_many_" +
                                            option_number);
    is_correct_field.setAttribute("name", "correct_option_one_from_many_" +
                                              option_number);
    option_number++;
  }
  create_new_select_tag(event) {
    if (event) {
      event.preventDefault();
    }
    const wrapper = document.getElementById("options_table");
    const table_row = document.createElement("tr");
    wrapper.appendChild(table_row);
    table_row.classList.add("small_border");
    const example_selector = document.createElement("select");
    table_row.appendChild(example_selector);
    const hint_text = document.createElement("span");
    table_row.appendChild(hint_text);
    hint_text.innerText = "How the selector will look like in test";
    const options_wrapper = document.createElement("div");
    table_row.appendChild(options_wrapper);
    const new_select_option_button = document.createElement("button");
    table_row.appendChild(new_select_option_button);
    new_select_option_button.innerText = "+";
    var self = this;
    new_select_option_button.onclick = function(event) {
      self.add_child_type_one_from_many(event, options_wrapper,
                                        example_selector)
    };
    this.add_child_type_one_from_many(event, options_wrapper, example_selector);
  }
}

function display_option_type(event, user_option) {
  // reset the number counter
  option_number = 1;
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
  option_input_creator.setAttribute("type", "button");
  option_input_creator.innerHTML = "+";
  question_type_user_input_wrapper.appendChild(option_input_creator);
  if (user_option == "boolean-choice") {

    var boolean_choice_object =
        new BooleanChoiceClass(event, option_input_creator, options_table)
    boolean_choice_object.add_child_type_boolean_choice(event)

  } else if (user_option == "write-in") {
    var write_in_object = new WriteIn(event, option_input_creator)
    write_in_object.add_child_type_write_in(event)
  } else if (user_option == "multiple-choice") {
    // function that initiates
    var multiple_choice_object =
        new MultipleChoice(event, option_input_creator, options_table)
    multiple_choice_object.add_table_column()
    // option_input_creator.onclick = add_child_type_multiple_choice;
    // add_child_type_write_in(event);
  } else if (user_option == "one-from-many") {
    // function that initiates
    var one_from_many_object =
        new OneFromMany(event, option_input_creator, options_table)
    one_from_many_object.create_new_select_tag()
    // option_input_creator.onclick = add_child_type_multiple_choice;
    // add_child_type_write_in(event);
  }
}

function load_input_field(event) {
  if (event) {
    event.preventDefault();
  }
  // load and clear everything
  const form_element = document.getElementById("user-list");
  form_element.innerHTML = "";
  // apend field for question text input
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
  // append final button
  const test_submit_button = document.createElement("input");
  test_submit_button.setAttribute("onclick", "send_form_data(event)");
  test_submit_button.setAttribute("type", "submit");
  test_submit_button.setAttribute("name", "submit");
  test_submit_button.setAttribute("value", "submit");
  document.body.appendChild(test_submit_button);
}

function send_form_data(event) {
  const path_hash = String(window.location).split("#");
  if (path_hash.length < 4) {
    document.body.append("wrong hash");
    return;
  }

  event.preventDefault();
  const form = document.querySelector("#user-list");
  // console.log(form);
  const form_data = new FormData(form);
  // add submit type to work with previously created php code
  form_data.append("submit", "1");
  console.log(path_hash)
  form_data.append("token", path_hash[3]);
  form_data.append("test_number", path_hash[2]);
  console.log(form_data);
  $.ajax({
  url: "send_data.php", method: "POST", data: (form_data), processData: false,
      contentType: false, success: function(data) {
      console.log("success");
      console.log(data);
      const server_response = document.createElement("span");
      server_response.innerHTML = data;
      document.body.appendChild(server_response);
    },
    error: function() {
      console.log("error");
      /*alert(
          "reload the page to fix Error fetching data pls report it to me
         Anča(.MaryAnn) id:" + test_id + " num:" + number_of_questions);
          */
    },

  });
}
