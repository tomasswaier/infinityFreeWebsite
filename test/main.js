/* ngl I feel bad about putting it all in one file but like how else is it
 * supposed to look like ? I dont wanna have 2 functions in 2 files thats icky
 * (the above was written before addition of another 6 functions :p
 * :p sry for variable names being inconsistant
 */
var data;

function load_questions(event) {
  if (event) {
    event.preventDefault();
  }
  var number_of_questions;
  var test_id;
  const pathHash = String(window.location).split("#");
  const isNumeric = (string) => string == Number.parseInt(string)
  if (pathHash[3] && isNumeric(pathHash[2]) && isNumeric(pathHash[3])) {
    // load_questions();
    number_of_questions = document.getElementById("number_of_questions").value;
    test_id = pathHash[2]
  }
  else {

    console.log("no hash parameters given");
    number_of_questions = document.getElementById("number_of_questions").value;
    var test_id_object = document.getElementById("test_selector");
    if (test_id_object) {
      // console.log(test_id_object);
    }
    test_id = test_id_object.value;
    // const index_of_option = test_id_object.getAttribute("name");
    // console.log("meow" + index_of_option);
    window.location.hash =
        "#" + test_id_object.options[test_id_object.selectedIndex].text + "#" +
        test_id + "#" + number_of_questions;

    // console.log(test_id, number_of_questions);
  }
  $.ajax({
    url : "get_data.php",
    method : "POST",
    data : ({test_id : test_id, number_of_questions : number_of_questions}),
    dataType : 'json',
    success : function(data) { display_questions(data); },
    error : function() {
      /*alert(
          "reload the page to fix Error fetching data pls report it to me
         Anča(.MaryAnn) id:" + test_id + " num:" + number_of_questions);
          */
    },

  });
  let selector = document.getElementById("test_selector")
  if (selector) {
    selector.remove()
  }
}

// part for confirming
function convert_to_array(data) {
  /*
   * I really am not sure why this exists ... can't i iterate over objects the
   * same way as array ? why would I need this
   */
  if (typeof data === "object" && !Array.isArray(data) && data) {
    data = Object.entries(data);
  }
  if (data && typeof data === "object") {
    for (let index = 0; index < data.length; index += 1) {
      const subsection = data[index];
      data[index] = convert_to_array(subsection);
    }
  }
  return data;
}

function extract_correct_answers(data) {
  /*
   * Iterate over every option in the multidimensional array that we received
   * and append the option along with it's value to the result array note :
   * adding support here in case the answer is an array of strings is not needed
   * since im not looking at the value but instead just blindly appending where
   * value should be
   */
  // console.log(data);
  var result = [];
  for (let j = 0; j < data.length; j++) {
    const question_wraper = data[j];

    //[5] is always the ['options'] section of the array (ik it's icky)

    let options_object = question_wraper.options;
    for (let i = 0; i < options_object.length; i++) {
      const option = options_object[i];
      // console.log("meow");
      //  when converting array of objects of arrays of objects of arrays to an
      //  array something went wrong this fixes it :3
      if (!option || typeof option == "string") {
        continue;
      }
      // in most cases you would only need the option[2][1] but in write in
      // questions you need [1][1]
      result.push([ option.options_id, option.is_correct, option.option_text ]);
    }
  }
  // console.log(result);
  return result;
}

function get_correct_answer(data, id, type) {
  /*
   * I will not be sorting the array and doing binary search because im not a
   * nerd
   * note : add binary search
   * 0:id
   * 1:value
   * 2:text val
   */
  for (const id_option of data) {
    if (id_option[0] == id) {
      if (type == "radio") {
        return id_option[1];
      } else if (type == "text") {
        return id_option[2];
      } else if (type == "option") {
        return id_option[1];
      } else {
        return false;
      }
    }
  }
  console.error("ERROR ID NOT FOUND :" + id);
  return undefined;
}

function is_correct(id, value, type, input_value, correct_values) {
  var correct_value = get_correct_answer(correct_values, id, type);
  // console.log(id, value, type, input_value, correct_value)
  if (type == "radio" || type == "option") {
    if (correct_value == value && input_value == true) {
      return true;
    } else {
      return undefined;
    }
  } else if (type == 'text') {
    // if the answer can be multiple options then devide them by column
    correct_value = correct_value.split(";");
    for (const correct_option of correct_value) {
      // console.log(correct_option, input_value);
      if (correct_option === input_value) {
        return true;
      }
    }
    return false;
  }
  console.log("undefined type option , cannot evaluate ");
  return undefined;
}

function function_input(input) {
  // ... it does look funny doesnt it
  return input;
}

function function_select(input) { return input.selectedOptions[0]; }

