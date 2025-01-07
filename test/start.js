function load_tests() {
  // should be rewritten so that instaed i use actual hash but whatever
  window.addEventListener("hashchange", function() { location.reload() });
  const pathHash = String(window.location).split("#");
  const isNumeric = (string) => string == Number.parseInt(string)
  if (pathHash[3] && isNumeric(pathHash[2]) && isNumeric(pathHash[3])) {
    load_questions();
  }
  $.ajax({
    url : "load_tests.php",
    mothod : "POST",
    // dataTypep : 'json',
    success : function(data) {
      const myObj = JSON.parse(data);

      // console.log(myObj);

      const userList = document.getElementById("test_selector");
      userList.innerHTML = ""; // Clear the list
      myObj.forEach(element => {
        const test_option = document.createElement("option");
        test_option.setAttribute("value", element['test_id']);
        test_option.setAttribute("id", "test_option_" + element['test_id']);
        test_option.innerHTML =
            element['test_name'] + "/" + element['test_author'];
        userList.appendChild(test_option);
      });
    },
    error : function() { alert("error :<"); }
  });
}
load_tests();
