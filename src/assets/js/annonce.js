// assets/js/annonce.js

$(document).ready(function() {
    $('#annonce_marque').change(function() {
        var selectedMarque = $(this).val();
        if (selectedMarque) {
            $.ajax({
                url: '/get_models/' + selectedMarque,
                type: 'GET',
                success: function(data) {
                    // Mettez à jour la liste des modèles
                    var modelSelect = $('#annonce_model');
                    modelSelect.empty();
                    $.each(data, function(key, value) {
                        modelSelect.append('<option value="' + value.id + '">' + value.modelNom + '</option>');
                    });
                }
            });
        }
    });
});
