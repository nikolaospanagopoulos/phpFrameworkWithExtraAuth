import Validate from "../Validate";

var html = `
<div id='passwordMismatch'>

<h1>Passwords do not match</h1>

</div>


`;

export default function validateFormOnSubmit() {
  
  if (
    document.getElementById("form-signup") ||
    document.getElementById("form-password")
  ) {
    var form =
      document.getElementById("form-signup") ||
      document.getElementById("form-password");

    form.addEventListener("submit", function (e) {
      if(document.getElementById('passwordMismatch')){
        document.getElementById('passwordMismatch').remove()
      }
      if (!Validate.validate()) {
        e.preventDefault();
        document.body.insertAdjacentHTML("beforeend", html);
        
      } 
    });
  }
}
