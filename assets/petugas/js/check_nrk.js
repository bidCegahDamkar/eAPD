$( document ).ready(function() {

    //var nrk_val = document.getElementById('NRK').value;
    var nrk_val = $("#NRK").val();
    

    function delay(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
            callback.apply(context, args);
            }, ms || 0);
        };
    }
      
      
      // Example usage:
      
    $('#NRK').keyup(delay(function (e) {
        //console.log('Time elapsed!', this.value);
        check_duplicate(this.value);
        //console.log('nrk = ' + this.value);
    }, 500));

    function check_duplicate(nrk){
        var hasSpace = /\s/g.test(nrk);
        if (hasSpace || nrk.length === 0) {
            add_attribute('duplicate');
        } else {
            var dataString = 'loadId='+ nrk;
            //console.log('nrk = ' + nrk);
            $.ajax({
                type: "GET",
                url: base_url + controller+"/check_duplicate_nrk",
                data: dataString,
                cache: false,
                success: function(result){
                    //console.log(result);
                    add_attribute(result);
                }
            });
        }
    }

    function add_attribute(result_val){
        if($( '#NRK' ).hasClass( 'is-valid' )){
            $('#NRK').removeClass( 'is-valid' )
        }
        if($( '#NRK' ).hasClass( 'is-invalid' )){
            $('#NRK').removeClass( 'is-invalid' )
        }

        if (result_val == 'duplicate') {
            $('#NRK').addClass( 'is-invalid' )
            if($('#submit_button').prop('disabled',false)){
                $('#submit_button').prop('disabled', true)
            }
        } else {
            $('#NRK').addClass( 'is-valid' )
            if($('#submit_button').prop('disabled')){
                $('#submit_button').prop('disabled', false)
            }
        }
    }
});