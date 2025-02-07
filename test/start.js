function load_tests() {
  // should be rewritten so that instaed i use actual hash but whatever
  let ais_array = [
    "Acute Ischemic Stroke", "Amniotic Infection Syndrome",
    "Airborne Intercept System", "Advanced Infantry System",
    "Aviation Industry Standards", "Aircraft Inspection Safety",
    "Amateur Interstellar Society", "Androgen Insensitivity Syndrome",
    "Anterior Ischemic Syndrome", "Acoustic Imaging System",
    "Advanced Induction Stove", "Automated Inventory System",
    "Aged Italian Sausage", "Aromatic Infused Syrup"
  ]
  document.getElementById("ais_text").innerText =
      ais_array[Math.floor(Math.random() * ais_array.length)].toUpperCase();
  const pathHash = String(window.location).split("#");
  const isNumeric = (string) => string == Number.parseInt(string)

  console.log("test number :" + pathHash[2] +
              " number of questions: " + pathHash[3]);
  if (pathHash[3] && isNumeric(pathHash[2]) && isNumeric(pathHash[3])) {
    // load_questions();
    let selector = document.getElementById("test_selector")
    if (selector) {
      selector.remove()
    }
    document.getElementById("user-list").innerText = "test :" + pathHash[1]
  } else {
    $.ajax({
      url : "load_tests.php",
      mothod : "POST",
      // dataTypep : 'json',
      success : function(data) {
        // console.log(data);
        const myObj = JSON.parse(data);

        // console.log(myObj);

        const userList = document.getElementById("test_selector");
        userList.innerHTML = ""; // Clear the list
        let index = 0;
        myObj.forEach(element => {
          const test_option = document.createElement("option");
          test_option.setAttribute("value", element['test_id']);
          test_option.setAttribute("name", index);
          test_option.setAttribute("id", "test_option_" + element['test_id']);
          test_option.innerHTML =
              element['test_name'] + "/" + element['test_author'];
          userList.appendChild(test_option);
          index++;
        });
        if (pathHash[3] && isNumeric(pathHash[2]) && isNumeric(pathHash[3])) {
          // console.log(pathHash[3], isNumeric(pathHash[2]));
          document.getElementById("number_of_questions")
              .setAttribute("value", pathHash[3])
          if (pathHash[2] <= index) {
            var test_id_object = document.getElementById("test_selector")
            test_id_object.value = pathHash[2];
            // test_id_object.selectedIndex
          }
        }
      },
      error : function() {
        console.log("failed to run start.js database connection error")
      },
    });
  }
}
load_tests();