function evaluate_elements(inputs, correct_values, select_function) {
  // Iterate through the inputs and log their values and IDs/names
  for (var input of inputs) {
    input = select_function(input);
    // console.log(input);
    //  type is important to check what value it's expectin
    const id = input.id;
    const id_number = parseInt(id.split("_")[1]);
    const value = input.value;
    if (value == 'submit') {
      continue;
    }
    var type = input.type;
    if (type == undefined) {
      type = input.localName;
    }
    var input_value;
    if (type == "radio") {
      input_value = input.checked;
    } else if (type == "text") {
      input_value = input.value;
    } else if (type == "option") {
      input_value = 1;
    }

    // console.log(id_number, value, type, input_value);
    if (!id_number) {
      console.log("Element Doesnt Exist");
      console.log(`ID: ${id_number}, Value: ${value}, Type: ${type}, Checked: ${
          input_value}`);
      continue;
    }

    var parent = input.parentElement.parentElement;
    var classes = parent.getAttribute("class");
    if (is_correct(id_number, value, type, input_value, correct_values)) {
      if (classes && classes.includes("red_background")) {
      }

      parent.setAttribute("class", "green_background");
      // console.log("correct");
    } else {
      if (!classes || !classes.includes("green_background")) {
        parent.setAttribute("class", "red_background");
        // console.log("incorrect");
        if (type == "text") {
          const correct_value_hint = document.createElement("span");
          correct_value_hint.innerText =
              get_correct_answer(correct_values, id_number, type);
          input.parentElement.appendChild(correct_value_hint);
        }
      }
    }
  };
}

