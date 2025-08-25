function naiveId() {
  // function stolen from
  // https://medium.com/@ryan_forrester_/javascript-unique-id-generation-how-to-guide-0d6752318823
  // I do not need uuid just something which can make less than 100 uuids for
  // backend to process. (Lord Farquad from Shrek voice) Some people might get 2
  // ids on different elements making this website not work properly but that is
  // a sacrifice I'm willing to take
  return (Date.now().toString(36) + Math.random().toString(36))
      .replace('.', '');
}

function displayInputImage(inputId, imageId) {
  if (!inputId) {
    return;
  }
  // displays  selected user image to user for confirmation/better ux
  var image = document.getElementById(inputId).files[0];
  var reader = new FileReader();
  reader.onload = function(
      e) { document.getElementById(imageId).src = e.target.result; };
  if (image) {
    reader.readAsDataURL(image);
  }
}

function removeInputImage(id) {
  // remove user image
  // todo: fix this
  document.getElementById(id).value = '';
  document.getElementById("user_image").value = '';
  document.getElementById("display_image").src = '';
}

function createImageField() {
  const uid = naiveId();
  const wrapper = document.createElement('div');
  wrapper.id = 'wrapper_' + uid;
  wrapper.classList.add('p-1', 'rounded-sm', 'm-4');
  const title = document.createElement('input');
  title.placeholder = 'Image Title'
  title.name = 'img_title_' + uid;
  title.id = 'img_title_' + uid;
  console.log(title.name);
  wrapper.appendChild(title);
  title.type = 'text';
  const imageInput = document.createElement('input');
  imageInput.name = 'img_file_' + uid;
  imageInput.id = 'img_file_' + uid;
  imageInput.onchange = function() {
    console.log('img_display_' + uid);
    displayInputImage('img_file_' + uid, 'img_display_' + uid);
  };

  wrapper.appendChild(imageInput);
  imageInput.type = 'file';
  const image = document.createElement('img');
  wrapper.appendChild(image);
  image.id = 'img_display_' + uid;
  image.src = '';
  return wrapper;
}

function createTextField() {
  const uid = naiveId();
  const wrapper = document.createElement('div');
  wrapper.classList.add('p-1', 'rounded-sm', 'm-4');
  const title = document.createElement('input');
  title.placeholder = 'Section Title'
  title.name = 'section_title_' + uid;
  title.id = 'section_title_' + uid;
  wrapper.appendChild(title);
  title.type = 'text';
  wrapper.appendChild(document.createElement('br'));
  const sectionText = document.createElement('textarea');
  wrapper.appendChild(sectionText);
  sectionText.name = 'section_text_' + uid;
  sectionText.id = 'section_text_' + uid;
  sectionText.classList.add('border', 'border-project-blue', 'rounded-md');
  sectionText.rows = 4;
  sectionText.cols = 100;

  return wrapper;
}

function loadInputField() {
  const studyGuideContentsWrapper =
      document.getElementById('studyGuideContents');
  studyGuideContentsWrapper.appendChild(createTextField());
}

function displaySectionCreators(parentElement) {
  var element = parentElement;
  if (element == null) {
    element = document.getElementById('studyGuideContents');
  }
  const createTextFieldButton = document.createElement('button');
  element.parentElement.appendChild(createTextFieldButton);
  createTextFieldButton.type = "button";
  createTextFieldButton.innerText = "|text|";
  createTextFieldButton.onclick =
      function() { element.appendChild(createTextField()); };
  const createImageFieldButton = document.createElement('button');
  element.parentElement.appendChild(createImageFieldButton);
  createImageFieldButton.type = "button";
  createImageFieldButton.innerText = "|img|";
  createImageFieldButton.onclick = function(event) {
        if (event) {
            event.preventDefault();
        }
        element.appendChild(createImageField());
    }
}
