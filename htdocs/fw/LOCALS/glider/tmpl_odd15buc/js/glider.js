head.js( "foundation/foundation.js",
   function(){
       //alert('dada');
       $(document)
        .foundation()
        .foundation('abide', {
              patterns: {
                min200: /^.{5,}$/
              }
            }
        );
   });
