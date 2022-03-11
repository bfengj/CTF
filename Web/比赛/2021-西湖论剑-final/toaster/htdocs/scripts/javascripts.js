<!--
function pulisci_input_type(theField) {

	if (theField.defaultValue==theField.value)
	theField.value = ""

}

function menu_a_discesa( targ, selObj, restore ){

	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  	if (restore) selObj.selectedIndex=0;
	
}

// Check that the number of characters in a string is between a max and a min
function isValidLength(string, min, max) {
        if (string.length < min || string.length > max) return false;
        else return true;
}

// Check that an email address is valid based on RFC 821 (?)
function isValidEmail(address) {
        if (address.indexOf('@') < 3) return false;
        var name = address.substring(0, address.indexOf('@'));
        var domain = address.substring(address.indexOf('@') + 1);
        if (name.indexOf('(') != -1 || name.indexOf(')') != -1 || name.indexOf('<') != -1 || name.indexOf('>') != -1 || name.indexOf(',') != -1 || name.indexOf(';') != -1 || name.indexOf(':') != -1 || name.indexOf('\\') != -1 || name.indexOf('"') != -1 || name.indexOf('[') != -1 || name.indexOf(']') != -1 || name.indexOf(' ') != -1) return false;
        if (domain.indexOf('(') != -1 || domain.indexOf(')') != -1 || domain.indexOf('<') != -1 || domain.indexOf('>') != -1 || domain.indexOf(',') != -1 || domain.indexOf(';') != -1 || domain.indexOf(':') != -1 || domain.indexOf('\\') != -1 || domain.indexOf('"') != -1 || domain.indexOf('[') != -1 || domain.indexOf(']') != -1 || domain.indexOf(' ') != -1) return false;
        return true;
}


// Check that a US zip code is valid
function isValidZipcode(zipcode) {
        zipcode = removeSpaces(zipcode);
        if (!(zipcode.length == 5) || !isNumeric(zipcode)) return false;
        return true;
}


// Check that a Canadian postal code is valid
function isValidPostalcode(postalcode) {
        if (postalcode.search) {
                postalcode = removeSpaces(postalcode);
                if (postalcode.length == 6 && postalcode.search(/^\w\d\w\d\w\d$/) != -1) return true;
                else if (postalcode.length == 7 && postalcode.search(/^\w\d\w\-d\w\d$/) != -1) return true;
                else return false;
        }
        return true;
}

// Check that a string contains only letters and numbers
function isAlphanumeric(string, ignoreWhiteSpace) {
        if (string.search) {
                if ((ignoreWhiteSpace && string.search(/[^\w\s]/) != -1) || (!ignoreWhiteSpace && string.search(/\W/) != -1)) return false;
        }
        return true;
}

// Check that a string contains only letters
function isAlphabetic(string, ignoreWhiteSpace) {
        if (string.search) {
                if ((ignoreWhiteSpace && string.search(/[^a-zA-Z\s]/) != -1) || (!ignoreWhiteSpace && string.search(/[^a-zA-Z]/) != -1)) return false;
        }
        return true;
}

// Check that a string contains only numbers
function isNumeric(string, ignoreWhiteSpace) {
        if (string.search) {
                if ((ignoreWhiteSpace && string.search(/[^\d\s]/) != -1) || (!ignoreWhiteSpace && string.search(/\D/) != -1)) return false;
        }
        return true;
}

// Remove characters that might cause security problems from a string
function removeBadCharacters(string) {
        if (string.replace) {
                string.replace(/[<>\"\'%;\)\(&\+]/, '');
        }
        return string;
}

// Remove all spaces from a string
function removeSpaces(string) {
        var newString = '';
        for (var i = 0; i < string.length; i++) {
                if (string.charAt(i) != ' ') newString += string.charAt(i);
        }
        return newString;
}

// Remove leading and trailing whitespace from a string
function trimWhitespace(string) {
        var newString  = '';
        var substring  = '';
        beginningFound = false;

        // copy characters over to a new string
        // retain whitespace characters if they are between other characters
        for (var i = 0; i < string.length; i++) {

                // copy non-whitespace characters
                if (string.charAt(i) != ' ' && string.charCodeAt(i) != 9) {

                        // if the temporary string contains some whitespace characters, copy them first
                        if (substring != '') {
                                newString += substring;
                                substring = '';
                        }
                        newString += string.charAt(i);
                        if (beginningFound == false) beginningFound = true;
                }

                // hold whitespace characters in a temporary string if they follow a non-whitespace character
                else if (beginningFound == true) substring += string.charAt(i);
        }
        return newString;
}

// Returns a checksum digit for a number using mod 10
function getMod10(number) {

        // convert number to a string and check that it contains only digits
        // return -1 for illegal input
        number = '' + number;
        number = removeSpaces(number);
        if (!isNumeric(number)) return -1;

        // calculate checksum using mod10
        var checksum = 0;
        for (var i = number.length - 1; i >= 0; i--) {
                var isOdd = ((number.length - i) % 2 != 0) ? true : false;
                digit = number.charAt(i);

                if (isOdd) checksum += parseInt(digit);
                else {
                        var evenDigit = parseInt(digit) * 2;
                        if (evenDigit >= 10) checksum += 1 + (evenDigit - 10);
                        else checksum += evenDigit;
                }
        }
        return (checksum % 10);
}

function setPointer(theCel, thePointerColor)
{
if (thePointerColor == '' || typeof(theCel.style) == 'undefined') {
return false;
}
theCel.style.backgroundColor = thePointerColor;
return true;
}

status_text();

function status_text() {
	window.status="";
	setTimeout("status_text()",1);
}

var isNS = (navigator.appName == "Netscape") ? 1 : 0;
var EnableRightClick = 0;
if(isNS) document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);

function mischandler(){
	if(EnableRightClick==1){ return true; }
	else {return false; }
}

function mousehandler(e){
	if(EnableRightClick==1){ return true; }
	var myevent = (isNS) ? e : event;
	var eventbutton = (isNS) ? myevent.which : myevent.button;
	if((eventbutton==2)||(eventbutton==3)) return false;
}

function keyhandler(e) {
	var myevent = (isNS) ? e : window.event;
	if (myevent.keyCode==96)
	EnableRightClick = 1;
	return;
}

document.oncontextmenu = mischandler;
document.onkeypress = keyhandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;

-->