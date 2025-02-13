class Subject {
  constructor(subject_name, row) {
    this.parent_element = row;
    this.name = subject_name;
  }
  initiate() {
    const wrapper = document.createElement("th");
    this.parent_element.appendChild(wrapper);
    wrapper.innerHTML = this.name;
  }
  add_class(time) {}
  add_lecture() {}
}

function add_subject(event) {
  if (event) {
    event.preventDefault();
  }
  const subject_name_element = document.getElementById("subject_name");
  if (subject_name_element.value) {
    const new_subject_row = document.createElement("tr");
    const table = document.getElementById("subjects_table");
    table.appendChild(new_subject_row)

    var new_subj = new Subject(subject_name_element.value, new_subject_row);
    new_subj.initiate()
  }
  subject_name_element.value = "";
}

document.getElementById("add_subject_button")
    .addEventListener("click", function(event) { add_subject(event); });
