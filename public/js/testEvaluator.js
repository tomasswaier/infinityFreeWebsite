if (!document) {
  const {document} = require("postcss");
}
function hideResults() {
  const parent = document.getElementById("numberOfPointsPopupText")
                     .parentElement.parentElement;
  parent.classList.add("hidden");
}

async function incrementNumberOfSubmits(testId) {
  try {
    const response =
        await fetch(window.location.href +
                    `/../../../../api/incrementNumberOfSubmits/${testId}`);
    if (!response.ok)
      throw new Error('Network response was not ok');
  } catch (error) {
    console.error('Error incrementing test viewCount', error);
  }
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
  incrementNumberOfSubmits(test_id);

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
  expalnations.forEach(
      explanation => { explanation.removeAttribute('hidden'); });
  // document.getElementById('result_info').innerText =
  //    countCorrect + '/' + countAll;
}
function displaynumberOfPoints() {
  let correctCount = 0;
  for (const questionId of questionIdArray) {
    if (questionOptionAssignment[Number(questionId)] &&
        (questionOptionAssignment[Number(questionId)].length == 0)) {
      correctCount++;
    } else {
      // console.log("Unable to find questionId:" + questionId +
      //             " in the following object");
      // console.log(questionOptionAssignment);
      // console.log("this may be because a question was left empty")
    }
  }

  const popupText = document.getElementById("numberOfPointsPopupText");
  popupText.parentElement.parentElement.classList.remove('hidden');
  popupText.innerText = correctCount + " / " + questionCount;
}
function flagCorrectlyAnsweredQuestion(inputNumber, optionQuestionDict) {
  if (!questionOptionAssignment || !questionIdArray) {
    console.error("Error:Variable questionOptionAssignment is unavailable.")
    return;
  }
  const questionId = Number(optionQuestionDict[inputNumber]);
  if (!questionId || questionId < 1) {
    console.error(
        'Error:QuestionId to be removed from array can not be found.');
    return
  }

  if (questionOptionAssignment[questionId] &&
      (questionOptionAssignment[questionId].indexOf(inputNumber) > -1)) {
    questionOptionAssignment[questionId].splice(
        questionOptionAssignment[questionId].indexOf(inputNumber), 1);
  } else {
    console.log("didn't find questionId:" + questionId);
  }
}

function questionOptionAssignmentToDict() {
  let myDict = {};
  for (const questionId in questionOptionAssignment) {
    for (var inputNumber of questionOptionAssignment[questionId]) {
      myDict[inputNumber] = questionId;
    }
  }
  return myDict;
}

function evaluateOptions(inputs, correctValues, selectedFunction) {
  // Iterate through the inputs and log their values and IDs/names
  var optionQuestionDict = questionOptionAssignmentToDict();

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
    let evaluationResult = false;
    if (type == "radio") {
      evaluationResult = evaluateRadioBox(input, name_id, parent);

    } else if (type == "text") {
      parent = input;
      evaluationResult = evaluateWriteIn(input, name_id, input.parentElement);
    } else if (input.localName == "option") {
      type = 'option';
      name_id = input.attributes['name'].value;
      evaluationResult = evaluateSelect(input, name_id, parent);
    } else {
      console.error('Error:could not identify type of input field');
      continue;
    }

    if (evaluationResult) {
      flagCorrectlyAnsweredQuestion(Number(name_id), optionQuestionDict);
    }
  };
  displaynumberOfPoints();
}

function evaluateSelect(input, id, grandParent) {
  let isCorrect = false;
  if (input.value == correctOptions[id]) {
    // option is correct
    if (grandParent.getAttribute("class") &&
        grandParent.getAttribute("class").includes("!bg-red-500")) {
      grandParent.classList.remove("!bg-red-500")
    }

    grandParent.classList.add("!bg-green-700");
    isCorrect = true;
  } else {
    // option is incorrect
    if (!grandParent.getAttribute("class") ||
        !grandParent.getAttribute("class").includes("!bg-green-700")) {
      grandParent.classList.add("!bg-red-500");
    } // console.log(grandParent.lastChild);
    if (!(grandParent.lastChild.localName == 'span')) {
      const explanation = document.createElement('span');
      // console.log(input.parentElement.children[correctOptions[id]].innerText);
      explanation.innerText =

          input.parentElement.children[correctOptions[id]].innerText;
      grandParent.appendChild(explanation);
    }
  }
  return isCorrect;
}

function evaluateWriteIn(input, id, grandParent) {

  var correctOptionSplit = correctOptions[id].split(';');

  for (const option of correctOptionSplit) {
    if (input.value == option) {
      // is correct
      if (input.getAttribute("class") &&
          input.getAttribute("class").includes("!bg-red-500")) {
        input.classList.remove("!bg-red-500")
      }

      input.classList.add("!bg-green-700");
      return true;
    }
  }
  if (!input.getAttribute("class") ||
      !input.getAttribute("class").includes("!bg-green-700")) {
    input.classList.add("!bg-red-500");
  }
  if (!(grandParent.lastChild.localName == 'span')) {
    const explanation = document.createElement('span');
    explanation.classList.add('bg-red-200');
    explanation.innerText = correctOptions[id];
    grandParent.appendChild(explanation);
  }
  return false;
}

function evaluateRadioBox(input, id, grandParent) {
  const value = parseInt(input.value);
  if (!input.checked) {
    if (grandParent.getAttribute("class") &&
        !grandParent.getAttribute("class").includes("!bg-green-700")) {
      grandParent.classList.add("!bg-red-500");
    }
    if (correctOptions[id] == value) {
      input.classList.add('bg-green-200');
      grandParent.classList.add("!bg-red-500");
    }
    return;
  }
  var parent_classes = grandParent.getAttribute("class");
  if (correctOptions[id] == value) {
    if (parent_classes && parent_classes.includes("!bg-red-500")) {
      grandParent.classList.remove("!bg-red-500")
    }
    input.classList.add('bg-green-200');
    grandParent.classList.add("!bg-green-700");
    return true;
  } else {
    if (!parent_classes || !parent_classes.includes("!bg-green-700")) {
      grandParent.classList.add("!bg-red-500");
    } else {
      return true
    }
  }
  return false;
}

document.getElementById('testSubmitButton')
    .addEventListener("click", submitForm);
