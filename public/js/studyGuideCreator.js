function display_input_image(id) {
  if (!id) {
    return;
  }
  // displays  selected user image to user for confirmation/better ux
  if (document.getElementById(id)) {
    document.getElementById(id).value = '';
  }
  var image = document.getElementById(id).files[0];
  var reader = new FileReader();
  reader.onload =
      function(
          e) { document.getElementById("display_image").src = e.target.result; }

      reader.readAsDataURL(image);
}

function remove_input_image(id) {
  // remove user image
  // todo: fix this
  document.getElementById(id).value = '';
  document.getElementById("user_image").value = '';
  document.getElementById("display_image").src = '';
}

function createImageField() {
  const wrapper = document.createElement('div');
  wrapper.classList.add('p-1', 'rounded-sm', 'm-4');
  const title = document.createElement('input');
  title.placeholder = 'Image Title'
  wrapper.appendChild(title);
  title.name = 'sectionTitle';
  title.type = 'text';
  const image = document.createElement('img');
}

function createTextField() {
  const wrapper = document.createElement('div');
  wrapper.classList.add('p-1', 'rounded-sm', 'm-4');
  const title = document.createElement('input');
  title.placeholder = 'Section Title'
  wrapper.appendChild(title);
  title.name = 'sectionTitle';
  title.type = 'text';
  wrapper.appendChild(document.createElement('br'));
  const sectionText = document.createElement('textarea');
  wrapper.appendChild(sectionText);
  sectionText.classList.add('border', 'border-project-blue', 'rounded-md');
  sectionText.rows = 4;
  sectionText.cols = 100;

  return wrapper;
}

function loadInputField() {
  const studyGuideContentWrapper = document.getElementById('studyGuideContent');
    studyGuideContentWrapper.appendChild(createTextField());
}
