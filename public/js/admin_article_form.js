$(document).ready(() => {



    var $locationSelect = $('.js-article-form-location');
    var $specificLocationTarget = $('.js-specific-location-target');

   $locationSelect.on('change',function(){
       $.ajax({
           url: $locationSelect.data('specific-location'),
           data: {
               location: $locationSelect.val()
           },
           success:function(data){
               if (!data) {
                   $specificLocationTarget.find('select').remove();
                   $specificLocationTarget.addClass('d-none');
                   return;
               }

               $specificLocationTarget
                   .html(data)
                   .removeClass('d-none')
           },
           error: function (err){
               console.log(err);
           }
       })
   });

});
