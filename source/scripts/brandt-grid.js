'use strict';
jQuery(document).ready(function($) {
    
    if ( jQuery( '#year-filter' ).length ) {
	    if(typeof equipment_years !== 'undefined'){
	      var unique_years = equipment_years.filter(function(elem, index, self) { 
				return index == self.indexOf(elem); 
			});
		
			unique_years.sort();
		
			for(var i = 0; i < unique_years.length; i++) {
				addradiobutton("radio", unique_years[i], 'year-filter');
			}   
	    }
	}
	
	if ( jQuery( '#makes-filter' ).length ) {
		if(typeof equipment_makes !== 'undefined'){
			var unique_makes = equipment_makes.filter(function(elem, index, self) { 
				return index == self.indexOf(elem); 
			});
			
			unique_makes.sort();
			for(var u = 0; u < unique_makes.length; u++) {
				addradiobutton("radio", unique_makes[u], 'makes-filter');
			}
		}
	}
	
	if ( jQuery( '#cats-filter' ).length ) {
		if(typeof equipment_cats !== 'undefined') {
			var unique_cats = equipment_cats.filter(function(elem, index, self) { 
				return index == self.indexOf(elem); 
			});
			
			unique_cats.sort();
			
			for(var j = 0; j < unique_cats.length; j++) {
				addradiobutton("radio", unique_cats[j], 'cats-filter');
			}
		} 
	}
	
	
	jQuery("input[name='cats-filter']").change(function(e){
		
		if(this.value == "*"){
			$('.card-equipment').show();
		} else{
			$('.card-equipment').hide();
			$('.card-equipment.'+slugify(this.value)).show();
		}
	});
	
	jQuery("input[name='year-filter']").change(function(e){
		
		if(this.value == "*"){
			$('.card-equipment').show();
		} else{
			$('.card-equipment').hide();
			$('.card-equipment.'+slugify(this.value)).show();
		}
	});
	
	jQuery("input[name='makes-filter']").change(function(e){
		if(this.value == "*"){
			$('.card-equipment').show();
		} else{
			$('.card-equipment').hide();
			$('.card-equipment.'+slugify(this.value)).show();
		}
	});
	
	
});

function slugify(text) {
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace('/', '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}

function addradiobutton(type, text, targ) {
	var label = document.createElement("label");
	
	var element = document.createElement("input");
	
	var span = document.createElement("div");
	var span2 = document.createElement("div");
	//Assign different attributes to the element.
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
	if ( jQuery(  foo ).length ) {
		//Append the element in page (in span).
		foo.appendChild(label);
	}
}
