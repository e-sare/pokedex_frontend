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

    // click even to open up move tabs
    $('#moveTab a:eq(0)').click();

    $('#nav-search button').hide();

    console.log(window.location.pathname);

    var hideNavSearch = function(){
        $('#nav-search').hide();
    }


    if(window.location.pathname == '/pokedex' || window.location.pathname == '/pokedex/index'){
        hideNavSearch();
    }else{
        $('#nav-search').show();
    }
})