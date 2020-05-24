function showNotif(Ids, counter) {
    const url = "/booking/notif";
    const userId = $('#userId').val();
    axios.post(url, {
            userId: userId
        })
        .then(function(response) {
            $.each(response.data, function(key, value) {
                if ($.inArray(value.id, Ids) === -1) {

                    $('#notifPrepend').prepend(
                        "<li style='display:flex'><img src='" + value.picture + "' class='avatar avatar-mini' alt='Avatar de " + value.booker + "' >" +
                        "<a class='dropdown-item' href='/demande/" + value.id + "'><h6 style='font-size:0.75em'> Nouvelle demande de reservation de la part de " + value.booker + "</h6></a></li>"
                    );
                    counter += 1;
                    Ids.push(value.id);
                    $('#counter').html(counter);
                }

            });
        }).catch(function(error) {
            console.log(error);
        });
    const urlBooker = "/booking/notifBooker";
    axios.post(urlBooker, {
            userId: userId
        })
        .then(function(response) {
            $.each(response.data, function(key, value) {
                if ($.inArray(value.id, Ids) === -1) {
                    $('#notifPrepend').prepend(
                        "<li style='display:flex'><img src='" + value.picture + "' class='avatar avatar-mini'>" +
                        "<a class='dropdown-item' href='/booking/" + value.id + "#comment'><h6 style='font-size:0.75em'>Votre reservation pour l'annonce <strong>" + value.title + "</strong> est terminée</h6></a></li>"
                    );
                    counter += 1;
                    Ids.push(value.id);
                    $('#counter').html(counter);
                }

            });
        }).catch(function(error) {
            console.log(error);
        });
    const urlAuthor = "/booking/notifAuthor";
    axios.post(urlAuthor, {
            userId: userId
        })
        .then(function(response) {
            $.each(response.data, function(key, value) {
                if ($.inArray(value.id, Ids) === -1) {
                    $('#notifPrepend').prepend(
                        "<li style='display:flex'> <img src='" + value.picture + "' class='avatar avatar-mini'>" +
                        "<a class='dropdown-item' href='/demande/" + value.id + "#comment'><h6 style='font-size:0.75em'>La reservation de <strong>" + value.booker + "</strong> pour votre article est terminée</h6></a></li>"
                    );
                    counter += 1;
                    Ids.push(value.id);
                    $('#counter').html(counter);
                }

            });
        }).catch(function(error) {
            console.log(error);
        });

    const urlConfirm = "/booking/notifConfirm";
    axios.post(urlConfirm, {
            userId: userId
        })
        .then(function(response) {
            $.each(response.data, function(key, value) {
                if ($.inArray(value.id, Ids) === -1) {
                    $('#notifPrepend').prepend("<li><a class='dropdown-item' href='/booking/confirm/" + value.id + "'><h6 style='font-size:0.75em'><i h6 style='font-size:2em; color:green' class='fas fa-clipboard-check'></i> Votre reservation pour l'article <strong style='color:green'>" + value.title + "</strong> a été confirmée</h6></a></li>");
                    counter += 1;
                    Ids.push(value.id);
                    $('#counter').html(counter);
                }

            });
        }).catch(function(error) {
            console.log(error);
        });
}

$(document).ready(function() {
    var counter = 0;
    var Ids = [];
    showNotif(Ids, counter);
    setInterval(function() {
        showNotif(Ids, counter);
    }, 5000);
});