const path_hash = String(window.location).split("#");
if (path_hash.length > 2) {
  console.log(path_hash);
  load_input_field();
} else {
  const load_button = document.createElement("button");
  load_button.innerText = "load";

  load_button.setAttribute("onclick", "display_test_selector(event)");
  document.getElementById("user-list").appendChild(load_button);
}

function display_test_selector() {
  const form_element = document.getElementById("user-list");
  form_element.innerHTML = "";
  const test_id_value = document.createElement("select");
  form_element.appendChild(test_id_value);
  test_id_value.setAttribute("name", "test_number");
  test_id_value.setAttribute("id", "test_number");
  get_test_options(test_id_value);
  form_element.appendChild(document.createElement("br"));
  const token_input_field = document.createElement("input")
  token_input_field.setAttribute("type", "text");
  token_input_field.setAttribute("id", "token_input");
  token_input_field.setAttribute("placeholder", "token");
  form_element.appendChild(token_input_field);
  form_element.appendChild(document.createElement("br"));

  const load_button = document.createElement("button");
  load_button.innerText = "load";
  load_button.setAttribute("onclick", "update_url(event)");
  form_element.appendChild(load_button);
}

function update_url(event) {
  event.preventDefault();
  const test_id_object = document.getElementById("test_number");
  // console.log(test_id_object);
  const token_input = document.getElementById("token_input");
  // console.log(token_input);
  // console.log(test_id_object.value);
  const url_addition =
      "#" + test_id_object.options[test_id_object.selectedIndex].text + "#" +
      test_id_object.value + "#" + token_input.value;

  window.location.hash = url_addition;
  load_input_field(event)
}
