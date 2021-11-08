$(document).ready(function(){

    // Animation "Fluide" de retour en haut de page
    $("a[href='#backTop']").click(function() {
        $("html, body").animate({ scrollTop: 0 }, 500);
    });

});