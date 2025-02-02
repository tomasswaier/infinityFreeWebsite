function display_input_image() {

  var image = document.getElementById("user_image").files[0];

  var reader = new FileReader();

  reader.onload =
      function(
          e) { document.getElementById("display_image").src = e.target.result; }

      reader.readAsDataURL(image);
}
var option_number = 1;
function add_test_options(parent, data) {
  // console.log(data);
  myObj = JSON.parse(data);
  // console.log(data);
  myObj.forEach(test_option => {
    option = document.createElement("option");
    option.innerText =
        test_option['test_name'] + "/" + test_option['test_author'];
    option.setAttribute("value", test_option['test_id']);
    option.setAttribute("id", "test_option_" + test_option['test_id']);
    parent.appendChild(option);
  });
}
function get_test_options(parent) {
  $.ajax({
    url : "load_data.php",
    mothod : "GET",
    // dataTypep : 'json',
    success : function(data) { add_test_options(parent, data); },
    error : function() { alert("error :<"); }
  });
}
function add_child_type_multiple_choice(event) {
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
                           "correct_option_multiple_choice_" + option_number);
  answer_true.setAttribute("name",
                           "correct_option_multiple_choice_" + option_number);
  fieldset.appendChild(answer_true);
  const answer_false = document.createElement("input");
  answer_false.setAttribute("type", "radio");
  answer_false.setAttribute("value", "false");
  answer_false.setAttribute("id",
                            "correct_option_multiple_choice_" + option_number);
  answer_false.setAttribute("name",
                            "correct_option_multiple_choice_" + option_number);
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
function add_child_type_write_in(event) {
  event.preventDefault();
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
function display_option_type(event, user_option) {
  // reset the number counter bcs
  option_number = 1;

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
  if (user_option == "multiple-choice") {

    const indicator = document.createElement("tr");
    options_table.appendChild(indicator);
    const true_indicator = document.createElement("td");
    true_indicator.innerText = "true/false";
    indicator.appendChild(true_indicator);
    option_input_creator.onclick = add_child_type_multiple_choice;
    add_child_type_multiple_choice(event);
  }
  if (user_option == "write-in") {
    option_input_creator.onclick = add_child_type_write_in;
    add_child_type_write_in(event);
  }
}
function load_input_field(event) {
  if (event) {
    event.preventDefault();
  }
  // load and clear everything
  const form_element = document.getElementById("user-list");
  form_element.innerHTML = "";

  // choose which test
  /* change this
const test_input = document.createElement("input");
test_input.setAttribute("type", "number");
test_input.setAttribute("value", "1");
test_input.setAttribute("id", "test_number");
test_input.setAttribute("name", "test_number");
*/

  // add question name
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

  // get user image
  const has_image = document.createElement("div");
  has_image.innerHTML =
      '<input id="user_image" name="user_image" type="file" onChange="display_input_image()" /><br><img id="display_image" src="" />';
  form_element.appendChild(has_image);
  // add options field
  const question_type_selector_wrapper = document.createElement("div");
  form_element.append(question_type_selector_wrapper);
  const question_type_selector = document.createElement("select");
  question_type_selector_wrapper.appendChild(question_type_selector);
  question_type_selector.setAttribute("id", "question_type_selector");
  question_type_selector.setAttribute("onChange",
                                      "display_option_type(event,this.value)");
  const question_type_user_input_wrapper = document.createElement("div");
  question_type_user_input_wrapper.setAttribute(
      "id", "question_type_user_input_wrapper");
  form_element.append(question_type_user_input_wrapper);
  // we could do it by some php x sql bullshit where we receive all the enum
  // options but idc i want it to work
  const question_types = [ "multiple-choice", "write-in" ];
  for (const question_type of question_types) {
    const question_type_option = document.createElement("option");
    question_type_option.setAttribute("value", question_type);
    question_type_option.innerHTML = question_type;
    question_type_selector.appendChild(question_type_option);
  }

  display_option_type(event, "multiple-choice");
  const test_submit_button = document.createElement("input");
  test_submit_button.setAttribute("onclick", "send_form_data(event)");
  test_submit_button.setAttribute("type", "submit");
  test_submit_button.setAttribute("name", "submit");
  test_submit_button.setAttribute("value", "submit");
  document.body.appendChild(test_submit_button);
}

/*
document.getElementById("my_button")
    .addEventListener("click", load_input_field);
    */

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
    url : "send_data.php",
    method : "POST",
    data : (form_data),
    processData : false,
    contentType : false,
    success : function(data) {
      console.log("success");
      console.log(data);
      const server_response = document.createElement("span");
      server_response.innerHTML = data;

      document.body.appendChild(server_response);
    },
    error : function() {
      console.log("error");
      /*alert(
          "reload the page to fix Error fetching data pls report it to me
         Anča(.MaryAnn) id:" + test_id + " num:" + number_of_questions);
          */
    },

  });
}
