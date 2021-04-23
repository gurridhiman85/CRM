
$(document).ready(function () {

    $("#checkbox_allday").on("change", function (e) {
        var checked = $(this).prop("checked");
        if (checked) {
            $(this).parents("form").addClass("checkbox_allday_checked");
        } else {
            $(this).parents("form").removeClass("checkbox_allday_checked");
        }
    });

//     $("#new-event-modal").on("click", function (e) {
//         alert("dgfhdfg");
//     });
// $("#side-popup").on("click", function (e) {
//         alert("dgfhdfg");
//     });

    $("#checkbox_remindme").on("change", function (e) {
        var checked = $(this).prop("checked");
        if (checked) {
            $(this).parents("form").addClass("checkbox_remindme_checked");
        } else {
            $(this).parents("form").removeClass("checkbox_remindme_checked");
        }
    });

    ACFn.add_event_form = function (F, R) {
        if (R.success) {
            F[0].reset();
            // $("#new-event-modal").modal('hide');
            $("#new-event-modal").removeClass("in");
            $("#new-event-modal").addClass("out");
            $("#new-event-modal").removeClass("bk-overlay");
            $("#new-event-modal").hide(  );
            swal("New Event Created", '', 'success');
            if ($("#events-calendar").length > 0) {
                $("#events-calendar").fullCalendar('refetchEvents');
            }
            if ($("#home-calendar").length > 0) {
                $("#home-calendar").fullCalendar('refetchEvents');
            }
        } else {
            ACFn.display_errors(F, R);
        }
    };
});