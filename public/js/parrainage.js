function docheckall() {
  if ($("#checkall").is(":checked")) {
	   $("#startdate").val("");
	   $("#enddate").val("");
	   $("#numbre").val("");
  }
}

function uncheck() {
  document.getElementById("checkall").checked = false;
}

function empty() {

    if (document.getElementById("checkall").checked == false && 
    	isEmpty('startdate') && isEmpty('enddate')&& isEmpty('numbre')) {
    	document.getElementById('erreurlabel').textContent  = 'Vous devez selectionner au moins un critére';
        return false;
    }else{
        document.getElementById('erreurlabel').textContent  = '';
        return true;
    }
}

function isEmpty(idinput){
	var value;
	value = document.getElementById(idinput).value;
  return (value == null || value === '');
}