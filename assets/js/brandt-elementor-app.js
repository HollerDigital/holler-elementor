"use strict";

jQuery(document).ready(function ($) {
  function checkWindowWidth() {
    //check window width (scrollbar included)
    var e = window,
        a = 'inner';

    if (!('innerWidth' in window)) {
      a = 'client';
      e = document.documentElement || document.body;
    }

    if (e[a + 'Width'] >= MqL) {
      return true;
    } else {
      return false;
    }
  }

  $("input[name='calc-payment-terms']").change(function (e) {
    CalcPayment('calcForm');
  });
  $("input[name='calc-payment-frequency']").change(function (e) {
    CalcPayment('calcForm');

    if ($(this).val() == 1) {
      $('.price-detail').html('Monthly Payment *OAC');
    } else {
      $('.price-detail').html('Bi-Weekly Payment *OAC');
    }
  });
  jQuery("input[name='depositAmt']").bind('input propertychange', function () {
    if ($(this).val() == '') {} else {
      CalcPayment('calcForm');
    }
  });

  if ($('#calc-Form').length) {
    CalcPayment('calcForm');
  }

  function CalcPayment(whatForm) {
    var equipAmt = parseValue(document.forms[whatForm].equipAmt.value);
    var depositAmt = parseValue(document.forms[whatForm].depositAmt.value);

    if (isNaN(depositAmt) || depositAmt < equipAmt * 0.1) {
      depositAmt = equipAmt * 0.1; // show note abooyt 10%;

      $('#calc-form-error').html('Minimum 10% Deposit required');
    } else {
      // clear error
      $('#calc-form-error').html('');
    }

    var formLoan = equipAmt - depositAmt;
    document.forms[whatForm].loanAmt.value = formLoan;
    var loanAmt = parseValue(formLoan);
    var intRate = parseValue(document.forms[whatForm].intRate.value) / 100;
    var amortization = $("input[name='calc-payment-terms']:checked").val();
    var payFreq = $("input[name='calc-payment-frequency']:checked").val();
    var totalPayments = amortization / payFreq;
    var payAmt = loanAmt * (intRate * payFreq / 12) * Math.pow(1 + intRate * payFreq / 12, totalPayments) / (Math.pow(1 + intRate * payFreq / 12, totalPayments) - 1);
    payAmt = Math.round(payAmt * 100) / 100;

    if (payAmt.toString() == "NaN") {
      payAmt = 0;
    }

    var payTotal = payAmt * totalPayments;
    $('.calc-price').html(payAmt.formatMoney(0, '.', ','));
    $('.finAmt').html("$" + loanAmt.formatMoney(2, '.', ','));
    $('.finCredit').html("$" + (payTotal - loanAmt).formatMoney(2, '.', ','));
    $('.finTotal').html("$" + payTotal.formatMoney(2, '.', ','));
  }

  $(function () {
    $("#calculator").accordion({
      active: false,
      collapsible: true
    });
  });
}); // Calculator functions

function parseValue(inputNum) {
  var inStr = inputNum.toString();
  var outStr = "";

  for (var i = 0; i < inStr.length; i++) {
    var thisChar = inStr.substring(i, i + 1);
    var thisInt = parseInt(thisChar);

    if (thisInt >= 0 && thisInt <= 9 && thisInt == thisChar || thisChar == ".") {
      outStr = outStr + thisChar;
    }
  }

  return parseFloat(outStr);
}

Number.prototype.formatMoney = function (c, d, t) {
  var n = this,
      c = isNaN(c = Math.abs(c)) ? 2 : c,
      d = d == undefined ? "." : d,
      t = t == undefined ? "," : t,
      s = n < 0 ? "-" : "",
      i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
      j = (j = i.length) > 3 ? j % 3 : 0;
  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function getTagPrice(topPrice) {
  var loanAmt = topPrice - topPrice * 0.1;
  var intRate = 6.5 / 100;
  var amortization = 60;
  var payFreq = 1;
  var totalPayments = amortization / payFreq;
  var payAmt = loanAmt * (intRate * payFreq / 12) * Math.pow(1 + intRate * payFreq / 12, totalPayments) / (Math.pow(1 + intRate * payFreq / 12, totalPayments) - 1);
  payAmt = Math.round(payAmt * 100) / 100;
  document.write(payAmt);
}
// Avoid `console` errors in browsers that lack a console.
"use strict";

jQuery(window).on('elementor/frontend/init', function () {
  elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
    jQuery("#brandt-feature-slider").imagesLoaded(function () {
      jQuery('#brandt-feature-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: true,
        responsive: {
          0: {
            items: 1
          },
          768: {
            items: 3
          },
          1160: {
            items: 5
          }
        }
      });
      jQuery("#brandt-feature-slider").show();
    });
  });
});
'use strict';

