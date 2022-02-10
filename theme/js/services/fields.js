var $regionalServiceCenter = $('#regional_service_center');
var $territorialServiceCenter = $('#territorial_service_center');

function searchTerritorialServiceCenter () {
    var value = $regionalServiceCenter.val();

    $.post  (
        '/service-field-territorial-service-center-field',
        {
            region:value,
        },
        function (options) {
            $territorialServiceCenter.html('');
            $territorialServiceCenter.append($('<option></option>').val('').text('Оберіть..'));
            for(var i=0;i<options.length;i++){
                var option = options[i];

                $territorialServiceCenter.append($('<option></option>').val(option.id).text(option.name));
            }
        }
    )
}
if($regionalServiceCenter.length && $territorialServiceCenter.length){
    $regionalServiceCenter.on('change',searchTerritorialServiceCenter);

    console.log($regionalServiceCenter.val());

    if($regionalServiceCenter.val()){
        searchTerritorialServiceCenter();
    }
}


 $('#contract_date_and_number_field').mask("99.99.9999 № 999999");