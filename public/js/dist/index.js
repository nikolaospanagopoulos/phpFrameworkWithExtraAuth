(() => {
  // src/Validate.js
  var Validate = class {
    static validate() {
      if (document.getElementById("form-signup")) {
        var inputEmail = document.getElementById("inputEmail");
        var password = document.getElementById("inputPassword");
        var passwordConf = document.getElementById("inputPasswordConfirmation");
        if (!inputEmail.value.match(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i)) {
          return false;
        }
        if (password.value !== passwordConf.value) {
          return false;
        }
      } else if (document.getElementById("form-password")) {
        var password = document.getElementById("inputPassword");
        var passwordConf = document.getElementById("inputPasswordConfirmation");
        if (password.value !== passwordConf.value) {
          return false;
        }
      }
      return true;
    }
  };

  // src/submit/onSubmit.js
  var html = `
<div id='passwordMismatch'>

<h1>Passwords do not match</h1>

</div>


`;
  function validateFormOnSubmit() {
    if (document.getElementById("form-signup") || document.getElementById("form-password")) {
      var form = document.getElementById("form-signup") || document.getElementById("form-password");
      form.addEventListener("submit", function(e) {
        if (document.getElementById("passwordMismatch")) {
          document.getElementById("passwordMismatch").remove();
        }
        if (!Validate.validate()) {
          e.preventDefault();
          document.body.insertAdjacentHTML("beforeend", html);
        }
      });
    }
  }

  // index.js
  validateFormOnSubmit();
})();
//# sourceMappingURL=index.js.map
