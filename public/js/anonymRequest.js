async function storeAnonymRequest(event) {
  if (event) {
    event.preventDefault();
  }

  try {
    const text = document.getElementById('anonym_request_text').value;

    const source = document.getElementById('anonym_request_source').value;

    var url = "";
    console.log(window.location.href.split('/'));
    if (window.location.href.includes("localhost")) {
      url = "http://localhost:8000/api/anonymRequest"
    } else {
      url = "https://maryann.free.nf/api/anonymRequest"
    }

    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    const response = await fetch(url, {
      method : "POST",
      body : JSON.stringify({source : source, text : text}),
      headers : {
        "Content-type" : "application/json; charset=UTF-8",
        'X-CSRF-TOKEN' : csrf
      }
    });
    const responseSpan = document.getElementById("response_message")

    if (!response.ok) {
      responseSpan.classList.remove("hidden")
      responseSpan.classList.add("text-red-700")
      responseSpan.innerText = "Failed to save request"

      // throw new Error('Network response was not ok');
    }
    else {
      responseSpan.classList.remove("hidden")
      responseSpan.classList.add("text-green-400")
      responseSpan.innerText = "Request saved"
    }

  } catch (error) {
    console.error('Error incrementing test viewCount', error);
  }
}

window.onload = function() {
  document.getElementById('anonym_request_submit_button')
      .addEventListener("click", storeAnonymRequest);
};
