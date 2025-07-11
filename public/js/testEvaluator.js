if (!document) {
  const {document} = require("postcss");
}

function is_correct(id, value, type, input_value, correct_values) {
  var correct_value = correct_values[id];
  // console.log(id, value, type, input_value, correct_value)
  if (type == "radio" || type == "option") {
    if (correct_value == value) {
      return true;
    } else {
      return false;
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

function isCorrect(correctAnswer, userInputElement) {
  console.log(userInputElement);
  console.log(correctAnswer + '||||' + userInputElement.value);
  var userAnswer = userInputElement.valuea;
  return correctAnswer == userAnswer;
}

function submitForm(event) {
  if (event) {
    event.preventDefault();
  }
  if (!correctOptions) {
    console.log(
        'corrett options have not been intiated hence an error happened or some tiddlywanker is plauying around with html js or sum')
  }
  const form =
      document.getElementById("testForm"); // or "main_form" for the main form

  // Get all input elements in the form
  var inputs = form.querySelectorAll("input");
  evaluateOptions(inputs, correctOptions, function_input);
  inputs = form.querySelectorAll("select");
}

function evaluateOptions(inputs, correctValues, selectedFunction) {
  // Iterate through the inputs and log their values and IDs/names
  for (var input of inputs) {
    input = selectedFunction(input);
    // console.log(input);
    //  type is important to check what value it's expectin
    const id = input.name;
    const name_id = parseInt(id);
    const value = input.value;
    if (value == 'submit') {
      continue;
    }
    // console.log(input);
    var type = input.type;
    if (type == undefined) {
      console.log('error with input :');
      continue;
    }
    var input_value;
    if (type == "radio") {
      input_value = input.checked;
    } else if (type == "text") {
      input_value = input.value;
    } else if (type == "option") {
      input_value = 1;
      // todo:this
    }

    var parent = input.parentElement.parentElement;
    var classes = parent.getAttribute("class");
    if (is_correct(name_id, value, type, input_value, correctValues)) {
      console.log(parent);
      if (classes && classes.includes("bg-red-500")) {
        parent.classList.remove("bg-red-500")
      }

      parent.classList.add("bg-green-700")
      // console.log("correct");
    } else {
      console.log('nay');
      if (!classes || !classes.includes("bg-green-700")) {
        parent.classList.add("bg-red-500");
        /*
if (type == "text") {
const correct_value_hint = document.createElement("span");
correct_value_hint.innerText =
get_correct_answer(correct_values, name_id, type);
input.parentElement.appendChild(correct_value_hint);
}
*/
      }
    }
  };
}


document.getElementById('testSubmitButton').addEventListener("click", submitForm);
