function kill_bad_children(element) {
  for (var bad_child of [...element.childNodes]) {
    if (bad_child.nodeName === "TD") {
      bad_child.remove();
    }
  }
  // console.log(element);
  return element;
}

function display_timetable(timetable) {
  const head_element = document.getElementById("table_body");
  var i = 0;
  // console.log(head_element.childNodes);
  for (var element of head_element.childNodes) {
    var prev_time = 7;
    if (element.nodeName == "TR" && i < timetable.length) {
      element = kill_bad_children(element);
      for (const my_class of timetable[i]) {
        if (my_class["time_start"] > prev_time + 1) {
          for (let j = prev_time + 1; j < my_class["time_start"]; j++) {
            const empty_element = document.createElement("td");
            element.appendChild(empty_element);
          }
        }
        const class_wrapper = document.createElement("td");
        if (my_class["is_lecture"]) {
          class_wrapper.classList.add("lecture_background");
        } else {
          class_wrapper.classList.add("class_background");
        }
        class_wrapper.setAttribute("colspan", my_class["time_end"] -
                                                  my_class["time_start"] + 1);
        class_wrapper.classList.add("brd");
        element.appendChild(class_wrapper);
        var class_to_append = document.createElement("span");
        class_wrapper.innerText = my_class["name"];
        class_wrapper.appendChild(class_to_append);
        prev_time = my_class["time_end"];
      }
      i++;
    }
  }
}

function print_timetable(timetable) {
  console.log("timetable");
  for (const day of timetable) {
    var day_string = "|"
    for (const my_class of day) {
      day_string += `${my_class["name"]} ${my_class["time_start"]}:50-${
          my_class["time_end"]}:50|`
    }
    console.log(day_string);
  }
}

function can_insert_in_timetable(timetable_day, time_start, time_end) {
  for (let i = 0; i < timetable_day.length; i++) {
    const prev_class = timetable_day[i];
    if (prev_class["time_start"] <= time_start &&
            time_start <= prev_class["time_end"] ||
        (prev_class["time_start"] <= time_end &&
         time_end <= prev_class["time_end"])) {
      return false
    }
  }
  return true
}

function sort_timetable(timetable) {
  /*
   * I do not like being alive. Every single day I after I know that my
   * pookiebear Boris will not be  mine not bcs I have a girlfriend but
   * because he's not actually gay. Every day I feel only suffering because of
   * what could've been if only he was into men. Every day I wake up only to
   * suffer more. I do not wish to exist in world that god has abandoned. I
   * begged, pleaded, cried to him that I'd be his servant... The most
   * obsequious one there ever was and the most faithful one at that but to no
   * awail... Please end my suffering.
   */
  for (let i = 0; i < timetable.length; i++) {
    timetable[i].sort((a, b) => a.time_start - b.time_start)
  }
  return timetable
}

function get_nearest_later_class(timetable, my_class) {
  // we expect that iw ill come here sorted
  for (const subject of timetable[my_class["day"]]) {
    if (subject["time_start"] > my_class["time_end"]) {
      return subject["time_start"];
    }
  }

  return 100;
}

function get_nearest_earlier_class(timetable, my_class) {
  // we expect that iw ill come here sorted
  var current = 100;
  for (const subject of timetable[my_class["day"]]) {
    if (subject["time_end"] < my_class["time_start"]) {
      current = subject["time_end"];
    }
  }
  return current;
}

// gaps between  prev ele - my ele - next ele
function evaluate_class(timetable, my_class) {
  var evaluation = -1
  var nearest_earlier = get_nearest_earlier_class(timetable, my_class);
  if (nearest_earlier != 100) {
    evaluation = my_class["time_start"] - nearest_earlier;
  } else {
    evaluation += nearest_earlier;
  }
  var nearest_later = get_nearest_later_class(timetable, my_class);
  evaluation += nearest_later - my_class["time_end"];
  return evaluation;
}

function calculate_best_shedule(lectures_array, classes_array) {
  var timetable = [ [], [], [], [], [] ];
  var available_classes = [];
  var available_classes_map = {};
  for (const lecture of lectures_array) {
    if (!can_insert_in_timetable(timetable[lecture["day"]],
                                 lecture["time_start"], lecture["time_end"])) {
      console.log("can't insert into timetable");
      // break ?
    } else {
      lecture["is_lecture"] = true;
      timetable[lecture["day"]].push(lecture)
      sort_timetable(timetable);
    }

    if (available_classes.indexOf(lecture["name"]) == -1) {
      available_classes.push(lecture["name"]);
      available_classes_map[lecture["name"]] = 0
    }
  }
  var evaluated_classes = [];
  for (const my_class of classes_array) {
    if (!can_insert_in_timetable(timetable[my_class["day"]],
                                 my_class["time_start"],
                                 my_class["time_end"])) {
      console.log("can't be inserted into timetable");
    } else {
      my_class["class_evaluation"] = evaluate_class(timetable, my_class);
      my_class["is_lecture"] = false;
      evaluated_classes.push(my_class);
      available_classes_map[my_class["name"]] += 1;
    }
  }
  available_classes.sort((a, b) => available_classes_map[a] -
                                   available_classes_map[b]);
  evaluated_classes.sort((a, b) => a.class_evaluation - b.class_evaluation);
  console.log(evaluated_classes)
  for (const highest_priority_class of available_classes) {
    for (const available_class of evaluated_classes) {
      if (available_class["name"] == highest_priority_class &&
          can_insert_in_timetable(timetable[available_class["day"]],
                                  available_class["time_start"],
                                  available_class["time_end"])) {
        timetable[available_class["day"]].push(available_class);
        timetable = sort_timetable(timetable);

        var new_evaluated_classes = [];
        for (const my_class of evaluated_classes) {
          if (!can_insert_in_timetable(timetable[my_class["day"]],
                                       my_class["time_start"],
                                       my_class["time_end"])) {
            console.log("can't be inserted into timetable");
          } else {
            my_class["class_evaluation"] = evaluate_class(timetable, my_class);
            new_evaluated_classes.push(my_class);
            available_classes_map[my_class["name"]] += 1;
          }
        }
        evaluated_classes = new_evaluated_classes;
        console.log(evaluated_classes)

        break
      }
    }
  }
  print_timetable(timetable);
  display_timetable(timetable);
}

function process_input(event) {
  event.preventDefault()
  const form = document.querySelector("#user_classes_form");
  const form_data = new FormData(form);
  const classes_formated = [];
  const lectures_formated = [];
  var array = {};
  var is_lecture = false
  for (const data of form_data) {
    // console.log(data);
    descriptor = data[0];
    descriptor = descriptor.split("_");
    if (descriptor[0] == "day") {
      var subject_name = descriptor[descriptor.length - 1];
      array["name"] = subject_name;
      if (descriptor[descriptor.length - 2] == "lecture") {
        is_lecture = true
      }
      array["day"] = Number(data[1]);
    } else {
      if (descriptor[1] == "end") {
        array["time_end"] = Number(data[1]);
        if (is_lecture) {
          lectures_formated.push(array);
        } else {
          classes_formated.push(array);
        }
        is_lecture = false;
        array = {};
      } else {
        array["time_start"] = Number(data[1]);
      }
    }
  }
  calculate_best_shedule(lectures_formated, classes_formated);
}
