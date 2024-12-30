function load_tests() {
  /*
   * loads all questions into understandable format
   */
  console.log("meow");
  console.log(number_of_questions);

  const xmlhttp = new XMLHttpRequest();

  xmlhttp.onload = function() {
    if (this.status === 200) {
      const myObj = JSON.parse(this.responseText);

      console.log(myObj);

      const userList = document.getElementById("test_selector");
      userList.innerHTML = ""; // Clear the list
      myObj.forEach(element => {
        const test_option = document.createElement("option");
        test_option.setAttribute("value", element['test_id']);
        test_option.setAttribute("id", "test_option_" + element['test_id']);
        test_option.innerHTML = element['test_name'];
        userList.appendChild(test_option);
      });

    } else {
      console.error("Requesting questions failed with status " + this.status);
    }
  };

  xmlhttp.open("GET", "load_tests.php", true);
  xmlhttp.send();
}
load_tests();
