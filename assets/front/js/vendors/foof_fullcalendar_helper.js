    (function($) {
        $.fn.getDayCell = function(mouseEvent) {
            var calendarId = $(this).attr('id');
            var days = $('#'+calendarId + ' .fc-day');
            for(var i = 0 ; i < days.length ; i++) {
                var day = $(days[i]);
                var mouseX = mouseEvent.pageX;
                var mouseY = mouseEvent.pageY;
                var offset = day.offset();
                var width  = day.width();
                var height = day.height();
                if (   mouseX >= offset.left && mouseX <= offset.left+width 
                    && mouseY >= offset.top  && mouseY <= offset.top+height )
                   return day;
               }
        }
    }(jQuery));

