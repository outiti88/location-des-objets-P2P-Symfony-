$('#add_image').click(function () {

    //je récupére le numéros des futurs champs que je vais créer
    const index = +$('#widgets-counter').val();

    const tmpl0 = $('#ad_images').data('prototype').replace(/__name__label__/g, "");

    //je récupére le prototype des entrées
    const tmpl = tmpl0.replace(/__name__/g, index);

    //j'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    //je gére le bouton supprimer
    handleDeleteButton();

});

function handleDeleteButton() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).closest('fieldset[class="form-group"]').remove();
    });
}

function updateCounter() {
    const counter = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(counter);
}

updateCounter();

handleDeleteButton();

$(document).ready(function () {
    var cat = $("#ad_category option:selected").text();
    const url = "/ads/subCategory";
    axios.post(url, {
            category: cat
        })
        .then(function (response) {
            $("#ad_subCategory option").remove();
            $.each(response.data, function (key, value) {
                $('#ad_subCategory').append($("<option></option>").attr("value", value.id).text(value.title));
            });
        }).catch(function (error) {
            console.log(error);
        });
    $("#ad_category").on('change', function () {
        var cat = $("#ad_category option:selected").text();
        const url = "/ads/subCategory";
        axios.post(url, {
                category: cat
            })
            .then(function (response) {
                $("#ad_subCategory option").remove();
                $.each(response.data, function (key, value) {
                    $('#ad_subCategory').append($("<option></option>").attr("value", value.id).text(value.title));
                });
            }).catch(function (error) {
                console.log(error);
            });
    });
}); //