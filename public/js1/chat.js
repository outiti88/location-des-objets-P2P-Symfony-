function insertMessage(MessageIds) {
    const url = "/chat/insert";
    const userId = $('#userId').val();
    const bookingId = $('#bookingId').val();
    const userPicture = $('#userPicture').val();
    const message = $('#message').val();
    $('.messages ul').append("<li class='sent'><img src='" + userPicture + "' alt='' /><p>" + message + "</p></li>");
    var mydiv = $('.messages');
    mydiv.scrollTop(mydiv.prop("scrollHeight"));
    axios.post(url, {
            userId: userId,
            bookingId: bookingId,
            message: message
        })
        .then(function (response) {
            $.each(response.data, function (key, value) {
                MessageIds.push(value.id);
            });
        })
        .catch(function (error) {
            console.log(error);
        });
};

function selectMessages(MessageIds) {
    const url = "/chat/selectMessages";
    const bookingId = $('#bookingId').val();
    const userId = $('#userId').val();
    axios.post(url, {
            bookingId: bookingId,
            userId: userId
        })
        .then(function (response) {
            $.each(response.data, function (key, value) {
                if ($.inArray(value.id, MessageIds) === -1) {
                    if (value.authorId == userId) {
                        $('.messages ul').append("<li class='sent'><img src='" + value.authorPicture + "' alt='' /><p>" + value.message + "</p></li>");
                    } else {
                        $('.messages ul').append("<li class='replies'><img src='" + value.authorPicture + "' alt='' /><p>" + value.message + "</p></li>");
                    }
                    MessageIds.push(value.id);
                }
            });
        }).catch(function (error) {
            console.log(error);
        });
}

$(document).ready(function () {
    var MessageIds = [];
    $('#message').click(function () {
        const url = "/chat/setSeen";
        const bookingId = $('#bookingId').val();
        axios.post(url, {
                bookingId: bookingId,
            })
            .catch(function (error) {
                console.log(error);
            });
    });
    $('#Envoyer').click(function () {
        insertMessage(MessageIds);
        $('#message').val("");
    });
    $('#message').on('keypress', function (e) {
        if (e.which === 13) {
            insertMessage(MessageIds);
            $(this).val("");
        }
    });
    selectMessages(MessageIds);
    var mydiv = $('.messages');
    mydiv.scrollTop(mydiv.prop("scrollHeight"));
    setInterval(function () {
        selectMessages(MessageIds);
        var mydiv = $('.messages');
        mydiv.scrollTop(mydiv.prop("scrollHeight"));
    }, 1000);
});