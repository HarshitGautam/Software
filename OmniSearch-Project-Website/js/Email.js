/*
This contains a set of functions that help protect email addresses
from spam-bots. Instead of using the email address directly, the 
encoded value is stored in the html and decoded when required.

Ralph Arvesen
Vertigo Software
*/

// open the client email with the specified address
function sendEmail(encodedEmail) {
    // do the mailto: link
    location.href = "mailto:" + decodeEmail(encodedEmail);
}

// display the email address in the statusbar
function displayStatus(encodedEmail) {
    window.status = "mailto:" + decodeEmail(encodedEmail);
}

// clear the statusbar message
function clearStatus() {
    window.status = "";
}

// return the decoded email address
function decodeEmail(encodedEmail) {
    // The encodedEmail is a string that contains the email address.
    // Each character in the email address has been converted into 
    // a two digit number (hex / base16). This function converts the
    // series of numbers back into the real email address.

    // holds the decoded email address
    var email = "";

    // go through and decode the email address
    for (i = 0; i < encodedEmail.length; ) {
        // holds each letter (2 digits)
        var letter = "";
        letter = encodedEmail.charAt(i) + encodedEmail.charAt(i + 1)

        // build the real email address
        email += String.fromCharCode(parseInt(letter, 16));
        i += 2;
    }

    return email;
}