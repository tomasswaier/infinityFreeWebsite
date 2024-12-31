function load_tests() {
  $.ajax({
    url : "load_tests.php",
    mothod : "GET",
    // dataTypep : 'json',
    success : function(data) {
      const myObj = JSON.parse(data);

      console.log(myObj);

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
