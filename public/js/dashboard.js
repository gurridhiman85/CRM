$(document).ready(function () {


    /*$('.upcoming').html('<img src="/elite/img/ajax-loader.gif" alt="please wait..." style="width: 100px; position: absolute; left: 50%; top: 50%; margin-left: -50px; margin-top: -50px;display: block;"/>');
     $('.done').html('<img src="/elite/img/ajax-loader.gif" alt="please wait..." style="width: 100px; position: absolute; left: 50%; top: 50%; margin-left: -50px; margin-top: -50px;display: block;"/>');
     $.ajax({
     // The URL for the request
     url: "/dashboard/pending?v="+new Date().getTime(),
     type: "GET",
     // The type of data we expect back
     dataType: "json",
     })
     // Code to run if the request succeeds (is done);
     // The response is passed to the function
     .done(function (json) {
     $('.upcoming').html('');
     $('.done').html('');
     var upcomingappend = "";
     var doneappend = "";
     upcomingappend += "<table id='upcoming' class='display task-table' cellspacing='0' width='100%'><thead><tr><th></th><th>Task</th><th>Priority</th><th>Responsible</th><th>Days</th><th>Attachment</th><th>By</th><th>%</th><th>Status</th></tr></thead><tbody>";
     doneappend += "<table id='done' class='display task-table' cellspacing='0' width='100%'><thead><tr><th>#</th><th>Task</th><th>Priority</th><th>Responsible</th><th>Days</th><th>Attachment</th><th>By</th><th>%</th><th>Status</th></tr></thead><tbody>";
     
     if(json.pending.length >= 1 ){
     //console.log(json.pending);
     $.each(json.pending, function(i, item) {
     //console.log(item.time_sort);
     //alert(item.title);
     upcomingappend += '<tr class="plain"><td>'+item.id+'</td><td>'+item.title+'</td><td class="text-center">'+item.priority+'</td><td class="user-name">'+item.assign_user+'</td><td class="text-center" data-order="'+item.time_sort+'">'+item.date+'</td><td class="text-center">'+item.file+'</td><td class="text-center user-name">'+item.created_by+'</td><td class="text-center">'+item.task_progress+'</td><td class="text-center">'+item.status+'</td></tr>';
     
     })				
     }
     
     if(json.done.length >= 1 ){
     $.each(json.done, function(i, item) {
     //alert(item.title);
     doneappend += '<tr class="plain"><td>'+item.id+'</td><td>'+item.title+'</td><td class="text-center">'+item.priority+'</td><td class="user-name">'+item.assign_user+'</td><td class="text-center">'+item.date+'</td><td class="text-center">'+item.file+'</td><td class="text-center user-name">'+item.created_by+'</td><td class="text-center">'+item.task_progress+'</td><td class="text-center">'+item.status+'</td></tr>';
     
     })				
     }
     
     
     upcomingappend += "</tbody></table>";
     doneappend += "</tbody></table>";
     $(".upcoming").append(upcomingappend);
     $(".done").append(doneappend);
     
     
     
     
     $('#upcoming').DataTable({
     dom: 'Bfrtip',
     // "ordering": false,
     order: [[ 4, 'desc' ]],
     "iDisplayLength": 20,
     buttons: []
     });
     
     $('#done').DataTable({
     dom: 'Bfrtip',
     "ordering": false,
     "iDisplayLength": 20,
     buttons: []
     });
     
     })
     // Code to run if the request fails; the raw request and
     // status codes are passed to the function
     .fail(function (xhr, status, errorThrown) {
     $('.upcoming').html('');
     $('.done').html('');
     console.log("Error: " + errorThrown);
     console.log("Status: " + status);
     console.dir(xhr);
     })// Code to run regardless of success or failure;
     
     */
    //$('#origin').html('<img src="/elite/img/ajax-loader.gif" alt="please wait..." style="width: 100px; position: absolute; left: 50%; top: 50%; margin-left: -50px; margin-top: -50px;display: block;"/>');

    var currentDate = new Date()
    var day = currentDate.getDate()
    var month = currentDate.getMonth() + 1
    var year = currentDate.getFullYear()
    var newdate = year + "-" + month + "-" + day;

    $.ajax({
        // The URL for the request
        url: "/dashboard/mydayajax/" + newdate + "?v=" + new Date().getTime(),
        type: "GET",
        // The type of data we expect backnew Date().format('m-d-Y h:i:s');
    })
            // Code to run if the request succeeds (is done);
            // The response is passed to the function
            .done(function (json) {
                $('#origin').html(json);
            })
            // Code to run if the request fails; the raw request and
            // status codes are passed to the function
            .fail(function (xhr, status, errorThrown) {
                $('.upcoming').html('');
                $('.done').html('');
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            })// Code to run regardless of success or failure;

})