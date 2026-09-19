class Subject {
  constructor(subject_name, row) {
    this.parent_element = row;
    this.name = subject_name;
    this.prev_element = NaN;
  }
  initiate() {
    if (document.getElementById("submit_button") == null) {
      const form = document.getElementById("user_classes_form");
      const submit_button = document.createElement("input");
      form.appendChild(submit_button);
      submit_button.setAttribute("id", "submit_button");
      submit_button.setAttribute("type", "submit")
      submit_button.setAttribute("value", "calculate_schedule")
      submit_button.onclick = function(event) { process_input(event); }
    }
    const wrapper = document.createElement("td");
    this.parent_element.appendChild(wrapper);
    const lectures_wrapper = document.createElement("td");
    lectures_wrapper.classList.add("border", "w200");
    this.parent_element.appendChild(lectures_wrapper);
    this.prev_element = lectures_wrapper;
    // add button for lectures
    const lectures_button = document.createElement("button");
    lectures_button.innerText = "new lecture";
    lectures_wrapper.appendChild(lectures_button)
    self = this;
    lectures_button.onclick = (event) =>
        self.add_lecture(event, lectures_wrapper, this.name);

    wrapper.innerHTML = this.name;
    // add add button for classes
    const button = document.createElement("button");
    button.innerText = "new class";
    lectures_wrapper.after(button);
    button.onclick = (event) =>
        self.add_class(event, this.parent_element, this.name);
  }
  add_day_picker(name, is_lecture) {
    const day_selector = document.createElement("select");
    day_selector.setAttribute("id", "day_selector");
    var lecutre_indicator = ""
    if (is_lecture) {
      lecutre_indicator = "lecture_";
    }
    day_selector.setAttribute("name",
                              "day_selector_" + lecutre_indicator + name);
    const days_array = [ "Mon", "Tue", "Wed", "Thu", "Fri" ];
    for (let i = 0; i < days_array.length; i++) {
      const option = document.createElement("option");
      day_selector.appendChild(option)
      option.setAttribute("value", i);
      option.innerText = days_array[i]
    }
    return day_selector
  }
  add_time_input(parent_element, name) {
    const time_input = document.createElement("input");
    parent_element.appendChild(time_input);
    time_input.setAttribute("type", "number");
    time_input.setAttribute("min", "6");
    time_input.setAttribute("max", "22");
    time_input.setAttribute("value", "8");
    time_input.setAttribute("name", "time_start_" + name);

    const text_indicator = document.createElement("span");
    parent_element.appendChild(text_indicator);
    text_indicator.innerHTML = ":00-";
    const time_end = document.createElement("input");
    time_end.setAttribute("type", "number");
    time_end.setAttribute("name", "time_end_" + name);
    time_end.classList.add("invisible_input");
    text_indicator.appendChild(time_end);
    time_end.value = 9;
    text_indicator.after(":50");
    time_input.onchange = (event) =>
        this.change_time(event, time_input, time_end);
  }
  add_remove_button(parent_element) {
    const button = document.createElement("button");
    button.innerText = "x";

    button.onclick = function() { parent_element.remove(); };

    parent_element.appendChild(button);
  }
  add_class(event, parent_element, name) {
    // change_interface_colors();
    event.preventDefault();
    const class_wrapper = document.createElement("td");
    class_wrapper.classList.add("class_time_border");
    class_wrapper.classList.add("w200");
    const class_wrapper_fieldset = document.createElement("fieldset");
    class_wrapper.appendChild(class_wrapper_fieldset);
    parent_element.insertBefore(
        class_wrapper,
        parent_element.childNodes[parent_element.childNodes.length - 1]);
    // dont ask why one is called and the other has return ...
    class_wrapper_fieldset.appendChild(this.add_day_picker(name, false));
    this.add_time_input(class_wrapper_fieldset, name);
    this.add_remove_button(class_wrapper);
  }
  add_lecture(event, parent_element, name) {
    event.preventDefault()
    const fieldset = document.createElement("fieldset");
    parent_element.appendChild(fieldset);
    fieldset.appendChild(this.add_day_picker(name, true));
    this.add_time_input(fieldset, name);
    this.add_remove_button(fieldset);
  }
  change_time(event, time_input, end_time_element) {
    event.preventDefault()
    let time = Number(time_input.value);
    time += 1;
    end_time_element.value = time;
  }
}

function add_subject(event) {
  if (event) {
    event.preventDefault();
  }
  const subject_name_element = document.getElementById("subject_name");
  if (subject_name_element.value) {
    const new_subject_row = document.createElement("tr");
    new_subject_row.classList.add("class_row")
    const table = document.getElementById("subjects_table");
    table.appendChild(new_subject_row)

    var new_subj = new Subject(subject_name_element.value, new_subject_row);
    new_subj.initiate()
  }
  subject_name_element.value = "";
}
document.getElementById("add_subject_button")
    .addEventListener("click", function(event) { add_subject(event); });

function change_interface_colors(event) {
  if (event) {
    event.preventDefault()
  }

  var r = document.querySelector(':root');
  if (getComputedStyle(r).getPropertyValue('--background_color') == "#000000") {
    r.style.setProperty("--background_color", "#ffffff");
    r.style.setProperty("--text_color", "#000000");
  } else {
    r.style.setProperty("--background_color", "#000000");
    r.style.setProperty("--text_color", "#ffffff");
  }
}
document.getElementById("change_interface_button")
  .addEventListener("click",
    function(event) {
      change_interface_colors(event);
    });
