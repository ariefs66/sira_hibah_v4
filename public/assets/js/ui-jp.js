+function ($) {

  $(function(){

      $("[ui-jq]").each(function(){
        var self = $(this);

       
         

         
              var options = eval('[' + self.attr('ui-options') + ']');
              if ($.isPlainObject(options[0])) {
                options[0] = $.extend({}, options[0]);
              }

               console.log(self.attr('ui-jq'));

               if(self.attr('ui-jq') == 'fullcalendar'){
                uiLoad.load(jp_config[self.attr('ui-jq')]);
               }else{
                uiLoad.load(jp_config[self.attr('ui-jq')]).then( function(){          
                  self[self.attr('ui-jq')].apply(self, options);
               
                });  
               }
              
          

          
            
       
        

        
      });

  });
}(jQuery);