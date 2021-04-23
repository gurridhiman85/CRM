$(document).ready(function () {




    // 172.16.0.60:8585/chat


    $(".list-toggle-outer").on('click', function () {

        var pe = $(this).parents('.js-cl-outer');
        pe.toggleClass('js-cl-open');
        if (pe.hasClass('js-cl-open')) {
            getContacts();
        }
        alignTabs();
    });

    $(".chat-bar").on('click', '.chat-toggle-outer a.js-bar-minus, .chat-toggle-outer .bar-title', function () {
        var pe = $(this).parents('.chat-tab');
        // console.log(pe.data());
        toggleChatWindow(pe.data('window-id'));
    });

    $(".chat-bar").on('click', '.chat-toggle-outer a.js-bar-close', function () {
        var pe = $(this).parents('.chat-tab');
        // console.log(pe.data());
        closeChatWindow(pe.data('window-id'));
    });

    $(".chat-bar").on('click', '.chat-toggle-outer a.js-bar-participants', function () {
        var pe = $(this).parents('.chat-tab');
        var window_id = pe.data('window-id');
        ACFn.sendAjax('/chat/editgroupform/' + window_id, 'get', {});
        console.log(chatLocalStorage.getChatContactByWindowId(window_id));
    });

    $(".chat-list-user").on('click', ' a.chat-link', function (e) {
        e.preventDefault();
        var window_id = $(this).data('window-id');
        chatLocalStorage.setWindowDataByKey(window_id, 'maximised', true);
        openNewChatWindow(window_id);
    });


    $(".chat-list-group").on('click', ' a.chat-link', function (e) {
        e.preventDefault();
        var window_id = $(this).data('window-id');
        chatLocalStorage.setWindowDataByKey(window_id, 'maximised', true);
        openNewChatWindowGroup(window_id);
    });

    function openTabsOnLoad() {
        var windows = chatLocalStorage.getWindowData();
        $.each(windows, function (window_id, value) {
            // console.log(value);
            if (value && !value.removed) {
                openNewChatWindow(window_id);
                if (value.maximised == true) {
                    maximiseChatWindow(window_id);
                }
            }
        });
    }


    function openNewChatWindow(window_id, force_maximise) {
        if ($("#chat_window_" + window_id).length == 0) {
            chatLocalStorage.setWindowDataByKey(window_id, 'removed', false);
            var data = chatLocalStorage.getChatContactByWindowId(window_id);

            if (!data) {
                return;
            }
            if (data.group_name) {
                openNewChatWindowGroup(window_id, force_maximise);
                return;
            }
            console.log(data);
            var $tmpl = $(chat_tab_template).clone();
            $tmpl.attr('id', 'chat_window_' + window_id);
            $tmpl.data('window-id', window_id);
            $tmpl.find('.bar-title .chat-title').html(data.chat_name);
            $tmpl.find('.js-bar-participants').hide();
            var $textArea = $tmpl.find(".single-chat-form textarea");
            var inimsg = '';
            if (data.accepted == false) {
                if (data.req == 'SN') {
                    inimsg = 'User hasn\'t accepted you request yet!';
                } else {
                    inimsg = '<a href="#" class="chat_accept_request">Accept request!</a>';
                }
            }

            if (inimsg) {
                $tmpl.find('.single-chat-outer').append(inimsg);
            }


            $(".chat-tabs-outer").prepend($tmpl);

            var maximised = chatLocalStorage.getWindowDataValue(window_id, 'maximised');
            if (maximised || maximised === null) {
                maximiseChatWindow(window_id);
            }

            $textArea.emojioneArea({
                emojiPlaceholder: ":smile_cat:",
                // hidePickerOnBlur: false,
                // saveEmojisAs: 'shortname',
                // shortnames: true,
                // useInternalCDN: true,
                // container: '#chat_window_'+window_id,
                events: {
                    keydown: function (editor, e) {
                        console.log('event:keydown');
                        var emojji = $textArea[0].emojioneArea;
                        $textArea.val(emojji.getText());
                        if ((e.shiftKey == false && e.altKey == false && e.ctrlKey == false) && (e.keyCode || e.which) == 13) { //Enter keycode
                            e.preventDefault();
                            // console.log('send');
                            sendChat(window_id);
                            emojji.setText('');
                            $tmpl.find('.emojionearea-button.active .emojionearea-button-close').trigger('click');
                        }
                    }
                }
            });

            // $tmpl.find('.emojionearea-button').append('Test');

            loadMessages(window_id);
            alignTabs();

        } else {

        }
        if (typeof force_maximise != 'undefined' && force_maximise) {
            maximiseChatWindow(window_id);
        }
    }


    function openNewChatWindowGroup(window_id, force_maximise) {
        if ($("#chat_window_" + window_id).length == 0) {
            chatLocalStorage.setWindowDataByKey(window_id, 'removed', false);
            var data = chatLocalStorage.getChatContactByWindowId(window_id);
            console.log(data);
            var $tmpl = $(chat_tab_template);
            $tmpl.attr('id', 'chat_window_' + window_id);
            $tmpl.data('window-id', window_id);
            $tmpl.find('.bar-title .chat-title').html(data.group_name);
            var $textArea = $tmpl.find(".single-chat-form textarea");


            $(".chat-tabs-outer").prepend($tmpl);

            var maximised = chatLocalStorage.getWindowDataValue(window_id, 'maximised');
            if (maximised || maximised === null) {
                maximiseChatWindow(window_id);
            }
            $textArea.emojioneArea({
                emojiPlaceholder: ":smile_cat:",
                // hidePickerOnBlur: false,
                // saveEmojisAs: 'shortname',
                // shortnames: true,
                // useInternalCDN: true,
                // container: '#chat_window_'+window_id,
                events: {
                    keydown: function (editor, e) {
                        console.log('event:keydown');
                        var emojji = $textArea[0].emojioneArea;
                        $textArea.val(emojji.getText());
                        if ((e.shiftKey == false && e.altKey == false && e.ctrlKey == false) && (e.keyCode || e.which) == 13) { //Enter keycode
                            e.preventDefault();
                            // console.log('send');
                            sendChat(window_id);
                            emojji.setText('');
                            $tmpl.find('.emojionearea-button.active .emojionearea-button-close').trigger('click');
                        }
                    }
                }
            });

            loadMessages(window_id);
            alignTabs();
            if (typeof force_maximise != 'undefined' && force_maximise) {
                maximiseChatWindow(window_id);
            }
        }
    }

    function loadMessages(window_id) {
        var data = {
            winid: window_id,
            pubkey: chat_public_key
        };
        $.ajax(chat_url + '/get/chat/messages', {
            data: data,
            dataType: 'json',
            success: function (response) {
                // console.log(response);
                if (response.messages) {
                    $.each(response.messages, function (index, message) {
                        // console.log(message);
                        addChatMessage(message);
                    });
                }
            }
        });
    }

    function closeChatWindow(window_id) {
        var pe = $("#chat_window_" + window_id);
        pe.slideUp(function () {
            pe.remove();
            alignTabs();
        });
        chatLocalStorage.setWindowDataByKey(window_id, 'removed', true);
        chatLocalStorage.setWindowDataByKey(window_id, 'maximised', false);
        // chatLocalStorage.removeOpenedChatWindow(window_id);
    }

    function toggleChatWindow(window_id) {
        var pe = $("#chat_window_" + window_id);
        // pe.toggleClass('chat-tab-open');
        // var opened = false;
        if (pe.hasClass('chat-tab-open')) {
            minimiseChatWindow(window_id);
        } else {
            maximiseChatWindow(window_id);
        }
        // chatLocalStorage.setWindowDataByKey(window_id, 'maximised', opened);
        // chatLocalStorage.setOpenedChatWindowMeta(window_id, 'opened', opened);
    }

    function maximiseChatWindow(window_id) {
        var pe = $("#chat_window_" + window_id);
        pe.addClass('chat-tab-open');
        chatLocalStorage.setWindowDataByKey(window_id, 'unread_count', 0);

        chatLocalStorage.setWindowDataByKey(window_id, 'maximised', true);
        showUnreadCounts();
        alignTabs();
    }

    function minimiseChatWindow(window_id) {
        var pe = $("#chat_window_" + window_id);
        pe.removeClass('chat-tab-open');
        chatLocalStorage.setWindowDataByKey(window_id, 'maximised', false);
        alignTabs();
    }


    function getContacts(callback) {
        $.ajax(chat_url + '/get/chat/contacts', {
            data: {
                chatid: chat_id
            },
            dataType: 'json',
            success: function (data) {
                $.each(data.contacts, function (key, value) {
                    value.contact_type = 'single';
                    chatLocalStorage.addChatContactToList(value);
                    addContactToList(value);
                });
                $.each(data.groups, function (key, value) {
                    value.contact_type = 'group';
                    chatLocalStorage.addChatContactToList(value);
                    addGroupToList(value);
                });
                showUnreadCounts();
                if (typeof callback == 'function') {
                    callback();
                }
            }
        });
    }

    function addContactToList(d) {
        if ($("#link_" + d.winid).length == 0) {
            var $tmpl = $(chat_list_user_template);
            var $chat_link_tmpl = $tmpl.find('.chat-link');
            var $chat_name_tmpl = $tmpl.find('.chat-name');
            $tmpl.attr('id', 'link_' + d.winid);
            $chat_link_tmpl.data('window-id', d.winid);
            $chat_name_tmpl.html(d.chat_name);
            $('.js-chat-list-user').append($tmpl);
        }
    }

    function addGroupToList(d) {
        if ($("#link_" + d.winid).length == 0) {
            var $tmpl = $(chat_list_group_template);
            var $chat_link_tmpl = $tmpl.find('.chat-link');
            var $chat_name_tmpl = $tmpl.find('.chat-name');
            $tmpl.attr('id', 'link_' + d.winid);
            $chat_link_tmpl.data('window-id', d.winid);
            $chat_name_tmpl.html(d.group_name);
            $('.js-chat-list-group').append($tmpl);
        }
    }


    $(".chat-bar").on('keydown', 'textarea', function (e) {
        if ((e.shiftKey == false && e.altKey == false && e.ctrlKey == false) && (e.keyCode || e.which) == 13) { //Enter keycode
            // console.log(e);
            e.preventDefault();
            // console.log('return');
            sendChat($(this).parents(".chat-tab").data('window-id'));
        }
    });

    function sendChat(window_id) {
        var cw = $('#chat_window_' + window_id);
        // var data = chat_contact_list[window_id];
        var contact = chatLocalStorage.getChatContactByWindowId(window_id);
        var textArea = cw.find('textarea');
        var message = textArea.val();

        if (message && message.trim()) {
            textArea.val('');
            chatSocket.send(JSON.stringify({
                chatid: chat_id,
                pubkey: chat_public_key,
                msg: message,
                winid: window_id,
                chat_type: contact.chat_type
            }));
            addChatMessage({
                winid: window_id,
                msg: message,
                // senton: {
                //     $numberLong: moment().format('X')
                // },
                chat_type: 's'
            });
        }
    }

    function sendChatFile(window_id, file_data) {
        var cw = $('#chat_window_' + window_id);
        // var data = chat_contact_list[window_id];
        var contact = chatLocalStorage.getChatContactByWindowId(window_id);
        var fileInput = cw.find('.chat-file-outer input');
        var data = getFileData(fileInput);
        // console.log(data);
        var message = "Sent a File: " + data.name;
        if (message && message.trim()) {

            chatSocket.send(JSON.stringify({
                chatid: chat_id,
                pubkey: chat_public_key,
                file: data,
                winid: window_id,
                chat_type: 'file'
            }));

            chatSocket.send(file_data);

            addChatMessage({
                winid: window_id,
                msg: message,
                // senton: {
                //     $numberLong: moment().format('X')
                // },
                chat_type: contact.chat_type
            });
        }
    }


    function addChatMessage(m) {
        // m.winid;
        var outer = $('#chat_window_' + m.winid + ' .single-chat-outer');
        // console.log(m);
        var window_data = chatLocalStorage.getWindowDataByID(m.winid);
        // console.log(window_data);
        var contact_data = chatLocalStorage.getChatContactByWindowId(m.winid);
        // console.log(window_data);
        if (outer.length == 0) {
            showChatAlert(m);
            if (m.read === false) {
                chatTabUnreadCountPlusPlus(m.winid);
            }
        } else {

            var $tmpl = $(chat_message_template);
            var msg = '';
            if (m.msg) {
                // console.log(m.msg);
                msg = parseChatMessage(m.msg);
                // console.log(m.msg);
            }
            $tmpl.find('.js-chat-msg').html(msg);
            var cls = 'msg-right';
            if (m.by) {
                if (m.by == chat_id || m.chatid == chat_id) {
                    $tmpl.find('.js-msg-user').hide();
                } else {
                    cls = 'msg-left';
                }
            }
            if (m.chat_type == 's') {
                // $tmpl.find('.js-msg-user').hide();
            }

            if (contact_data.contact_type == 'group' && m.by) {
                $tmpl.find('.js-msg-user').html(m.by);
            } else {
                $tmpl.find('.js-msg-user').hide();
            }

            if (m.senton && m.senton.$numberLong) {
                var time = m.senton.$numberLong;
                $tmpl.find('.js-moment-from-time').data('time', time);
            } else {

                $tmpl.find('.js-moment-from-time').data('time', new Date().getTime() / 1000);
            }
            $tmpl.addClass(cls);
            if (m.senton) {
                outer.prepend($tmpl);
            } else {
                outer.append($tmpl);
            }
            scrollDivToBottom(outer);
            parseMomentTime();


            if (!chatLocalStorage.data_is_window_maximised(m.winid) && m.read === false) {
                chatTabUnreadCountPlusPlus(m.winid);
                showChatAlert(m);
            }
        }
        showUnreadCounts();
    }

    function parseChatMessage(msg) {
        return emojione.unicodeToImage(msg);
        // return emojione.shortnameToImage(msg);
    }

    var chat_alert_timeout = null;

    function showChatAlert(m) {
        var $tmpl = $(chat_alert_template);
        var msg = parseChatMessage(m.msg);
        var span = $("<div></div>").addClass('single-message-outer').data('window_id', m.winid);
        span.html(msg);
        if ($("body .chat-alert").length > 0) {
            $("body .chat-alert .message").append(span);
        } else {
            $tmpl.find('.message').html(span);
            $("body").append($tmpl);
        }
        dismissChatAlertTimeout();
    }

    $("body").on('click', '.chat-alert .single-message-outer', function (e) {
        e.preventDefault();
        var pe = $(this).parents('.chat-alert');
        openNewChatWindow($(this).data("window_id"), true);
        dismissChatAlert();
    });

    function dismissChatAlertTimeout() {
        window.clearTimeout(chat_alert_timeout);
        chat_alert_timeout = window.setTimeout(function () {
            dismissChatAlert();
        }, 10000);
    }

    function dismissChatAlert() {
        window.clearTimeout(chat_alert_timeout);
        $('.chat-alert').slideUp(function () {
            $('.chat-alert').remove();
        });
    }

    function chatTabUnreadCountPlusPlus(window_id) {
        var count = chatLocalStorage.getWindowDataValue(window_id, 'unread_count');
        // var count = t.data('count');
        if (!count) {
            count = 0;
        } else {
            count = parseInt(count);
        }
        count++;
        chatLocalStorage.setWindowDataByKey(window_id, 'unread_count', count);
        showUnreadCounts();
        // t.data('count', count);
    }

    function showUnreadCounts() {
        var data = chatLocalStorage.getWindowData();
        var total = 0;
        $.each(data, function (window_id, w_data) {
            var reset = false;
            if (w_data.unread_count) {

                if (chatLocalStorage.data_is_window_maximised(window_id)) {
                    chatLocalStorage.setWindowDataByKey(window_id, 'unread_count', 0);
                    reset = true;
                } else {

                    var t = $("#chat_window_" + window_id);
                    if (t.length) {
                        t.find('.unread_count').html('(' + w_data.unread_count + ')');
                    }
                    var t = $("#link_" + window_id);
                    if (t.length) {
                        t.find('.user_unread_count').html('(' + w_data.unread_count + ')');
                    }
                }

            } else {
                reset = true;
            }
            if (reset) {
                var t = $("#chat_window_" + window_id);
                if (t.length) {
                    t.find('.unread_count').html('');
                }
                var t = $("#link_" + window_id);
                if (t.length) {
                    t.find('.user_unread_count').html('');
                }
            } else {
                total += w_data.unread_count;
            }

        });
        if (total) {
            var t = $(".js-cl-outer .bar-title .chat_unread_count");
            if (t.length) {
                t.html('(' + total + ')');
            }
            console.log('Sending Notification');
            PageTitleNotification.On('(' + total + ') New Message(s)', 'chat');
            BrowserNotification.sendNotification('(' + total + ') New Message(s)');
        } else {
            var t = $(".js-cl-outer .bar-title .chat_unread_count");
            if (t.length) {
                t.html('');
            }
            PageTitleNotification.Off('chat');
        }
    }

    function wsConnect() {
        var heartbeat_message = 'Test 234';
        var heartbeat_missed = 0;
        try {
            chatSocket = new WebSocket(chat_ws_url);

            chatSocket.binaryType = 'arraybuffer';

            message('<p class="event">Socket Status: ' + chatSocket.readyState);

            chatSocket.onopen = function () {
                message('<p class="event">Socket Status: ' + chatSocket.readyState + ' (open)');
                // window.setInterval(function () {
                //     chatSocket.send(JSON.stringify({
                //         ping: heartbeat_message,
                //         time: new Date().getTime()
                //     }));
                //     heartbeat_missed++;
                // }, 60000);
            }

            chatSocket.onmessage = function (msg) {
//                 console.log(msg);
                var res = JSON.parse(msg.data);
                if (res.connection == 'do_register') {
                    chatSocket.send(JSON.stringify({
                        chatid: chat_id,
                        pubkey: chat_public_key,
                        type: 'loaded'
                    }));
                } else if (res.chat_type == 's') { // {"msg":"Hii","chat_type":"s","con":1,"winid":"WI8905161861"}
                    res.read = false;
                    addChatMessage(res);
                }
                message('<p class="message">Received: ' + msg.data);
            }

            chatSocket.onclose = function () {
                message('<p class="event">Socket Status: ' + chatSocket.readyState + ' (Closed)');
                console.log(chatSocket);
                if (chatSocket.readyState == 3) {
                    setTimeout(function () {
                        console.log('run again');
                        wsConnect();
                    }, 5000);
                    // chatSocket = new WebSocket(host);
                }
            }

        } catch (exception) {
            console.log(exception);
        }
    }

    function chatLocalStorageFunctions() {
        this.getWindowData = function () {
            var data = JSON.parse(localStorage.getItem('chat_window_data'));
            if (!data) {
                data = {};
            }
            return data;
        }

        this.getWindowDataByID = function (window_id) {
            var data = this.getWindowData();
            if (typeof data[window_id] == 'undefined') {
                data[window_id] = {};
            }
            return data[window_id];
        }

        this.setWindowData = function (window_id, window_data) {
            var data = this.getWindowData();
            data[window_id] = window_data;
            localStorage.setItem('chat_window_data', JSON.stringify(data));
        }

        this.setWindowDataByKey = function (window_id, key, value) {
            var data = this.getWindowData();
            data[window_id] = this.getWindowDataByID(window_id);
            data[window_id][key] = value;
            localStorage.setItem('chat_window_data', JSON.stringify(data));
        }

        this.getWindowDataValue = function (window_id, key) {
            var window_data = this.getWindowDataByID(window_id);
            if (typeof window_data[key] == 'undefined') {
                return null;
            }
            return window_data[key];
        }

        this.data_is_window_maximised = function (window_id) {
            var t = this.getWindowDataValue(window_id, 'maximised');
            return t ? true : false;
        }


        this.addOpenedChatWindow = function (window_id) {
            var allopenedwindows = this.getOpenedChatWindows();
            if (typeof allopenedwindows[window_id] == 'undefined') {
                allopenedwindows[window_id] = {};
            }
            localStorage.setItem('chat_opened', JSON.stringify(allopenedwindows));
        }

        this.setOpenedChatWindowMeta = function (window_id, key, value) {
            var allopenedwindows = this.getOpenedChatWindows();
            if (typeof allopenedwindows[window_id] == 'undefined') {
                allopenedwindows[window_id] = {};
            }
            allopenedwindows[window_id][key] = value;
            localStorage.setItem('chat_opened', JSON.stringify(allopenedwindows));
        }

        this.removeOpenedChatWindow = function (window_id) {
            var allopenedwindows = this.getOpenedChatWindows();
            allopenedwindows[window_id] = undefined;
            localStorage.setItem('chat_opened', JSON.stringify(allopenedwindows));
        }
        this.getOpenedChatWindows = function () {
            var allopenedwindows = JSON.parse(localStorage.getItem('chat_opened'));
            if (!allopenedwindows) {
                allopenedwindows = {};
            } else {

            }
            return allopenedwindows;
        }

        this.getChatContactByWindowId = function (window_id) {
            var items = this.getChatContactList();
            var item = items[window_id];
            return item;
        }

        this.getChatContactList = function () {
            var items = JSON.parse(localStorage.getItem('chat_contact_list'));
            if (!items) {
                items = {};
            } else {

            }
            return items;
        }

        this.addChatContactToList = function (data) {
            var items = this.getChatContactList();
            items[data.winid] = data;
            localStorage.setItem('chat_contact_list', JSON.stringify(items));
        }
    }


    var chatLocalStorage = new chatLocalStorageFunctions();
    openTabsOnLoad();

    $(".chat-bar").on('click', '.chat_accept_request', function (e) {
        e.preventDefault();
        var window_id = $(this).parents('.chat-tab').data('window-id');
        // console.log(window_id);
        acceptContact(window_id);
    });

    function acceptContact(window_id) {
        var contact_data = chatLocalStorage.getChatContactByWindowId(window_id);

        // console.log(contact_data);
        // return;
        $.ajax(chat_js_url_84 + '/push/chat/accept/request', {
            method: 'post',
            data: {
                chatid: chat_id,
                winid: window_id
            },
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                // contact_data.accepted = true;
                // chatLocalStorage.addChatContactToList(contact_data);
                // $('#chat_window_' + window_id).find('.chat_accept_request').slideUp();


            }
        });
        contact_data.accepted = true;
        chatLocalStorage.addChatContactToList(contact_data);
        $('#chat_window_' + window_id).find('.chat_accept_request').slideUp();
    }

    function scrollDivToBottom(div) {
        var d = div;
        d.scrollTop(d.prop("scrollHeight"));
    }

    setInterval(function () {
        parseMomentTime();
    }, 5000);

    // parseMomentTime();


    function parseMomentTime() {
        $(".js-moment-from-time").each(function (index, element) {
            var t = $(element).data('time');
            if (t) {
                var mt = moment(t, 'X');
                $(element).html(mt.fromNow() + ' - ' + mt.format('LTS'));
            }
        })
    }

    ACFn.on_chat_group_create = function (F, R) {
        getContacts();
        $("#modal-popup").modal('hide');
    }

    ACFn.on_request_sent = function (F, R) {
        getContacts();
        $("#modal-popup").modal('hide');
    }

    ACFn.on_remove_group_user = function (F, R) {
        F.parents('.remove_participant').slideUp(function () {
            F.parents('.remove_participant').remove();
        });
    }

    ACFn.on_chat_group_update = function (F, R) {
        $("#modal-popup").modal('hide');
    }


    function getFileData($input) {
        var input = $input[0];
        var response = {};
        if (input.files && input.files[0]) {
            file = input.files[0]; // The file
            if (file) {
                $.each(file, function (index, value) {
                    response[index] = value;
                });
            }
            // response['data'] = file_data;
        }
        return response;
    }

    $(".chat-tab").on('change', '.chat-file-outer input', function () {
        var window_id = $(this).parents('.chat-tab').data('window-id');
        // alert('changed!');
        if (!window.FileReader) {
            return alert('FileReader API is not supported by your browser.');
        }
        var $i = $(this), // Put file input ID here
            input = $i[0]; // Getting the element from jQuery
        if (input.files && input.files[0]) {
            file = input.files[0]; // The file
            fr = new FileReader(); // FileReader instance
            fr.onload = function () {
                sendChatFile(window_id, fr.result);
            };
            fr.readAsArrayBuffer(file);
        }
    });

    function alignTabs() {
        $(".chat-tabs-outer").css('border-right-width', $(".chat-list-outer").width());

        var right = 0;
        $(".chat-tab").each(function (index, element) {
            right += 5;
            $(element).css('right', right);
            right += $(element).width();
        });
    }

    ACFn.open_project_chat_window = function (F, R) {
        if (R.window_id) {
            var data = chatLocalStorage.getChatContactByWindowId(R.window_id);
            if (!data) {
                getContacts(function () {
                    openNewChatWindowGroup(R.window_id);
                });
            } else {
                openNewChatWindowGroup(R.window_id);
                maximiseChatWindow(R.window_id);
            }
        } else {
            ACFn.display_message('Project Chat could not be Opened');
        }
    }

    ACFn.openProjectChatWindow = function (window_id) {
        openNewChatWindowGroup(window_id);
        maximiseChatWindow(window_id);
    };

    wsConnect();


});

