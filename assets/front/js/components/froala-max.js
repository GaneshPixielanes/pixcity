jQuery(document).ready(function() {

    var countWords;

    function countWordsIn(text){
        var countWords = 0;
        if(text){
            text = text.replace(/'/g," ");
            text = text.replace(/(^\s*)|(\s*$)/gi,"");//exclude  start and end white-space
            text = text.replace(/[ ]{2,}/gi," ");//2 or more space to 1
            text = text.replace(/\n /,"\n"); // exclude newline with a start spacing
            countWords = text.split(' ').filter(function(str){return str!="";}).length;
        }

        return countWords;
    }

    function updateValidator(){
        $("form[name='user']").valid();
    }

    //---------------------------------------------------
    // Count words Like

    $likeContent = $("#user_pixie_likeText");

    if($likeContent.length > 0) {
        var likeContentText;

        $likeContent.on('froalaEditor.contentChanged', function (e, editor) {
            updateLikeWordCounter();
        });
        $likeContent.on('froalaEditor.initialized', function (e, editor) {
            updateLikeWordCounter();
        });

        function updateLikeWordCounter() {
            likeContentText = $($likeContent.froalaEditor('html.get')).text();
            countWords = countWordsIn(likeContentText);
            $(".count-words-status.like").toggleClass("valid", countWords <= 500);
            $(".count-words-status.like .current").text(countWords);
            $likeContent.valid();
        }
    }


    //---------------------------------------------------
    // Count words Dislike

    $dislikeContent = $("#user_pixie_dislikeText");

    if($dislikeContent.length > 0) {
        var dislikeContentText;

        $dislikeContent.on('froalaEditor.contentChanged', function (e, editor) {
            updateDislikeWordCounter();
        });
        $dislikeContent.on('froalaEditor.initialized', function (e, editor) {
            updateDislikeWordCounter();
        });

        function updateDislikeWordCounter() {
            dislikeContentText = $($dislikeContent.froalaEditor('html.get')).text();
            countWords = countWordsIn(dislikeContentText);
            $(".count-words-status.dislike").toggleClass("valid", countWords <= 500);
            $(".count-words-status.dislike .current").text(countWords);
            $dislikeContent.valid();
        }
    }


});