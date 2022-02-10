/*function reloadIframe(moduleId){
    document.getElementById('modeuleId_' + moduleId).contentWindow.location.reload();
}
reloadIframe([+moduleId+]);
document.querySelectorAll('#DocumentParamsTab').forEach(function(item, i, arr) {
    addEventListener('click', function(){

        item.getElementById('modeuleId_' + moduleId).contentWindow.location.reload();
        console.log(123);
        reloadIframe([+moduleId+]);
    }, false)
}
*/
document.querySelectorAll('#DocumentParams iframe').forEach( function(tab, index, arr){
    console.log(1234);
    tab[index].contentWindow.location.reload();
})
