// disable all tabs
$('[data-toggle=tab]').click(function () {
  return false;}
).addClass("text-muted");

var validated = function(tab){
  tab.unbind('click').removeClass('text-muted').addClass('green');
}

//validate inputs on click of button
$('.btn-ok').click(function(){
    
    var allValid = true;
    // get each input in this tab pane and validate
    $(this).parents('.tab-pane').find('.form-control').each(function(i,e){
         
        // some condition(s) to validate each input
        var len = $(e).val().length;
        if (len>0){
            // validation passed
            allValid = true;
        } else {
            // validation failed
            allValid = false;
        }
       
    });
  
    if (allValid) {
        var tabIndex = $(this).parents('.tab-pane').index();
        validated($('[data-toggle]').eq(tabIndex+1));
    }
    
});

// always validate first tab
validated($('[data-toggle]').eq(0));

// form submit
$( "#myForm" ).submit(function( event ) {
  console.log("Handler for .submit() called..");
  console.log( $( this ).serialize() );
  event.preventDefault();
});


// NEXT TAB BTN

$('#btnReview').click(function(){
  $('.nav-tabs > .active').next('li').find('a').trigger('click');
});