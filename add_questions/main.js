function load_input_field(event) {
  event.preventDefault();
  // load and clear everything
  const form_element = document.getElementById("user-list");
  form_element.innerHTML = "";

  // choose which test
  const test_wrapper = document.createElement("div");
  test_wrapper.setAttribute("class", "block");
  const test_text = document.createElement("span");
  test_text.innerHTML = "Test number (default ppi = 1):";
  const test_input = document.createElement("input");
  test_input.setAttribute("type", "number");
  test_input.setAttribute("value", "1");
  test_input.setAttribute("id", "test_number");
  test_wrapper.appendChild(test_text);
  test_wrapper.appendChild(test_input);
  form_element.appendChild(test_wrapper);

  // add question name
  const question_name_wrapper = document.createElement("div");
  question_name_wrapper.setAttribute("class", "block");
  const question_name_text = document.createElement("span");
  question_name_text.innerHTML = "question name:";
  const question_name_input = document.createElement("input");
  question_name_input.setAttribute("type", "text");
  question_name_input.setAttribute("id", "question_name_text");
  question_name_wrapper.appendChild(question_name_text);
  question_name_wrapper.appendChild(question_name_input);
  form_element.appendChild(question_name_wrapper);

  // add options field
  const question_type_selector_wrapper = document.createElement("div");
  form_element.append(question_type_selector_wrapper);
  const question_type_selector = document.createElement("select");
  question_type_selector.setAttribute("id", "question_type_selector");
  question_type_selector_wrapper.appendChild(question_type_selector);
  // we could do it by some php x sql bullshit where we receive all the enum
  // options but idc i want it to work
  const question_types = [ "multiple-choice", "write-in" ];
  for (const question_type of question_types) {
    const question_type_option = document.createElement("option");
    question_type_option.setAttribute("value", question_type);
    question_type_option.innerHTML = question_type;
    question_type_selector.appendChild(question_type_option);
  }
  const has_image = document.createElement("div");
  has_image.innerHTML =
      "<span>Upload image:</span><select id='has_image'><option value='true'>Yes</option><option value='false'>No</option></select>";
  form_element.appendChild(has_image);
}

document.getElementById("my_button")
    .addEventListener("click", load_input_field);
