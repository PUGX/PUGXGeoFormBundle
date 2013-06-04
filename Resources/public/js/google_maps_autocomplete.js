$.each($("form"), function(formindex, form) {
    $.each($(form).find("input.pugx-geocode"), function(index, widget) {

        var options = {
            types: ['geocode']
        };

        var autocomplete = new google.maps.places.Autocomplete(widget, options);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            $(widget).siblings('input.pugx-geocode-latitude').val(place.geometry.location.lat());
            $(widget).siblings('input.pugx-geocode-longitude').val(place.geometry.location.lng());
        });
    });
});
