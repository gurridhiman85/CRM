$(document).ready(function () {
    if (("WebSocket" in window) && typeof wsNotificationUrl != 'undefined' && wsNotificationUrl && notificationUserId && notificationKey) {
        var notificationWebsocket = new WebSocket(wsNotificationUrl);
        var obj = {
            from: notificationUserId,
            appkey: notificationKey
        };
        // console.log(notificationWebsocket);
        notificationWebsocket.onopen = function (websocket) {
            // console.log(websocket);
            // Web Socket is connected, send data using send()
            notificationWebsocket.send(JSON.stringify(obj));
            // alert("Message is sent...");
        };

        notificationWebsocket.onmessage = function (event) {
            var json = {};
            try {
                json = JSON.parse(event.data);
            } catch (e) {

            }
            console.log(json);
            if (typeof (json.register) != 'undefined' && json.register == 'success') {
                loadPreNotifications();
            } else if (typeof (json.type) != 'undefined'&&json.type=='notification') {
                addMessageToList(json);
            }
        };

        notificationWebsocket.onclose = function(e) {
            console.log("Wesocket Closed");
            console.log(e);

        }

        window.onbeforeunload = function (event) {
            notificationWebsocket.close();
        };

    }

    function loadPreNotifications() {
        $.ajax({
            url: notificationUrl + 'get/notification',
            // dataType: "jsonp",
            data: {
                to: notificationUserId,
                appkey: notificationKey,
                limit: 10
            },
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                if (!data) {
                    return;
                }
                try {
                    var json = data;// JSON.parse(data);
                    // console.log(json);
                    $.each(json.notifications, function (index, notification) {
                        addMessageToList(notification);
                    });
                } catch (e) {

                }
            },
            complete: function(){
                setTimeout(markNotificationsRead, 2000);
            }
        })
        // http://local.crm:5000/get/notification?to=108&appkey=&limit=5&startdate=

        // setInterval(markNotificationsRead, 5000);
    }

    function sendReadNotifications($visibleids, callback) {
        console.log($visibleids.toString());
        $.ajax({
            url: notificationUrl + 'read/notification',
            data: {
                readby: notificationUserId,
                appkey: notificationKey,
                type: 'seen'
                // id: JSON.stringify($visibleids),
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if(typeof (callback) == 'function') {
                    callback(data);
                }

            },
            complete: function () {
                setTimeout(markNotificationsRead, 5000);
            }
        })
        // http://local.crm:5000/get/notification?to=108&appkey=&limit=5&startdate=
    }

    function markNotificationsRead() {
        var $divs = $("#appendwsnotification").children(".notification_message_outer").not(".is_seen");
        var $visibleids = [];
        $divs.each(function (index, element) {
            if ($(element).is(":visible")) {
                $visibleids.push($(element).data('id'));
                // $(element).addClass("is_seen");
            }
        });
        if ($visibleids.length) {
            sendReadNotifications($visibleids, function(data){
                console.log($visibleids);
                $visibleids.forEach(function (id) {
                    console.log(id);
                    $("#notification_"+id).addClass("is_seen");
                });
            });
        } else {
            setTimeout(markNotificationsRead, 3000);
        }
        // console.log($visibleids);
    }

    function addMessageToList(message) {
        //"{"unid":"","subject":"Ticket created: fgmjgh in project: Ambani HMS Rental","connection":"true","from":"","to":"1,108,1","type":"notification","message":"New Ticket Created","is_seen":0}
        // console.log(message);
        var count = $("#countzero").html();
        if (!count) {
            count = 0;
        }


        // $("#appendwsnotification").prepend(JSON.stringify(message));
        $notiobj = $("" + notification_template_default);
        $notiobj.find('.mail-desc').html(message.subject);
        $notiobj.find('.mail-desc').attr('title', message.message+'\n'+message.subject);
        $notiobj.attr('id', 'notification_'+message.id);
        $notiobj.addClass('notification_message_outer');
        $notiobj.data('id', message.id);
        if(message.from) {
            $notiobj.find('.user-img img').attr('src', notification_user_img+'/'+message.from);
        } else {
            $notiobj.find('.user-img img').attr('src', notification_user_img+'/4');
        }
        // console.log(message.is_seen);
        if (message.is_seen == 1) {
            $notiobj.addClass("is_seen");
        } else {
            count = parseInt(count) + 1;
        }
        if (message.show_at) {
            $notiobj.find('.time').html(message.show_at);
        }
        $("#appendwsnotification").prepend($notiobj);
        $("#countzero").html(count);
    }
});