(function ($) {

    var pe = null;
    var options = {};
    var is_open_chat_socket = false;
    var chatSocket = null;

    var methods = {
        init: function () {
            var _methods = this;
            console.log('init chat');
            console.log(pe);
            _methods.wsConnect();
        },
        show: function () {
        },// IS
        hide: function () {
        },// GOOD
        wsConnect: function () {
            var _methods = this;
            try {
                chatSocket = new WebSocket(options.ws_url);

                chatSocket.binaryType = 'arraybuffer';

                _methods.message('<p class="event">Socket Status: ' + chatSocket.readyState);

                chatSocket.onopen = function () {
                    _methods.message('<p class="event">Socket Status: ' + chatSocket.readyState + ' (open)');
                    is_open_chat_socket = true;
                }

                chatSocket.onmessage = function (msg) {
//                 console.log(msg);
                    var res = JSON.parse(msg.data);
                    if (res.connection == 'do_register') {
                        chatSocket.send(JSON.stringify({
                            chatid: options.id,
                            pubkey: options.public_key,
                            type: 'loaded'
                        }));
                    } else if (res.chat_type == 's') { // {"msg":"Hii","chat_type":"s","con":1,"winid":"WI8905161861"}
                        res.read = false;
                        _methods.addChatMessage(res);
                    }
                    _methods.message('<p class="message">Received: ' + msg.data);
                }

                chatSocket.onclose = function () {
                    is_open_chat_socket = false;
                    _methods.message('<p class="event">Socket Status: ' + chatSocket.readyState + ' (Closed)');
                    console.log(chatSocket);
                    if (chatSocket.readyState == 3) {
                        setTimeout(function () {
                            console.log('run again');
                            _methods.wsConnect();
                        }, 5000);
                        // chatSocket = new WebSocket(host);
                    }
                }

            } catch (exception) {
                console.log(exception);
            }
        },
        message: function (m) {
            console.log(m);
        },
        alignTabs: function alignTabs() {
            pe.find(".chat-tabs-outer").css('border-right-width', pe.find(".chat-list-outer").width());
            var right = 0;
            pe.find(".chat-tab").each(function (index, element) {
                right += 5;
                $(element).css('right', right);
                right += $(element).width();
            });
        }
    };

    $.fn.chatPlugin = function (poptions) {
        pe = this;
        var defaults = {
            ws_url: '',
            id: '',
            public_key: '',

        };

        // This is the easiest way to have default options.
        options = $.extend(true, {}, defaults, poptions);

        methods.init();
        return this;

    };


}(jQuery));

