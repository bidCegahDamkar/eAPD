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
            $("#mapd_id").trigger("change");
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
            $("#mapd_id").trigger("change");
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

            if(! $( '.refisa' ).hasClass( 'hide-no-urut' )){
                $('.refisa').addClass( 'hide-no-urut' )
            }
    
            if($('#no_urut').prop('required')){
                $('#no_urut').prop('required', false)
            }
        }

        
    });

    $("#fileuploadInput").on('change', function() {
        const [file] = this.files
        if (file) {
            let imgSrc = URL.createObjectURL(file)
            $("#preview_foto_apd").attr("src",imgSrc);
        }
    });
        
    $("#mkp_id").trigger("change");

    $('#mapd_id').on('change', function() {
        //alert( $('select#mapd_id option:selected').attr('data-img') );
        var imgSrc;
        var defaultPath = base_url + 'assets/petugas/APD/';
        //var fotoAPD = $('select#mapd_id option:selected').attr('val');
        var ma_id =$('#mapd_id').find(":selected").val();
        var mkp_id = $('#mkp_id').find(":selected").val();
        //console.log(fotoAPD);
        $( ".carousel" ).remove();

        var dataString = 'loadId='+ ma_id;
        $.ajax({
            url : base_url+controller+'/get_img_apd_ajax',
            type: "GET",
            data: dataString,
            dataType: "JSON",
            success: function(result)
            {
                // gambar apd
                if (result) {
                    const obj = JSON.parse(result.foto_mapd);
                    /*let html = '<div class="carousel-full owl-carousel owl-theme" >';
                    for (let i = 0; i < obj.length; i++) {
                        //console.log('result delete: ' + obj[i]);
                        html += '<div class="item" style="width:350px"><img src="'+base_url+'assets/petugas/APD/'+obj[i]+'" alt="alt" class="imaged img-fluid"></div>';
                    }
                    html += '</div>';*/
                    let html = `
                    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">`;
                    for (let i = 0; i < obj.length; i++) {
                        //console.log('result delete: ' + obj[i]);
                        //html += '<div class="item" style="width:350px"><img src="'+base_url+'assets/petugas/APD/'+obj[i]+'" alt="alt" class="imaged img-fluid"></div>';
                        let k = '';
                        if (i==0) {
                            k = 'active';
                        }
                        html += `
                            <div class="carousel-item `+k+` text-center" >
                                <img src="`+base_url+'assets/petugas/APD/'+obj[i]+`" class="img-fluid" alt="..." style="height:250px">
                            </div>
                        `;
                    }
                    html += `
                        </div>
                    </div>
                    `;
                    $( "#img-apd" ).append( html );
                    //$('#img-apd').owlCarousel();
                    /*$('.owl-carousel').bootstrap.Carousel(owl-carousel, {
                        interval: 2000,
                        wrap: false
                      })*/
                    //var myCarousel = document.querySelector('#img-apd');
                    //var carousel = new bootstrap.Carousel(myCarousel);
                    var myCarousel = document.querySelector('#carouselExampleInterval')
                    var carousel = new bootstrap.Carousel(myCarousel, {
                        interval: 2000,
                    })

                      

                        // no urut
                    if (result.no_seri == 1 && mkp_id != 3) {
                        if( $( '.refisa' ).hasClass( 'hide-no-urut' )){
                            $('.refisa').removeClass( 'hide-no-urut' )
                        }
                        if(!$('#no_urut').prop('required')){
                            $('#no_urut').prop('required', true)
                        }
                    }else{
                        if(! $( '.refisa' ).hasClass( 'hide-no-urut' )){
                            $('.refisa').addClass( 'hide-no-urut' )
                        }
                        if($('#no_urut').prop('required')){
                            $('#no_urut').prop('required', false)
                        }
                    }
                }
                
                //console.log('ajax success');
            }
        });

        //console.log('picselect loaded');

        /*if ( fotoAPD !== '') {
            imgSrc = defaultPath + fotoAPD;
        } else {
            imgSrc = defaultPath + 'no-image.png';
        }
        $("#apdImg").attr("src",imgSrc);*/
    });

    $("#mapd_id").trigger("change");

});