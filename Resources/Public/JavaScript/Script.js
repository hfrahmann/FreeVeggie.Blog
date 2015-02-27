
function fvToggleNavigation(selector) {
    $(selector).slideToggle('fast');
}


function fvCalculateIngredients(ingredientsElement, originalPortion) {

    var portionString = ingredientsElement.find('input.fv-ingredient-portion').val().replace(',', '.');
    var portion = parseFloat(portionString).toFixed(2);
    if(portionString.length == 0 || isNaN(portion) || portion <= 0) {
        portion = originalPortion;
    }

    ingredientsElement.find('td.fv-ingredient-amount').each(function(){

        originalAmount = $(this).data('originalamount');
        newAmount = originalAmount * (portion / originalPortion);

        fractionPart = newAmount % 1;
        if((""+fractionPart).length > 4) {
            newAmount = parseFloat(newAmount).toFixed(2);
            newAmount = parseFloat(newAmount);
        }

        $(this).html(newAmount);

    });

}


function fvCalculateBlur(portionInput, originalPortion) {

    var portionString = portionInput.val().replace(',', '.');
    var portion = parseFloat(portionString);
    if(isNaN(portion) || portion <= 0) {
        portionInput.val(originalPortion);
    }

}



function fvGoogleMapImage(imageObject, address, zoom) {
    encodedAddress = encodeURIComponent(address);
    $.get("http://maps.googleapis.com/maps/api/geocode/json?address=" + encodedAddress + "&sensor=false", function(data, status) {
        var lat = data.results[0].geometry.location.lat;
        var lng = data.results[0].geometry.location.lng;

        var mapUrl = "http://maps.googleapis.com/maps/api/staticmap?";
        mapUrl += "center=" + encodedAddress + "&";
        mapUrl += "zoom=" + parseInt(zoom) + "&";
        mapUrl += "size=600x300&";
        mapUrl += "maptype=roadmap&";
        mapUrl += "markers=color:red%7Clabel:A%7C"+lat+","+lng+"&";

        imageObject.attr('src', mapUrl);
    });
}


function fvSharePopup(shareLinkObject) {
    var href = shareLinkObject.attr('href');
    window.open(href, "Sharepopup"+Math.random(), "width=500,height=500,resizable=no");
}
