function oldLinkChecker() {
  let ais_array = [
    "Acute Ischemic Stroke", "Amniotic Infection Syndrome",
    "Airborne Intercept System", "Advanced Infantry System",
    "Aviation Industry Standards", "Aircraft Inspection Safety",
    "Amateur Interstellar Society", "Androgen Insensitivity Syndrome",
    "Anterior Ischemic Syndrome", "Acoustic Imaging System",
    "Advanced Induction Stove", "Automated Inventory System",
    "Aged Italian Sausage", "Aromatic Infused Syrup"
  ]
  document.getElementById("ais_text").innerText =
      ais_array[Math.floor(Math.random() * ais_array.length)].toUpperCase();
  const pathHash = String(window.location).split("#");
  const isNumeric = (string) => string == Number.parseInt(string)

  if (pathHash[3] && isNumeric(pathHash[2]) && isNumeric(pathHash[3])) {
    // load_questions();
    // console.log('moew');
    window.location.href = 'https://maryann.free.nf/test/' + pathHash[1] + '/' +
                           pathHash[2] + '/' + pathHash[3];
  }
}
