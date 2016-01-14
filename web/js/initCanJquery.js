$(document).ready(function() {
    if( ! $('#myCanvas').tagcanvas({
            textColour : '#ff1f00',
            outlineThickness : 1,
            maxSpeed : 0.03,
            depth : 0.95
        })) {
        // TagCanvas failed to load
        $('#myCanvasContainer').hide();
    }
    // your other jQuery stuff here...
});