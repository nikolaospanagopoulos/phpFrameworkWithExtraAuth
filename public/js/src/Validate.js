export default class Validate {
  static validate() {
    if (document.getElementById("form-signup")) {
      var inputEmail = document.getElementById("inputEmail");
      var password = document.getElementById("inputPassword");
      var passwordConf = document.getElementById("inputPasswordConfirmation");

      if (
        !inputEmail.value.match(
          /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
        )
      ) {
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
}
