jQuery(document).ready(function($){
	 
	function checkWindowWidth() {
		//check window width (scrollbar included)
		var e = window, 
            a = 'inner';
        if (!('innerWidth' in window )) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        if ( e[ a+'Width' ] >= MqL ) {
			return true;
		} else {
			return false;
		}
	}
	
	$("input[name='calc-payment-terms']").change(function(e){
  	CalcPayment('calcForm'); 
	});
	
	$("input[name='calc-payment-frequency']").change(function(e){
  	CalcPayment('calcForm');
  	if($(this).val() == 1){
    	$('.price-detail').html('Monthly Payment *OAC')
  	} else{
    	$('.price-detail').html('Bi-Weekly Payment *OAC')
  	}
	});
	
	jQuery("input[name='depositAmt']").bind('input propertychange', function() {
  	if( $(this).val() == '' ){
  	} else{
      CalcPayment('calcForm');
  	}
	});
	
	if($('#calc-Form').length){
    CalcPayment('calcForm');
	}
	
	function CalcPayment(whatForm) {
    
		var equipAmt = parseValue(document.forms[whatForm].equipAmt.value)
		var depositAmt = parseValue(document.forms[whatForm].depositAmt.value)
		
		if( isNaN(depositAmt) || depositAmt < ( equipAmt * 0.1 )){
  		depositAmt = equipAmt * 0.1;
  		// show note abooyt 10%;
  		$('#calc-form-error').html('Minimum 10% Deposit required')
		} else{
  		// clear error
  		$('#calc-form-error').html('')
  		
		}
		
		var formLoan = equipAmt - depositAmt
		document.forms[whatForm].loanAmt.value =  formLoan
		
		var loanAmt = parseValue(formLoan)
		
		var intRate = parseValue(document.forms[whatForm].intRate.value) / 100
		
		var amortization = $("input[name='calc-payment-terms']:checked").val()
		
		var payFreq = $("input[name='calc-payment-frequency']:checked").val()
		
		var totalPayments = amortization / payFreq
	
		var payAmt = (loanAmt * (intRate*payFreq/12) * Math.pow((1+(intRate*payFreq/12))  , totalPayments))/(Math.pow((1+(intRate*payFreq/12)),totalPayments)-1);
		
		payAmt = Math.round(payAmt *100) /100
		
		if (payAmt.toString() == "NaN") {
		  payAmt = 0
		}
			
		var payTotal = payAmt * totalPayments;
	 
		$('.calc-price').html(payAmt.formatMoney(0, '.', ','));
		$('.finAmt').html( "$" +(loanAmt).formatMoney(2, '.', ',') );
		$('.finCredit').html("$" +  (payTotal - loanAmt).formatMoney(2, '.', ','));
		$('.finTotal').html("$" + (payTotal).formatMoney(2, '.', ','));
	}

  $( function() {
    $( "#calculator" ).accordion({
      active: false,
      collapsible: true
    });
  });

});

// Calculator functions
function parseValue(inputNum) {
		var inStr = inputNum.toString()
		var outStr = ""
		for (var i=0;i<inStr.length;i++) {
			var thisChar = inStr.substring(i,i+1)
			var thisInt = parseInt(thisChar)
			if (((thisInt >= 0) && (thisInt <= 9) && (thisInt == thisChar)) || thisChar == ".") {
				outStr = outStr + thisChar
			}
		}
		return parseFloat(outStr)
	}
  
Number.prototype.formatMoney = function(c, d, t){
    var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  };

function getTagPrice(topPrice){
    var loanAmt = topPrice - (topPrice * 0.1)
  	var intRate = 6.5 / 100
  	var amortization = 60;
  	var payFreq = 1
  	var totalPayments = amortization / payFreq
    var payAmt = (loanAmt * (intRate*payFreq/12) * Math.pow((1+(intRate*payFreq/12))  , totalPayments))/(Math.pow((1+(intRate*payFreq/12)),totalPayments)-1)
    payAmt = Math.round(payAmt *100) /100;
    document.write(payAmt)
  }