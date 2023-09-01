//for register
function doRegister() {
  let email = document.getElementById("email").value;

  if (!email) {
    alert("E-mail should not be empty");
    document.getElementById("email").focus();
    return;
  }

  // Check if email has a basic valid format
  let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  if (!emailRegex.test(email)) {
    alert("Please enter a valid email address.");
    document.getElementById("email").focus();
    return;
  }

  let name = email.split("@")[0];
  if (!name) {
    alert("Failed to extract name from the email");
    return;
  }

  postData("/wp-json/api/v1/register", {
    email,
    name,
  })
    .then((response) => {
      const jsonObject = JSON.parse(response);
      if (jsonObject.code != 0)
        throw new Error(`error code: ${jsonObject.code}`);
      let publicKey = preformatMakeCredReq(jsonObject.fido_register_request);
      return navigator.credentials.create({ publicKey });
    })
    .then((response) => {
      let makeCredResponse = publicKeyCredentialToJSON(response);
      // console.log("THIS IS MakeCredResponse", makeCredResponse);
      return putData("/wp-json/api/v1/register", makeCredResponse);
    })
    .then((response) => {
      const jsonObject = JSON.parse(response);
      if (jsonObject.code == 0) {
        console.log("register success");
      }
    })
    .catch((error) => {
      if (error.name === "NotAllowedError") {
        // user cancel
        // TODO: show something
        console.log("user press cancel");
      } else if (error.name === "InvalidStateError") {
        // key has registered credential
        // TODO: show something
        console.log("Security Key has been registered");
      } else {
        // TODO: show something
        console.log(error.message);
      }
    });
}

//for dologin
function doLogin() {
  postData("/wp-json/api/v1/login", {})
    .then((response) => {
      const jsonObject = JSON.parse(response);
      if (jsonObject.code != 0)
        throw new Error(`error code: ${jsonObject.code}`);
      let publicKey = preformatGetAssertReq(jsonObject.fido_login_request);
      return navigator.credentials.get({
        publicKey,
      });
    })
    .then((response) => {
      let getCredResponse = publicKeyCredentialToJSON(response);
      return putData("/wp-json/api/v1/login", getCredResponse);
    })
    .then((response) => {
      const jsonObject = JSON.parse(response);
      if (jsonObject.code == 0) {
        document.getElementById("gogo-email-form").style.display = "none";
        console.log("User login succese");
      } else {
        console.log("With error code");
      }
    });
}