function submit_form(event) {
  event.preventDefault();
  if (data == undefined) {
    console.log("Data wasn't loaded");
  } else {
    // console.log(data);
    //  data = convert_to_array(data);
    var correct_values = extract_correct_answers(data);
    console.log(correct_values);

    // meow
    const form = document.getElementById(
        "user-list"); // or "main_form" for the main form

    // Get all input elements in the form
    var inputs = form.querySelectorAll("input");
    evaluate_elements(inputs, correct_values, function_input);
    inputs = form.querySelectorAll("select");
    // console.log(inputs);
    evaluate_elements(inputs, correct_values, function_select);

    const pathHash = String(window.location).split("#");
    // I will assume that no one is playing around with the url
    var test_id = pathHash[2];
    if (!test_id) {
      test_id = 0
    }

    $.ajax({
      url : "submit.php",
      method : "POST",
      data : ({test_id : test_id}),
      success : function() { console.log("Data submited successfully"); },
      error : function() { console.log("Data not submite successfully"); },

    });
  }
}
class MultipleChoice {
  constructor() { this.number_of_columns = 0; }
  initiate_top_row(column_names, parent_element) {
    var columns = column_names.split(";");
    const table_row = document.createElement("tr");
    table_row.appendChild(document.createElement("td"))
    parent_element.appendChild(table_row)
    columns.forEach(element => {
      const text_wrapper = document.createElement("td");
      table_row.appendChild(text_wrapper)
      const column_text = document.createElement("span");
      column_text.innerText = element;
      text_wrapper.appendChild(column_text);
      this.number_of_columns++;
    });
  }
  add_row(option, parent_element) {
    const table_row = document.createElement("tr");
    parent_element.appendChild(table_row);
    const text_row_indicator_wrapper = document.createElement("td");
    table_row.appendChild(text_row_indicator_wrapper);
    const text_row_indicator = document.createElement("span");
    text_row_indicator.innerText = option.option_text;
    text_row_indicator_wrapper.appendChild(text_row_indicator);
    for (let index = 0; index < this.number_of_columns; index++) {
      const button_wrapper = document.createElement("td");
      table_row.appendChild(button_wrapper);
      const radio_button = document.createElement("input");
      button_wrapper.appendChild(radio_button);
      radio_button.setAttribute("type", "radio");
      radio_button.setAttribute("id", "option_" + option.options_id);
      radio_button.setAttribute("name", "option_" + option.options_id);
      radio_button.setAttribute("value", index);
    }
  }
  display_question(data, parent_element) {
    // question text
    const table_question_wrapper = document.createElement("div");
    parent_element.append(table_question_wrapper);
    const question_text = document.createElement('pre');

    table_question_wrapper.append(question_text);
    question_text.textContent = data.question;
    if (data.question_image != "NULL") {
      const test_image = document.createElement("img");
      test_image.setAttribute("src", "../resources/test_images/" +
                                         data.question_image);
      parent_element.appendChild(test_image);
    }

    const all_options_wrapper = document.createElement('table');
    parent_element.append(all_options_wrapper);
    all_options_wrapper.setAttribute("class", "block");
    this.initiate_top_row(data.question_extras, all_options_wrapper)
    data.options.forEach(individual_option => {this.add_row(
                             individual_option, all_options_wrapper)});
  }
}
class BooleanChoice {
  constructor() {}
  display_question(question, parent_element) {

    this.display_question_text(question.question, parent_element);
    if (question.question_image != "NULL") {
      this.display_image(question.question_image, parent_element);
    }
    this.display_top_row_indicator(parent_element);

    const all_options_wrapper = document.createElement('div');
    parent_element.append(all_options_wrapper);
    all_options_wrapper.setAttribute("class", "block");
    var grey_background_index = 0;
    for (const option of question.options) {

      this.display_row(option, grey_background_index % 2 == 1,
                       all_options_wrapper);
      grey_background_index++;
    }
  }
  display_row(option, grey_background, parent_element) {
    const option_wrapper = document.createElement('div');
    parent_element.appendChild(option_wrapper);
    option_wrapper.setAttribute("class", "block");
    // fieldset to make sure only one is true
    const option_field_set = document.createElement('fieldset');
    option_wrapper.appendChild(option_field_set);
    if (grey_background) {
      option_field_set.classList.add("grey_background");
    }
    const element_id = "option_" + option.options_id;
    const cell_true = document.createElement("td");
    cell_true.classList.add("radio_button_margin");
    const answer_true = document.createElement("input");
    answer_true.setAttribute("type", "radio");
    answer_true.setAttribute("value", "1");
    answer_true.setAttribute("id", element_id);
    answer_true.setAttribute("name", element_id);
    cell_true.appendChild(answer_true);

    const cell_false = document.createElement("td");
    cell_false.classList.add("radio_button_margin");
    const answer_false = document.createElement("input");
    answer_false.setAttribute("type", "radio");
    answer_false.setAttribute("value", "0");
    answer_false.setAttribute("id", element_id);
    answer_false.setAttribute("name", element_id);
    cell_false.appendChild(answer_false);
    // text

    const option_text_wrapper = document.createElement("td");
    const option_text_element = document.createElement("span");
    option_text_element.textContent = option.option_text;
    option_text_wrapper.appendChild(option_text_element);

    // append all children
    option_field_set.appendChild(cell_true);
    option_field_set.appendChild(cell_false);
    option_field_set.appendChild(option_text_wrapper);
  }
  display_top_row_indicator(parent_element) {
    const indicator = document.createElement("fieldset");
    parent_element.appendChild(indicator);

    // the indicator has grey background :3
    indicator.classList.add("grey_background");
    const true_indicator = document.createElement("td");
    true_indicator.classList.add("radio_button_margin")
    true_indicator.classList.add("red_text")
    true_indicator.innerText = "True";
    indicator.appendChild(true_indicator);
    const false_indicator = document.createElement("td");
    false_indicator.innerText = "False";
    false_indicator.classList.add("radio_button_margin")
    false_indicator.classList.add("red_text")
    indicator.appendChild(false_indicator);
  }
  display_question_text(question_text, parent_element) {
    const table_question_wrapper = document.createElement("div");
    parent_element.append(table_question_wrapper);
    const question_text_element = document.createElement('pre');
    table_question_wrapper.append(question_text_element);
    question_text_element.textContent = question_text;
  }
  display_image(question_image, parent_element) {
    const test_image = document.createElement("img");
    test_image.setAttribute("src",
                            "../resources/test_images/" + question_image);
    parent_element.appendChild(test_image);
  }
}
class WriteIn {
  constructor() {}
  display_row(option_id, parent_element, i) {
    const input_cell = document.createElement("td");
    const option_number_indicator = document.createElement("span")
    option_number_indicator.innerText = i + ")"
    input_cell.appendChild(option_number_indicator)
    const input_element = document.createElement("input");
    input_element.setAttribute("type", "text");
    input_element.setAttribute("id", "option_" + option_id);
    input_element.setAttribute("name", "option_" + option_id);
    input_cell.appendChild(input_element);
    parent_element.appendChild(input_cell);
  }
  display_question_text(question_text, parent_element) {
    const table_question_wrapper = document.createElement("div");
    parent_element.append(table_question_wrapper);
    const question_text_element = document.createElement('pre');
    table_question_wrapper.append(question_text_element);
    question_text_element.textContent = question_text;
  }
  display_image(question_image, parent_element) {
    const test_image = document.createElement("img");
    test_image.setAttribute("src",
                            "../resources/test_images/" + question_image);
    parent_element.appendChild(test_image);
  }
  display_question(data, parent_element) {
    // console.log(data);
    this.display_question_text(data.question, parent_element);
    if (data.question_image != "NULL") {
      this.display_image(data.question_image, parent_element)
    }

    const question_table_wrapper = document.createElement("div");
    parent_element.appendChild(question_table_wrapper);
    question_table_wrapper.classList.add("block");
    var i = 1;
    data.options.forEach(option => {
      const individual_option_wrapper = document.createElement("div");
      question_table_wrapper.appendChild(individual_option_wrapper);
      individual_option_wrapper.classList.add("block");
      const fieldset = document.createElement("fieldset");
      individual_option_wrapper.appendChild(fieldset);
      this.display_row(option.options_id, fieldset, i);
      i++;
    });
  }
}
class OneFromMany {
  constructor() {}
  /*display_row(option_id, parent_element, i) {
    const input_cell = document.createElement("td");
    const option_number_indicator = document.createElement("span")
    option_number_indicator.innerText = i + ")"
    input_cell.appendChild(option_number_indicator)
    const input_element = document.createElement("input");
    input_element.setAttribute("type", "text");
    input_element.setAttribute("id", "option_" + option_id);
    input_element.setAttribute("name", "option_" + option_id);
    input_cell.appendChild(input_element);
    parent_element.appendChild(input_cell);
  }
  */
  create_select_element(index) {
    const selector = document.createElement("select");
    selector.setAttribute("id", "selector_" + index);
    return selector;
  }
  create_option_element(option_id, option_text) {
    const option_element = document.createElement("option");
    option_element.innerText = option_text;
    option_element.setAttribute("id", 'option_' + option_id);
    option_element.setAttribute("name", 'option_' + option_id);
    option_element.value = 1;
    return option_element;
  }
  display_question_text(question_text, parent_element) {
    const table_question_wrapper = document.createElement("div");
    parent_element.append(table_question_wrapper);
    const question_text_element = document.createElement('pre');
    table_question_wrapper.append(question_text_element);
    question_text_element.textContent = question_text;
  }
  display_image(question_image, parent_element) {
    const test_image = document.createElement("img");
    test_image.setAttribute("src",
                            "../resources/test_images/" + question_image);
    parent_element.appendChild(test_image);
  }
  display_question(data, parent_element) {
    // console.log(data);
    this.display_question_text(data.question, parent_element);
    if (data.question_image != "NULL") {
      this.display_image(data.question_image, parent_element)
    }

    const question_table_wrapper = document.createElement("div");
    parent_element.appendChild(question_table_wrapper);
    question_table_wrapper.classList.add("block");
    var i = 0;
    var select_element;

    data.options.forEach(option => {
      if (option.belongs_to > i) {
        const individual_option_wrapper = document.createElement("div");
        question_table_wrapper.appendChild(individual_option_wrapper);
        individual_option_wrapper.classList.add("block");
        const fieldset = document.createElement("fieldset");
        individual_option_wrapper.appendChild(fieldset);
        select_element = this.create_select_element(i);
        fieldset.appendChild(select_element);
        i++;
      }
      const option_element =
          this.create_option_element(option.options_id, option.option_text);
      select_element.appendChild(option_element);

      // this.display_row(option.options_id, fieldset, i);
    });
  }
}
document.getElementById("my_button")
    .addEventListener("click", function(event) { load_questions(event) });

