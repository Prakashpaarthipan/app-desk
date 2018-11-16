//var specialKeys = new Array();
//specialKeys.push(8); //Backspace
function ValidateAlpha(evt)
    {
        var keyCode = (evt.which) ? evt.which : evt.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
       //document.getElementById("uname").style.display = ret ? "none" : "inline";  
        return false;
            return true;
    }
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_mob(e) {
	var keyCode = e.which ? e.which : e.keyCode

	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	
	
	document.getElementById("mob").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_ph(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("ph").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_pin(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pin").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_cph(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("cph").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_cmob(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("cmob").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_eyear(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("eyear").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_rdis(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode == 46) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("rdis").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_sdis(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode == 46) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("sdis").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_fdis(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode == 46) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("fdis").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_pice(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode == 46) ||  specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pice").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_pcode(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pcode").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_fran(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pcode").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_tran(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pcode").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_mqty(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pcode").style.display = ret ? "none" : "inline";
	
	return ret;
}
var specialKeys = new Array();
specialKeys.push(8); //Backspace
function IsNumeric_mxqty(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	document.getElementById("pcode").style.display = ret ? "none" : "inline";
	
	return ret;
}