console.log('GDPR Enabled Now');

let show_gdpr = true;

function getCookie(cookie_name) {
    var cookieArr = document.cookie.split(";");
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        if(cookie_name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}

function checkCookie() {
  var gdpr_compliance_accepted = getCookie("gdpr_compliance");
  if (gdpr_compliance_accepted != "" && gdpr_compliance_accepted != null) {
    console.log("Welcome again");
    console.log('gdpr_compliance_accepted: ', gdpr_compliance_accepted);

    show_gdpr = false;
  }
}

// check for acceptance
checkCookie();

// create generic message (TODO: eventually pulled from theme settings)
const gdpr_container = document.createElement("div");
gdpr_container.classList.add("gdpr_container");
gdpr_container.setAttribute("id", "gdpr_container");

const gdpr_text = document.createTextNode("We use cookies on our website to give you the most relevant experience by remembering your preferences and repeat visits. By clicking 'Accept All', you consent to the use of all cookies.");

const gdpr_accept_btn = document.createElement("button")
gdpr_accept_btn.classList.add("gdpr_accept_btn");
gdpr_accept_btn.classList.add("btn");
gdpr_accept_btn.classList.add("btn-primary");
gdpr_accept_btn.innerHTML = "Accept All";


// after the dom loads, append the message if it hasn't been accepted yet
document.addEventListener("DOMContentLoaded", function(){

    // if the cookie exists
    if(show_gdpr){
        gdpr_container.appendChild(gdpr_text);
        gdpr_container.appendChild(gdpr_accept_btn);
        document.body.appendChild(gdpr_container);
    }

    // on click, add the cookie to hook to for a conditional check before adding additional cookies
    gdpr_accept_btn.addEventListener("click", function(){
        // set the cookie, and make it expire far into the future
        document.cookie = "gdpr_compliance=accepted; expires=" + new Date(2147483647 * 1000).toUTCString();"path=/;";

        // remove the bar
        document.getElementById("gdpr_container").remove();

    });

    // add function to delete all cookies unless the user has accepted
    // https://stackoverflow.com/questions/10306254/is-it-possible-to-block-cookies-from-being-set-using-javascript-or-php

});

