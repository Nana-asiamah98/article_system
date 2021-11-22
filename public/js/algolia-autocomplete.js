$(document).ready(() => {

    $('.js-user-complete').each(function () {
        var autoCompleteUrl = $(this).data('complete-url');
        $(this).autocomplete({hint: false}, [
            {

                source: function (query, cb) {
                    $.ajax({
                        url: autoCompleteUrl+'?query='+query
                    }).then(function (data){
                        cb(data.users);
                    })
                },
                displayKey: 'email',
                debounce: 500,
            }
        ])

    });
});
