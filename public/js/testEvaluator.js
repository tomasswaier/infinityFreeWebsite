if (!document) {
  const {document} = require("postcss");
}

function is_correct(id, value, type, input_value, correct_values) {
  var correct_value = correct_values[id];
  // console.log(correct_values);
  if (type == "radio" || type == "option") {
    // console.log(input_value + correct_value);
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
      if (correct_option.toLowerCase() === input_value.toLowerCase()) {
        return true;
      }
    }
    return false;
  }
  console.log("undefined type option , cannot evaluate " + type);
  return undefined;
}

function function_input(input) {
  // ... it does look funny doesnt it
  return input;
}

function function_select(input) { return input.selectedOptions[0]; }

function isCorrect(correctAnswer, userInputElement) {
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
  // console.log(inputs);
  evaluateOptions(inputs, correctOptions, function_input);
  inputs = form.querySelectorAll("select");
  evaluateOptions(inputs, correctOptions, function_select);

  var expalnations = document.getElementsByName('explanation');
  expalnations.forEach(explanation => {
    console.log(explanation);
    explanation.removeAttribute('hidden');
  });
}

function evaluateOptions(inputs, correctValues, selectedFunction) {
  // Iterate through the inputs and log their values and IDs/names
  for (var input of inputs) {
    input = selectedFunction(input);

    var parent = input.parentElement.parentElement;
    //  type is important to check what value it's expectin
    const id = input.name;
    var name_id = parseInt(id);
    const value = input.value;
    var type = input.type;
    if (type == 'submit') {
      continue;
    }
    var input_value;
    if (type == "radio") {
      evaluateRadioBox(input, name_id, parent);
      continue;
    } else if (type == "text") {
      parent = input.parentElement;
      evaluateWriteIn(input, name_id, parent);
      continue;
    } else if (input.localName == "option") {
      type = 'option';
      name_id = input.attributes['name'].value;
      evaluateSelect(input, name_id, parent);
      // console.log(input);
      // todo:this
    } else {
    }
    if (is_correct(name_id, value, type, input_value, correctValues)) {
      if (parent.getAttribute("class") &&
          parent.getAttribute("class").includes("bg-red-500")) {
        parent.classList.remove("bg-red-500")
      }

      parent.classList.add("bg-green-700");
      // console.log("correct");
    } else {
      if (!parent.getAttribute("class") ||
          !parent.getAttribute("class").includes("bg-green-700")) {
        parent.classList.add("bg-red-500");
      }
    }
  };
}

function evaluateSelect(input, id, grandParent) {

  if (input.value == correctOptions[id]) {
    if (grandParent.getAttribute("class") &&
        grandParent.getAttribute("class").includes("bg-red-500")) {
      grandParent.classList.remove("bg-red-500")
    }

    grandParent.classList.add("bg-green-700");
    // console.log("correct");
  } else {
    if (!grandParent.getAttribute("class") ||
        !grandParent.getAttribute("class").includes("bg-green-700")) {
      grandParent.classList.add("bg-red-500");
    }
    // console.log(grandParent.lastChild);
    if (!(grandParent.lastChild.localName == 'span')) {
      const explanation = document.createElement('span');
      // console.log(input.parentElement.children[correctOptions[id]].innerText);
      explanation.innerText =

          input.parentElement.children[correctOptions[id]].innerText;
      grandParent.appendChild(explanation);
    }
  }
}

function evaluateWriteIn(input, id, grandParent) {

  if (input.value == correctOptions[id]) {
    if (grandParent.getAttribute("class") &&
        grandParent.getAttribute("class").includes("bg-red-500")) {
      grandParent.classList.remove("bg-red-500")
    }

    grandParent.classList.add("bg-green-700");
    // console.log("correct");
  } else {
    if (!grandParent.getAttribute("class") ||
        !grandParent.getAttribute("class").includes("bg-green-700")) {
      grandParent.classList.add("bg-red-500");
    }
    // console.log(grandParent.lastChild);
    if (!(grandParent.lastChild.localName == 'span')) {
      const explanation = document.createElement('span');
      explanation.innerText = correctOptions[id];
      grandParent.appendChild(explanation);
    }
  }
}

function evaluateRadioBox(input, id, grandParent) {
  const value = parseInt(input.value);
  if (!input.checked) {
    // console.log(input);
    if (grandParent.getAttribute("class") &&
        !grandParent.getAttribute("class").includes("bg-green-700")) {
      grandParent.classList.add("bg-red-500");
    }
    if (correctOptions[id] == value) {
      input.classList.add('bg-green-200');
      grandParent.classList.add("bg-red-500");
    }
    return;
  }
  var parent_classes = grandParent.getAttribute("class");
  if (correctOptions[id] == value) {
    if (parent_classes && parent_classes.includes("bg-red-500")) {
      grandParent.classList.remove("bg-red-500")
    }
    input.classList.add('bg-green-200');
    grandParent.classList.add("bg-green-700");
  } else {
    if (!parent_classes || !parent_classes.includes("bg-green-700")) {
      grandParent.classList.add("bg-red-500");
    }
  }
}

document.getElementById('testSubmitButton').addEventListener("click", submitForm);
