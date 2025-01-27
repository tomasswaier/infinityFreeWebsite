/* ngl I feel bad about putting it all in one file but like how else is it
 * supposed to look like ? I dont wanna have 2 functions in 2 files thats icky
 * (the above was written before addition of another 6 functions :p
 * :p sry for variable names being inconsistant
 */
function create_child_option(type, fieldset, options_id, option_text) {
  const element_id = "option_" + options_id;
  // console.log(type);
  if (type == 'multiple-choice') {

    const cell_true = document.createElement("td");
    cell_true.classList.add("radio_button_margin");
    const answer_true = document.createElement("input");
    answer_true.setAttribute("type", "radio");
    answer_true.setAttribute("value", "true");
    answer_true.setAttribute("id", element_id);
    answer_true.setAttribute("name", element_id);
    cell_true.appendChild(answer_true);

    const cell_false = document.createElement("td");
    cell_false.classList.add("radio_button_margin");
    const answer_false = document.createElement("input");
    answer_false.setAttribute("type", "radio");
    answer_false.setAttribute("value", "false");
    answer_false.setAttribute("id", element_id);
    answer_false.setAttribute("name", element_id);
    cell_false.appendChild(answer_false);
    // text

    const option = document.createElement("td");
    const option_text_element = document.createElement("span");
    option_text_element.textContent = option_text;
    option.appendChild(option_text_element);

    // append all children
    fieldset.appendChild(cell_true);
    fieldset.appendChild(cell_false);
    fieldset.appendChild(option);
    return;
  } else if (type == "write-in") {
    const input_cell = document.createElement("td");
    const input_element = document.createElement("input");
    input_element.setAttribute("type", "text");
    input_element.setAttribute("id", element_id);
    input_element.setAttribute("name", element_id);
    input_cell.appendChild(input_element);
    fieldset.appendChild(input_cell);
    return;
  } else {
    console.error("INVALID TYPE OF VARIABLE");
    return;
  }
}
var data;
function load_questions(event) {
  if (event) {
    event.preventDefault();
  }
  var number_of_questions;
  var test_id;
  console.log("no hash parameters given");
  number_of_questions = document.getElementById("number_of_questions").value;
  var test_id_object = document.getElementById("test_selector");
  if (test_id_object) {
    console.log(test_id_object);
  }
  test_id = test_id_object.value;
  // const index_of_option = test_id_object.getAttribute("name");
  // console.log("meow" + index_of_option);
  window.location.hash =
      "#" + test_id_object.options[test_id_object.selectedIndex].text + "#" +
      test_id + "#" + number_of_questions;
  console.log(test_id, number_of_questions);
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
}

// part for confirming
function convert_to_array(data) {
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
  var result = [];
  for (const question_wraper of data) {
    //[3] is always the ['options'] section of the array (ik it's icky)
    for (const options of question_wraper[4]) {
      // when converting array of objects of arrays of objects of arrays to an
      // array something went wrong this fixes it :3
      if (!options || typeof options == "string") {
        continue;
      }
      for (const option of options) {
        // in most cases you would only need the option[2][1] but in write in
        // questions you need [1][1]
        result.push([ option[0][1], option[2][1], option[1][1] ]);
      }
    }
  }
  console.log(result);
  return result;
}
function get_correct_answer(data, id, type) {
  /*
   * I will not be sorting the array and doing binary search because im not a
   * nerd
   * note : add binary search
   */
  for (const id_option of data) {
    if (id_option[0] == id) {
      if (type == "radio") {
        return id_option[1];
      } else if (type == "text") {
        return id_option[2];
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
  if (type == "radio") {
    correct_value = (correct_value == 1) ? "true" : "false";
    // console.log(correct_value)
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

function submit_form(event) {
  event.preventDefault();
  if (data == undefined) {
    console.log("Data wasn't loaded");
  } else {
    // console.log(data);
    data = convert_to_array(data);
    // console.log(data);
    var correct_values = extract_correct_answers(data);

    // meow
    const form = document.getElementById(
        "user-list"); // or "main_form" for the main form

    // Get all input elements in the form
    const inputs = form.querySelectorAll("input");

    // Iterate through the inputs and log their values and IDs/names
    for (const input of inputs) {
      // type is important to check what value it's expectin
      const id = input.id;
      const id_number = parseInt(id.split("_")[1]);
      const value = input.value;
      if (value == 'submit') {
        continue;
      }
      const type = input.type;
      var input_value;
      if (type == "radio") {
        input_value = input.checked;
      } else if (type == "text") {
        input_value = input.value;
      }
      if (!id_number) {
        console.log("Element Doesnt Exist");
        console.log(`ID: ${id_number}, Value: ${value}, Type: ${
            type}, Checked: ${input_value}`);
        continue;
      }

      // var parent = input.parentElement.parentElement.parentElement;
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

      // console.log(
      //     `ID: ${id}, Value: ${value}, Type: ${type}, Checked:
      //     ${checked}`);
    };
  }
}
document.getElementById("my_button")
    .addEventListener("click", function(event) { load_questions(event) });

function display_questions(received_data) {
  console.log(data);
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
  data.forEach(function(element) {
    // console.log(element);
    //  question row
    const table_row = document.createElement('tr');
    //  question number on the left
    const table_number_cell = document.createElement('td');
    table_number_cell.classList.add("number_indicator");
    const table_number = document.createElement("span");
    table_number.textContent = question_index;
    table_number_cell.appendChild(table_number);
    question_index++;

    // question text
    const table_question_cell = document.createElement('td');
    table_question_cell.classList.add("question_cell");
    const table_question_wrapper = document.createElement("div");
    table_question_cell.append(table_question_wrapper);
    const question_text = document.createElement('pre');

    table_question_wrapper.append(question_text);
    question_text.textContent = element.question;
    if (element.question_image != "NULL") {
      const test_image = document.createElement("img");
      test_image.setAttribute("src", "../resources/test_images/" +
                                         element.question_image);
      table_question_cell.appendChild(test_image);
    }

    const all_options_wrapper = document.createElement('div');
    table_question_cell.append(all_options_wrapper);
    all_options_wrapper.setAttribute("class", "block");
    if (element.question_type == "multiple-choice") {
      const indicator = document.createElement("fieldset");
      all_options_wrapper.appendChild(indicator);
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
    var grey_background_index = 0;
    // add option
    element.options.forEach(function(options) {
      // window around one option
      const option_wrapper = document.createElement('div');
      option_wrapper.setAttribute("class", "block");
      // fieldset to make sure only one is true
      const option_field_set = document.createElement('fieldset');
      // grey backgorund like in ais
      if (grey_background_index % 2 == 1 &&
          element.question_type == "multiple-choice") {
        option_field_set.classList.add("grey_background");
      }
      grey_background_index++;
      create_child_option(element.question_type, option_field_set,
                          options.options_id, options.option_text);

      // true and false buttons
      option_wrapper.appendChild(option_field_set);
      all_options_wrapper.appendChild(option_wrapper);
    });
    table_row.appendChild(table_number_cell);
    table_row.appendChild(table_question_cell);

    table.appendChild(table_row);
  });
  const submit_button = document.createElement("input");
  submit_button.setAttribute("type", "submit");
  submit_button.setAttribute("value", "submit");
  submit_button.setAttribute("id", "submit_form_button");
  submit_button.addEventListener("click", submit_form);
  // console.log(submit_button);
  table.appendChild(submit_button);
}
