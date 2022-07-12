  var $ = jQuery;
  var $win = $(window);
  var $grid = $('#grid').isotope({
                itemSelector: '.item',
              });

  var $imgs = $("img.lazy");
  var content = [];
  //var limit = 24;
  var equipment = [];
  var dataLoaded = [];
  var equipmentFiltered =[];
  var page = 0;
  var total = 0
  var pageIndex = 1;
  var pageSize = 500;
  var length = 12;
  var filterTemplate = '';
  var aYears = [];
  var aMakes = [];
  var aPrices = [];
  var aCats = [];
  var url = 'https://www.camex.com/camex-api/?SubCode=';
  	
  jQuery(document).ready(function($){ 
  	var dataType = $('#camex-dataSource').val();
  	var data = $('#camex-productData').val();
  	
  	if(dataType === "data-csv"){
    	url = 'https://www.camex.com/camex-api/?csv=';
  	} else {
    	//data-subcode
    	url = 'https://www.camex.com/camex-api/?SubCode=';
  	}
  	
  	// Make Server Request
    $.ajax({
      url: url + data
    })
    .done(function( data ) {
      content = data;
      data.forEach( function(item) {
        aYears.push( item.Year );
        aMakes.push( item.Make );
        aPrices.push(item.TopPrice);
        aCats.push(item.Name);
      });
      showServerData();
      updateFilters();
    });
      
    function showServerData() {
        var pageIndex = pageIndex || 0
        var startIndex = pageIndex *  pageSize;
        var endIndex = startIndex + pageSize;
        var equipment = content.slice(startIndex, endIndex);
        createCards( equipment )
    }
    
    function createCards( data ){
      
      if( data.length > 0) {
      data.forEach( function(item) {
      
        if( item.WebDisplay === 'Yes' )  {
        
          var price =  accounting.formatMoney( parseFloat( item.TopPrice.replace(/[^0-9-.]/g, '')));
          var image = item.thumb || item.icon
          var flag = getFlag(item.Availability, item.Marketing, item.DealPendingExpire);
          
          var $card = $(`<div class="item ${item.EquipmentId} ${item.Year} ${ slugify(item.Name)} ${ slugify(item.ClassName)} ${slugify(item.Make)} ${ slugify( item.NewUsed ) }">
          	 ${flag}
          	<a href="${item.url}" class="card">
            	
            	<div class="img-wrap">
                <img class="lazy" src="${item.icon}" data-original="${image}">        
            	</div>
      
              <aside class="card-content">
                
                <div class="card-header">
                  <h2 class="product-name">${item.ShortName}</h2>
                  <h3 class="model-description">${item.ModelDescription}</h3>
                </div>
                
                <p class="product-detail">
                    <strong>EQUIPMENT ID:</strong> ${item.EquipmentId} <br>
                    <strong>NEW/USED:</strong> ${item.NewUsed} <br>
                    <strong>MILEAGE:</strong> ${item.Mileage} <br>
                    <strong>HOURS:</strong> ${item.Hours} <br>
                    <strong>ENGINE:</strong> ${item.EngineSize}</p>
                <hr>
                
                
                
                <div class="card-footer">
                    <h4 class="product-price">${price}<span class="currency">CAD</span></h4>
                  <button class="btn btn-ghost">View Product</button>
                </div>
                
              </aside>
              </a></div>`);
                 
                
              
              $('#grid').append( $card ).isotope( 'appended', $card ).isotope('on', 'layoutComplete', function () {
                loadVisible($imgs, 'lazylazy');
              });
              
              $("img.lazy").lazyload({
                            failure_limit: Math.max($imgs.length - 1, 0),
                            effect : "fadeIn",
                            threshold : 200
                          });
              
              $('#grid').imagesLoaded().progress( function() {
                $grid.isotope('layout');
              });
        }
       
      }); 
      }
      
    }
                  
  $grid.isotope({
    itemSelector: '.item',
    percentPosition: true,
    layoutMode: 'fitRows',
    onLayout: function() {
      $win.trigger("scroll");
    }
  });
/*
  
  $imgs.lazyload({
    failure_limit: Math.max($imgs.length - 1, 0),
    effect : "fadeIn",
    threshold : 500
  });
*/

  function getFlag( availability, marketing = '', dealPending='' ) {
    
    var output = null;
    var flagName = availability;
    var flagSlug = null;
    
    if(dealPending != '' && dealPending != '1900-01-01 00:00:00'){
       flagName = 'Deal Pending';
    } else if(marketing != '') {
      flagName = marketing;
    } else {
      flagName = availability;
    }
    flagSlug = slugify(flagName);
    output  = `<div class='flag flag-${flagSlug}'>${flagName}</div>`;
    return  output;
  }
  
  function loadVisible( $els, trigger ) {
    $els.filter( function () {
      var rect = this.getBoundingClientRect();
      return rect.top >= 0 && rect.top <= window.innerHeight;
    }).trigger(trigger);
  }

/*
  $grid.isotope('on', 'layoutComplete', function () {
    loadVisible($imgs, 'lazylazy');
  });
*/

  
 

  var filters = {};

  $('.filter').on( 'change',  function() {
    if(dataType === "data-csv"){
     filters[ "class" ]  = $( "#class-filter" ).val();
    }
    filters[ "type" ]   = $( "#type-filter" ).val();
    filters[ "year" ]   = $( "#year-filter" ).val();
    filters[ "make" ]   = $( "#make-filter" ).val();
    filters[ "cat" ]    = $( "#cat-filter" ).val();
    
    // combine filters
    var filterValue = concatValues( filters );
    $grid.isotope({ filter: filterValue });
  
  });

  // flatten object by concatting values
  function concatValues( obj ) {
    var value = '';
    for ( var prop in obj ) {
      value += obj[ prop ];
    }
    return value;
  }

  function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace('/', '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
  }
      
  function updateFilters() {
        
        var unique = aYears.filter(function(elem, index, self) {
          return index == self.indexOf(elem);
        });
        
        unique.sort();
        
        var sel = document.getElementById('year-filter');
        
        for(var i = 0; i < unique.length; i++) {
            var opt = document.createElement('option');
            opt.innerHTML = unique[i];
            opt.value = '.'  + unique[i];
            sel.appendChild(opt);
        }
        
        var uniqueMakes = aMakes.filter(function(elem, index, self) {
            return index == self.indexOf(elem);
        })    
        
        uniqueMakes.sort();
        
        var sel = document.getElementById('make-filter');
        
        for(var i = 0; i < uniqueMakes.length; i++) {
            var opt = document.createElement('option');
            opt.innerHTML =uniqueMakes[i];
            opt.value = '.'  +  slugify(uniqueMakes[i]);
            sel.appendChild(opt);
        }
        
        var uniqueCat = aCats.filter(function(elem, index, self) {
            return index == self.indexOf(elem);
        })  
        
        uniqueCat.sort();
        
        var sel = document.getElementById('cat-filter');
        
        for(var i = 0; i < uniqueCat.length; i++) {
            var opt = document.createElement('option');
            opt.innerHTML = uniqueCat[i];
            opt.value = '.'  + slugify(uniqueCat[i]);
            sel.appendChild(opt);
        }
      }
});