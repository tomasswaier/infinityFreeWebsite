class Subject {
  constructor(subject_name, row) {
    this.parent_element = row;
    this.name = subject_name;
    this.prev_element = NaN;
  }
  initiate() {
    const wrapper = document.createElement("td");
    this.parent_element.appendChild(wrapper);
    this.prev_element = wrapper;
    wrapper.innerHTML = this.name;
    // add add button
    const button = document.createElement("button");
    button.innerText = "new class";
    wrapper.after(button);
    self = this;
    button.onclick = (event) => self.add_class(event);
  }
  add_class(event) {
    event.preventDefault();
    const class_wrapper = document.createElement("td");
    class_wrapper.classList.add("class_time_border");
    this.prev_element.after(class_wrapper);
    this.prev_element = class_wrapper;
    const time_input = document.createElement("input");
    class_wrapper.appendChild(time_input);
    time_input.setAttribute("type", "number");
    time_input.setAttribute("min", "6");
    time_input.setAttribute("max", "22");
    time_input.setAttribute("value", "8");

    const text_indicator = document.createElement("span");
    class_wrapper.appendChild(text_indicator);
    text_indicator.innerHTML = ":00-";
    const time_end = document.createElement("span");
    text_indicator.appendChild(time_end);
    time_end.innerText = "9";
    text_indicator.after(":50");

    time_input.onchange = (event) =>
        this.change_time(event, time_input, time_end);
  }
  change_time(event, time_input, end_time_element) {
    event.preventDefault()
    let time = Number(time_input.value);
    time += 1;
    end_time_element.innerText = String(time);
  }
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
