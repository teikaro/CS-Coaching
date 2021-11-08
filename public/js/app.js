$(function(){
    // Rend temporaire les div alerts (messages)
    if ($('div.alert')) {
        $('div.alert').delay(3500).fadeOut(300, function() {
            $('div.alert').remove();
        });
    }
});