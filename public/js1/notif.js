function showNotif(Ids, counter) {
    const url = "/booking/notif";
    const userId = $('#userId').val();
    axios.post(url, {
            userId: userId
        })
        .then(function (response) {
            $.each(response.data, function (key, value) {
                if ($.inArray(value.id, Ids) === -1) {
                    $('#notifPrepend').prepend("<li><a class='dropdown-item' href='http://127.0.0.1:8000/demande/" + value.id + "'><h6>Vous avez reçu une nouvelle demande de reservation de la part de " + value.booker + "</h6></a></li>");
                    counter += 1;
                    Ids.push(value.id);
                    $('#counter').html(counter);
                }

            });
        }).catch(function (error) {
            console.log(error);
        });
}
$(document).ready(function () {
    var counter = 0;
    var Ids = [];
    showNotif(Ids, counter);
    setInterval(function () {
        showNotif(Ids, counter);
    }, 5000);
});