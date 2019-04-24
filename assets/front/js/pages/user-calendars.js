const fullcalendar = require('../vendors/fullcalendar.min');

jQuery(document).ready(function() {

    var $apiRoutes = $("#api-routes");
    var apiRouteCalendars = $apiRoutes.attr("data-calendars-route");
    var apiRouteNewCalendar = $apiRoutes.attr("data-new-calendar-route");
    var apiRouteDeleteCalendar = $apiRoutes.attr("data-delete-calendar-route");
    var apiRouteNewCalendarEvent = $apiRoutes.attr("data-new-calendar-event-route");
    var apiRouteUpdateCalendarEvent = $apiRoutes.attr("data-update-calendar-event-route");
    var apiRouteDeleteCalendarEvent = $apiRoutes.attr("data-delete-calendar-event-route");

    var $calendar = $("#agenda");
    var $createCalendarForm = $("#createAgenda");
    var $selectCalendar = $("#select-calendar");
    var $selectCalendarModal = $("#select-calendar-in-modal");

    var $modalAddCard = $(".modal-agenda-add-card");
    var $modalConfirmDeleteCalendar = $(".modal-confirm-delete");

    var calendarLoading = false;
    var selectedDate;

    $calendar.fullCalendar({
        firstDay: 1,
        lang: 'fr',
        dayNamesShort: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        monthNames:["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"],
        header: {
            left: '',
            center: 'prev,title,next',
            right: ''
        },

        editable: true,
        droppable: true,

        drop: function(date, jsEvent, ui, resourceId) {
            addEvent(date, [$(this).attr("data-id")]);
        },

        eventDrop: function(event, delta, revertFunc, jsEvent, ui, view){
            updateEvent(event.start.format(), (event.end)?event.end.format():event.start.format(), event.calendarId, event.eventId);
        },

        eventResize: function(event, delta, revertFunc, jsEvent, ui, view){
            updateEvent(event.start.format(), event.end.format(), event.calendarId, event.eventId);
        },

        viewRender: function(view, element){
            refreshCalendars(view.start.format(), view.end.format());
        },

        eventClick: function(event, jsEvent, view) {
            if(!$(jsEvent.target).hasClass("delete-event")) {
                window.open(event.url, '_blank', 'width=700,height=600');
            }
            else{
                deleteEvent(event.calendarId, event.eventId);
                console.log(event);
                $calendar.fullCalendar('removeEvents', event._id);
            }

            return false;
        },

        windowResize: function(){
            updateLeftScroll();
        },

        dayClick: function(date, jsEvent, view) {
            selectedDate = date;
            $modalAddCard.find(".selected-date").text(date.format("DD MMMM YYYY"));
            $modalAddCard.modal()
        },

        eventMouseover: function(event, jsEvent, view){

        },

        eventDragStop: function(event, jsEvent, ui, view) {

        },

        eventRender: function(event, element) {
            element.append( "<span class='delete-event' title='Supprimer'>X</span>" );
        }

    });


    //---------------------------------------------
    // Display calendar events
    //---------------------------------------------

    function refreshCalendars(from, to){

        if($(".col-right-agenda").hasClass("not-auth")) return false;

        calendarLoading = true;
        $calendar.addClass("loading");

        $.ajax({
            type: "GET",
            url: apiRouteCalendars,
            data: {
                calendar: $("#select-calendar").val(),
                from: from,
                to: to
            }
        }).done(function (res) {
            updateEvents(res);
            updateHeader(res);
        }).always(function(){
            calendarLoading = false;
            $calendar.removeClass("loading");
        });

    }


    //---------------------------------------------
    // Create calendar
    //---------------------------------------------

    var $modalCreateCalendar = $(".modal-create-calendar");

    $createCalendarForm.submit(function(e){
        e.preventDefault();
        $modalCreateCalendar.modal("hide");

        $.ajax({
            type: "POST",
            url: apiRouteNewCalendar,
            data: {
                name: $createCalendarForm.find("input[name='agenda']").val()
            }
        }).done(function (res) {

            $createCalendarForm.find("input[name='agenda']").val("");

            $selectCalendar.append($('<option>', {
                value: res.id,
                text: res.summary
            }));

            $selectCalendar.val(res.id).trigger("change");

            reloadEvents();
        }).always(function(){

        });
    });

    function updateEvents(calendars){

        $calendar.fullCalendar("removeEvents");

        var events = [];
        calendars.forEach(function(calendar){
            events = [];

            calendar.events.forEach(function(event){
                events.push({
                    title: event.summary,
                    start: event.start.date,
                    end: event.end.date,
                    color: calendar.calendar.backgroundColor,
                    textColor: calendar.calendar.foregroundColor,
                    //url: event.htmlLink,
                    url: event.description,
                    eventId: event.id,
                    calendarId: calendar.calendar.id
                });
            });

            $calendar.fullCalendar("renderEvents", events);

        });

    }

    function updateHeader(calendars){

    }


    //---------------------------------------------
    // Delete calendar
    //---------------------------------------------

    $(".delete-current-calendar").click(function(e){
        e.preventDefault();

        $modalConfirmDeleteCalendar.modal("hide");

        $.ajax({
            type: "POST",
            url: apiRouteDeleteCalendar,
            data: {
                calendar: $("#select-calendar").val(),
            }
        }).done(function (res) {

            if(!res.error) {
                $("#select-calendar option[value='" + $("#select-calendar").val() + "']").remove();
                $("#select-calendar").trigger("change");
            }
            else{
                alert("Impossible de supprimer ce calendrier");
            }

        }).always(function(){

        });
    });


    //---------------------------------------------
    // Add new events
    //---------------------------------------------

    function addEvent(date, cardIds, calendar){

        calendar = (typeof calendar === 'undefined') ? $("#select-calendar").val() : calendar;

        if($(".col-right-agenda").hasClass("not-auth")) return false;

        var $selectedCalendarOption = $selectCalendar.find("option[value='"+calendar+"']");

        $.ajax({
            type: "POST",
            url: apiRouteNewCalendarEvent,
            data: {
                calendar: calendar,
                cardIds: cardIds,
                dateFrom: date.format(),
                dateTo: date.format()
            }
        }).done(function (res) {

            if(res){
                res.forEach(function(event){
                    $calendar.fullCalendar("renderEvents", [{
                        title: event.summary,
                        start: date,
                        textColor: $selectedCalendarOption.attr("data-color"),
                        color: $selectedCalendarOption.attr("data-bg-color"),
                        //url: event.htmlLink,
                        url: event.description,
                        eventId: event.id,
                        calendarId: calendar
                    }]);
                })
            }


        }).always(function(){

        });

    }


    //---------------------------------------
    // Update event
    //---------------------------------------

    function updateEvent(from, to, calendarId, eventId){

        if($(".col-right-agenda").hasClass("not-auth")) return false;

        $.ajax({
            type: "POST",
            url: apiRouteUpdateCalendarEvent,
            data: {
                calendar: calendarId,
                event: eventId,
                dateFrom: from,
                dateTo: to
            }
        }).done(function (res) {

        }).always(function(){

        });

    }


    //---------------------------------------
    // Delete event
    //---------------------------------------

    function deleteEvent(calendarId, eventId){

        $.ajax({
            type: "POST",
            url: apiRouteDeleteCalendarEvent,
            data: {
                calendar: calendarId,
                event: eventId
            }
        }).done(function (res) {

        }).always(function(){

        });

    }


    //---------------------------------------
    // View or Edit mode
    //---------------------------------------

    var calendarMode = "view";
    var $calendarContainer = $(".calendar-container");

    function setCalendarMode(mode){ // view or edit
        $calendarContainer.removeClass("mode-view");
        $calendarContainer.removeClass("mode-edit");

        calendarMode = mode;
        $calendarContainer.addClass("mode-"+mode);
    }

    //---------------------------------------
    // Change calendar
    //---------------------------------------

    var currentCalendar = "";

    $selectCalendar.change(function(){

        currentCalendar = $(this).val();

        if(currentCalendar !== "") {
            $selectCalendarModal.val(currentCalendar);
            setCalendarMode("edit");
        }
        else{
            setCalendarMode("view");
        }

        reloadEvents();
    });

    function reloadEvents(){
        var view = $calendar.fullCalendar("getView");
        refreshCalendars(view.start.format(), view.end.format());
    }


    //---------------------------------------
    // Filter by collection
    //---------------------------------------

    $("#select-collections, #select-collections-in-modal").change(function(){
       var collectionId = $(this).val();

        $("#select-collections, #select-collections-in-modal").val(collectionId);

       if(collectionId !== "") {
           setViewMode("collections");
           $(".collection-row").hide();
           $(".collection-row[data-id='" + collectionId + "']").show();
       }
       else{
           $(".collection-row").show();
       }
    });

    //---------------------------------------
    // Filter by category
    //---------------------------------------

    $("#select-category, #select-category-in-modal").change(function(){
       var categoryId = $(this).val();

        $("#select-category, #select-category-in-modal").val(categoryId);

       if(categoryId !== "") {
           setViewMode("cards");
           $(".view-mode-cards .card").hide();
           $(".view-mode-cards .card.cat-" + categoryId).show();
       }
       else{
           $(".view-mode-cards .card").show();
       }
    });


    //---------------------------------------
    // Draggable
    //---------------------------------------

    $(".col-left-agenda .card").draggable({
        helper: 'clone',
        appendTo: 'body',
        zIndex: 1000,
        revert: true,
        revertDuration: 100,
        cursorAt: { left: 25, top: 25 }
    });


    //---------------------------------------
    // Change view mode
    //---------------------------------------

    $(".switch-list-mode").click(function(e){
        e.preventDefault();

        $("#select-collections, #select-collections-in-modal").val("").trigger("change");
        $("#select-category, #select-category-in-modal").val("").trigger("change");

        var mode = $(this).attr("data-type");

        setViewMode(mode);

    });

    function setViewMode(mode){
        $(".switch-list-mode").removeClass("active");
        $(".switch-list-mode[data-type='"+mode+"']").addClass("active");

        $("[class*='view-mode-']").hide();
        $("[class*='view-mode-"+mode+"']").show();
    }

    function updateLeftScroll(){
        $(".col-left-cards-list-scroll").each(function(){
           $(this).height($(".fc-view-container").height() - $(this).prev(".content-above-scroll").outerHeight() - $(".col-left-agenda .btnTop").outerHeight() - 20);
        });
    }

    updateLeftScroll();


    //---------------------------------------------
    // Add events modal
    //---------------------------------------------

    var collectionCards = [];

    function resetCollection(){
        collectionCards = [];
        applyCollectionChanges();
        updateView();
    }

    function updateView(){
        $(".collCheck input").each(function(){
            $(this).prop("checked", isInCollection($(this).val()));
        });
    }

    function addCardToCollection(id){
        collectionCards.push(id);
    }

    function removeCardFromCollection(id){
        collectionCards = collectionCards.filter(function(item) {
            return item !== id
        });
    }

    function isInCollection(id){
        return collectionCards.indexOf(id) !== -1;
    }

    $(document).on("change", "input[name='selectcard[]']", function(){
        var id = $(this).val();
        if($(this).prop("checked")){
            addCardToCollection(id);
        }
        else{
            removeCardFromCollection(id);
        }

        applyCollectionChanges();
    });

    function applyCollectionChanges(){
        $(".collection-total-cards").text(collectionCards.length);
    }

    $("#add-modal-events").click(function(e){
        e.preventDefault();
        addEvent(selectedDate, collectionCards, $("#select-calendar-in-modal").val());
        $modalAddCard.modal("hide");
        resetCollection();
    });



});

