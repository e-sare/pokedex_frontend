/*
pokedex.js - by Eric Sare
*/

/*
$(document).ready(function(){
    $('#moveTab a').click(function(e){
        e.preventDefault();
        $(this).tab('show');
    });

    $('#myTab a[href="#level-moves"]').tab('show');
    $('#myTab a[href="#hm-tm-moves"]').tab('show');
    $('#myTab a[href="#move-tutor-moves"]').tab('show');
})

*/
$(document).ready(function(){
    $('#moveTab a:eq(0)').click();
})