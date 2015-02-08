
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
