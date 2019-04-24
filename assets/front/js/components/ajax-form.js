jQuery(document).ready(function() {

    var loading = false;

    $("form.ajax-submit").each(function(){

       $(this).submit(function(e){
           e.preventDefault();

           loading = true;
           var $form = $(this);
           var url = $(this).attr("action");
           var afterAction = $(this).attr("data-after");

           $.ajax({
               type: "PUT",
               url: url,
               data: $(this).serialize()
           }).done(function(res){

           }).always(function(){
               loading = false;

               switch(afterAction){
                   case "closeModal":
                       $form.parents(".modal").modal("hide");
                       break;
}
           });
       });

    });

});