function display_questions(received_data) {
  // console.log(data);
  data = received_data;

  // console.log(data);

  const userList = document.getElementById("user-list");
  userList.innerHTML = ""; // Clear the list

  const table = document.createElement("table");
  userList.appendChild(table);
  table.setAttribute("id", "main_table");
  table.setAttribute("class", "main_table");
  // Loop through the data and display each question - I will not be putting
  // this into multiple functions bcs it's funky already
  let question_index = 1;
  for (const element of data) {

    // console.log(element);
    //  question row
    const table_row = document.createElement('tr');
    //  question number on the left
    const table_number_cell = document.createElement('td');
    table_number_cell.classList.add("number_indicator");
    const table_number = document.createElement("span");
    table_number.textContent = question_index;
    table_number_cell.appendChild(table_number);
    table_row.appendChild(table_number_cell);
    question_index++;

    // question text
    const table_question_cell = document.createElement('td');
    table_question_cell.classList.add("question_cell");
    table_row.appendChild(table_question_cell);
    table.appendChild(table_row);
    if (element.question_type === "multiple-choice") {
      var multiple_choice_object = new MultipleChoice();
      multiple_choice_object.display_question(element, table_question_cell)
      continue;
    }
    if (element.question_type === "boolean-choice") {
      var boolean_choice_object = new BooleanChoice();
      boolean_choice_object.display_question(element, table_question_cell)
      continue;
    }
    if (element.question_type === "write-in") {
      var write_in_object = new WriteIn();
      write_in_object.display_question(element, table_question_cell)
      continue;
    }
    if (element.question_type === "one-from-many") {
      var one_from_many_object = new OneFromMany();
      one_from_many_object.display_question(element, table_question_cell)
      continue;
    }
  }
  const submit_button = document.createElement("input");
  submit_button.setAttribute("type", "submit");
  submit_button.setAttribute("value", "submit");
  submit_button.setAttribute("id", "submit_form_button");
  submit_button.addEventListener("click", submit_form);
  // console.log(submit_button);
  table.appendChild(submit_button);
}
