function formValidation() {
    var uid = document.registform.userid;
    var passid = document.registform.passid;
    var uname = document.registform.username;
    var uadd = document.registform.address;
    var ucountry = document.registform.country;
    var uzip = document.registform.zip;
    var uemail = document.registform.email;
    var usex = document.registform.sex;
    if (validateUserID(uid, 5, 12)){
        if (validatepassword(passid, 7, 12)) {
            if (allLetter(uname)) {
                if (alphanumeric(uadd)) {
                    if (countryselect(ucountry)) {
                        if (allnumeric(uzip)) {
                            if (validateEmail(uemail)) {
                                if (validateGender(usex)) {}
                            }
                        }
                    }
                }
            }
        }
    }
    return false;
}
   