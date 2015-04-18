onVisible Documentation
=========
onVisible is a small and easy to use JS library to add events listeners when dom elements become hidden or visible.

The demo is available here: http://onvisible.deflotte.fr/

Requirement
------------
onVisible require jQuery 1.8.2

Get Started
------------

Here is the minimal html code to use ZoomGallery

    <html>
        <head>
            <title>Demo of onVisible</title>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="demo.css" type="text/css" />
            <script type="text/javascript" src="jquery.js"></script>
            <script type="text/javascript" src="onvisible.js"></script>
        </head>
        <body>
            <h1>onVisible</h1>
            <p>Trigger an event when elements become visibles.</p>
            <div id="log"></div>
            <div class="event">#1</div>
            <div class="event">#2</div>
            <div class="event">#3</div>
            <div class="event">#4</div>
            <div class="event">#5</div>
            <div class="event">#6</div>
            
            <script>
                // Enable events to the elements
                $(document).ready(function() {
                    $('.event').visibilityListener();
                });
            
                // Add event listener
                $('.event').on('visible', function() {
                    $(this).addClass('on');
                    $('#log').prepend(
                        $('<div></div>')
                            .html($(this).html() + ' is now visible')
                    );
                });
                $('.event').on('partiallyvisible', function() {
                    $(this).removeClass('fullyvisible');
                });
                $('.event').on('fullyvisible', function() {
                    $(this).addClass('fullyvisible');
                });
                $('.event').on('hidden', function() {
                    $(this).removeClass('on');
                    $(this).removeClass('fullyvisible');
                    $('#log').prepend(
                        $('<div></div>')
                            .html($(this).html() + ' is now hidden')
                    );
                });
            </script>
        </body>
    </html>


Options
------------
 - checkOnLoad: If true, envets will be trigger on load
 - frequency: Frequency (in ms) to check visibility
 
Events
------------
Four events are availables:
 - visible: When an element become visible (partially or fully)
 - partiallyvisible: When an element become partially visible
 - fullyvisible: When an element become fully visible
 - hidden: When an element become hidden


Version
-

0.1


License
-

GPL

  [Maxence de Flotte]: http://tech.deflotte.fr/
  [@madef_]: http://twitter.com/madef_
  [demo]: http://zoomgallery.deflotte.fr
