function showNotif(Ids, counter, chatIds, counterMessage) {
    const userId = $("#userId").val();

    let urlNotif = "/booking/notif";
    let urlBooker = "/booking/notifBooker";
    let urlAuthor = "/booking/notifAuthor";
    let urlConfirm = "/booking/notifConfirm";
    let urlChat = "/chat/notif";

    const requestOne = axios.post(urlNotif, {
        userId: userId
    });
    const requestTwo = axios.post(urlBooker, {
        userId: userId
    });
    const requestThree = axios.post(urlAuthor, {
        userId: userId
    });
    const requestFour = axios.post(urlConfirm, {
        userId: userId
    });
    const requestFive = axios.post(urlChat, {
        userId: userId
    });

    axios
        .all([requestOne, requestTwo, requestThree, requestFour, requestFive])
        .then(
            axios.spread((...responses) => {
                const responseOne = responses[0];
                const responseTwo = responses[1];
                const responseThree = responses[2];
                const responseFour = responses[3];
                const responseFive = responses[4];

                $.each(responseOne.data, function (key, value) {
                    if ($.inArray(value.id, Ids) === -1) {
                        $("#notifPrepend").prepend(
                            "<li style='display:flex'><img src='" +
                            value.picture +
                            "' class='avatar avatar-mini' alt='Avatar de " +
                            value.booker +
                            "' >" +
                            "<a class='dropdown-item' href='/demande/" +
                            value.id +
                            "'><h6 style='font-size:0.75em'> Nouvelle demande de reservation de la part de " +
                            value.booker +
                            "</h6></a></li>"
                        );
                        counter += 1;
                        Ids.push(value.id);
                        $("#counter").html(counter);
                    }
                });
                $.each(responseTwo.data, function (key, value) {
                    if ($.inArray(value.id, Ids) === -1) {
                        $("#notifPrepend").prepend(
                            "<li style='display:flex'><img src='" +
                            value.picture +
                            "' class='avatar avatar-mini'>" +
                            "<a class='dropdown-item' href='/booking/" +
                            value.id +
                            "#comment'><h6 style='font-size:0.75em'>Votre reservation pour l'annonce <strong>" +
                            value.title +
                            "</strong> est terminée</h6></a></li>"
                        );
                        counter += 1;
                        Ids.push(value.id);
                        $("#counter").html(counter);
                    }
                });
                $.each(responseThree.data, function (key, value) {
                    if ($.inArray(value.id, Ids) === -1) {
                        $("#notifPrepend").prepend(
                            "<li style='display:flex'> <img src='" +
                            value.picture +
                            "' class='avatar avatar-mini'>" +
                            "<a class='dropdown-item' href='/demande/" +
                            value.id +
                            "#comment'><h6 style='font-size:0.75em'>La reservation de <strong>" +
                            value.booker +
                            "</strong> pour votre article est terminée</h6></a></li>"
                        );
                        counter += 1;
                        Ids.push(value.id);
                        $("#counter").html(counter);
                    }
                });
                $.each(responseFour.data, function (key, value) {
                    if ($.inArray(value.id, Ids) === -1) {
                        $("#notifPrepend").prepend(
                            "<li><a class='dropdown-item' href='/booking/confirm/" +
                            value.id +
                            "'><h6 style='font-size:0.75em'><i h6 style='font-size:2em; color:green' class='fas fa-clipboard-check'></i> Votre reservation pour l'article <strong style='color:green'>" +
                            value.title +
                            "</strong> a été confirmée</h6></a></li>"
                        );
                        counter += 1;
                        Ids.push(value.id);
                        $("#counter").html(counter);
                    }
                });
                $.each(responseFive.data, function (key, value) {
                    if ($.inArray(value.id, chatIds) === -1) {
                        $("#messagePrepend").prepend(
                            "<li><a class='dropdown-item' href='/chat/seen/" +
                            value.id +
                            "'><h6 style='font-size:0.75em'><i h6 style='font-size:2em; color:green' class='fas fa-clipboard-check'></i> Vous avez des messages concernant la reservation n°<strong style='color:green'>" +
                            value.id +
                            "</strong></h6></a></li>"
                        );
                        counterMessage += 1;
                        chatIds.push(value.id);
                        $("#counterMessage").html(counterMessage);
                    }
                });
            })
        )
        .catch((errors) => {
            console.error(errors);
        });
}

$(document).ready(function () {
    var counter = 0;
    var counterMessage = 0;
    var Ids = [];
    var chatIds = [];
    showNotif(Ids, counter, chatIds, counterMessage);
    setInterval(function () {
        showNotif(Ids, counter, chatIds, counterMessage);
    }, 7000);
});