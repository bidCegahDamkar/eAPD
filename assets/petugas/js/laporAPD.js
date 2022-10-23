$( document ).ready(function() {
    //$('.needs-validation').attr('novalidate', true);
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    /*(function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();*/

    
    $("#mkp_id").on('change', function() {
        if (this.value == '1') {
            //show elemen
            if($( '.refi' ).hasClass( 'hide-kus' )){
                $('.refi').removeClass( 'hide-kus' )
            }
            if($( '.refi-kus' ).hasClass( 'hide-kus' )){
                $('.refi-kus').removeClass( 'hide-kus' )
            }
            // add required prop
            if(!$('.refi-select').prop('required')){
                $('.refi-select').prop('required', true)
            }
            if(!$('#fileuploadInput').prop('required') && req){
                $('#fileuploadInput').prop('required', true)
            }
            // remove disabled
            if($('.refi-select').prop('disabled')){
                $('.refi-select').prop('disabled', false)
            }
            if($('#fileuploadInput').prop('disabled')){
                $('#fileuploadInput').prop('disabled', false)
            }
        } else if (this.value == '2') {
            if($( '.refi' ).hasClass( 'hide-kus' )){
                $('.refi').removeClass( 'hide-kus' )
            }
            if(! $( '.refi-kus' ).hasClass( 'hide-kus' )){
                $('.refi-kus').addClass( 'hide-kus' )
            }
            // add required prop
            if(!$('.refi-select').prop('required')){
                $('.refi-select').prop('required', true)
            }
            //remove required prop
            if($('#fileuploadInput').prop('required')){
                $('#fileuploadInput').prop('required', false)
            }
            // remove disabled
            if($('.refi-select').prop('disabled')){
                $('.refi-select').prop('disabled', false)
            }
            // add disabled
            if(! $('#fileuploadInput').prop('disabled')){
                $('#fileuploadInput').prop('disabled', true)
            }
        } else {
            if(! $( '.refi' ).hasClass( 'hide-kus' )){
                $('.refi').addClass( 'hide-kus' )
            }
            if(! $( '.refi-kus' ).hasClass( 'hide-kus' )){
                $('.refi-kus').addClass( 'hide-kus' )
            }
            // remove required prop
            if($('.refi-select').prop('required')){
                $('.refi-select').prop('required', false)
            }
            if($('#fileuploadInput').prop('required')){
                $('#fileuploadInput').prop('required', false)
            }
            // add disabled
            if(! $('.refi-select').prop('disabled')){
                $('.refi-select').prop('disabled', true)
            }
            if(! $('#fileuploadInput').prop('disabled')){
                $('#fileuploadInput').prop('disabled', true)
            }
        }
      });
        
        $("#mkp_id").trigger("change");

        console.log( "ready!" );
});