jQuery(document).ready(function ($) {
  if (jQuery('#year-filter').length) {
    if (typeof equipment_years !== 'undefined') {
      var unique_years = equipment_years.filter(function (elem, index, self) {
        return index == self.indexOf(elem);
      });
      unique_years.sort();

      for (var i = 0; i < unique_years.length; i++) {
        addradiobutton("radio", unique_years[i], 'year-filter');
      }
    }
  }

  if (jQuery('#makes-filter').length) {
    if (typeof equipment_makes !== 'undefined') {
      var unique_makes = equipment_makes.filter(function (elem, index, self) {
        return index == self.indexOf(elem);
      });
      unique_makes.sort();

      for (var u = 0; u < unique_makes.length; u++) {
        addradiobutton("radio", unique_makes[u], 'makes-filter');
      }
    }
  }

  if (jQuery('#cats-filter').length) {
    if (typeof equipment_cats !== 'undefined') {
      var unique_cats = equipment_cats.filter(function (elem, index, self) {
        return index == self.indexOf(elem);
      });
      unique_cats.sort();

      for (var j = 0; j < unique_cats.length; j++) {
        addradiobutton("radio", unique_cats[j], 'cats-filter');
      }
    }
  }

  jQuery("input[name='cats-filter']").change(function (e) {
    if (this.value == "*") {
      $('.card-equipment').show();
    } else {
      $('.card-equipment').hide();
      $('.card-equipment.' + slugify(this.value)).show();
    }
  });
  jQuery("input[name='year-filter']").change(function (e) {
    if (this.value == "*") {
      $('.card-equipment').show();
    } else {
      $('.card-equipment').hide();
      $('.card-equipment.' + slugify(this.value)).show();
    }
  });
  jQuery("input[name='makes-filter']").change(function (e) {
    if (this.value == "*") {
      $('.card-equipment').show();
    } else {
      $('.card-equipment').hide();
      $('.card-equipment.' + slugify(this.value)).show();
    }
  });
});

function slugify(text) {
  return text.toString().toLowerCase().replace(/\s+/g, '-') // Replace spaces with -
  .replace('/', '-') // Replace spaces with -
  .replace(/[^\w\-]+/g, '') // Remove all non-word chars
  .replace(/\-\-+/g, '-') // Replace multiple - with single -
  .replace(/^-+/, '') // Trim - from start of text
  .replace(/-+$/, ''); // Trim - from end of text
}

function addradiobutton(type, text, targ) {
  var label = document.createElement("label");
  var element = document.createElement("input");
  var span = document.createElement("div");
  var span2 = document.createElement("div"); //Assign different attributes to the element.

  element.setAttribute("type", type);
  element.setAttribute("value", text);
  element.setAttribute("name", targ);
  label.setAttribute("class", "filter-container");
  span.setAttribute("class", "checkmark");
  span2.setAttribute("class", "filter-label");
  span2.innerHTML += text;
  label.appendChild(element);
  label.appendChild(span);
  label.appendChild(span2);
  var foo = document.getElementById(targ);

  if (jQuery(foo).length) {
    //Append the element in page (in span).
    foo.appendChild(label);
  }
}
"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

(function ($) {
  if (!$) {
    return;
  } ////////////
  // Plugin //
  ////////////


  $.fn.headroom = function (option) {
    return this.each(function () {
      var $this = $(this),
          data = $this.data('headroom'),
          options = _typeof(option) === 'object' && option;
      options = $.extend(true, {}, Headroom.options, options);

      if (!data) {
        data = new Headroom(this, options);
        data.init();
        $this.data('headroom', data);
      }

      if (typeof option === 'string') {
        data[option]();

        if (option === 'destroy') {
          $this.removeData('headroom');
        }
      }
    });
  }; //////////////
  // Data API //
  //////////////


  $('[data-headroom]').each(function () {
    var $this = $(this);
    $this.headroom($this.data());
  });
})(window.Zepto || window.jQuery);
// Avoid `console` errors in browsers that lack a console.
"use strict";
/*
// Tab Hover Style
jQuery(document).ready(function($) {
       
  jQuery("#brandt-model-slider").hide();
  
  jQuery("#brandt-model-slider").imagesLoaded(function () {
    
    jQuery("#brandt-model-slider").owlCarousel({
        loop: true,
        center: true,
        items: 1,
        margin:10,
        nav: false,
        lazyLoad: true,
        video: true,
        dots: false,
        autoHeight: true,
        URLhashListener:true,
        autoplayHoverPause:true,
        startPosition: 'URLHash',
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
    
    jQuery("#brandt-model-slider").show();
  
  }); 
  
 });
 
*/

jQuery(window).on('elementor/frontend/init', function () {
  elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
    jQuery("#brandt-model-slider").hide();
    jQuery("#brandt-model-slider").imagesLoaded(function () {
      jQuery("#brandt-model-slider").owlCarousel({
        loop: true,
        center: true,
        items: 1,
        margin: 10,
        nav: false,
        lazyLoad: true,
        video: true,
        dots: false,
        autoHeight: true,
        URLhashListener: true,
        autoplayHoverPause: true,
        startPosition: 'URLHash',
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 1
          }
        }
      });
      jQuery("#brandt-model-slider").show();
    });
  });
});
// Avoid `console` errors in browsers that lack a console.
"use strict";

jQuery(window).on('elementor/frontend/init', function () {
  elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
    jQuery("#brandt-product-slider").hide();
    jQuery("#brandt-product-slider").imagesLoaded(function () {
      jQuery("#brandt-product-slider").owlCarousel({
        loop: true,
        center: true,
        items: 1,
        margin: 10,
        nav: true,
        lazyLoad: true,
        video: true,
        dots: true,
        autoHeight: true,
        //autoWidth:true,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 1
          }
        }
      });
      jQuery("#brandt-product-slider").show();
    });
  });
});
"use strict"; // Tab Hover Style

jQuery(document).ready(function ($) {
  jQuery("#brandt-tabs").hide();
  jQuery(function () {
    jQuery("#brandt-tabs").tabs({
      active: 0
    });
    jQuery("#brandt-tabs").show();
  });
});
jQuery(window).on('elementor/frontend/init', function () {
  console.log(elementorFrontend.hooks); // ok

  elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
    jQuery("#brandt-tabs").tabs({
      active: 0
    });
  });
});