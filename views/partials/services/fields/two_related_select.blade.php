@foreach($fields as $field)
    {!! $field !!}
@endforeach

<script>
    let relations = {!! json_encode($relations) !!};
    let data = {!! json_encode($data) !!};
    let first;
    let second;
    let firstSelect;
    let secondSelect;
    let firstSelectPlaceholder;
    let secondSelectPlaceholder;

    document.addEventListener('DOMContentLoaded', function(){
        first = data.fields[0].name;
        second = data.fields[1].name;
        firstSelectPlaceholder = data.fields
        firstSelect = document.querySelector('[name="'+first+'"]');
        secondSelect = document.querySelector('[name="'+second+'"]');
        firstSelectPlaceholder = data.fields[0].placeholder;
        secondSelectPlaceholder = data.fields[1].placeholder;
        firstSelect.addEventListener('change', checkFirstSelected);

        setTimeout(function (){
            checkFirstSelected();
        },2000);
    });

    function checkFirstSelected(){

        if(firstSelect.value){
            updateOptions(firstSelect.value);
        }else{
            secondSelect.innerHTML = getOptionView('',secondSelectPlaceholder);
        }
    }

    function updateOptions(selectedId){
        secondSelect.value = '';
        if(relations[selectedId].districts){
            let options = getOptionView('',secondSelectPlaceholder);
            for(let districtId in relations[selectedId].districts){
                options += getOptionView(
                    relations[selectedId].districts[districtId].id,relations[selectedId].districts[districtId].title);
            }
            secondSelect.innerHTML = options;
        }
    }

    function getOptionView(id,caption){
        return '<option value="'+id+'">'+caption+'</option>'
    }
</script>