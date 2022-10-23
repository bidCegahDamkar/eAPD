$( document ).ready(function() {
    $('#mapd_id').on('change', function() {
        //alert( $('select#mapd_id option:selected').attr('data-img') );
        var imgSrc;
        var defaultPath = base_url + 'assets/petugas/APD/';
        //var fotoAPD = $('select#mapd_id option:selected').attr('val');
        var ma_id =$('#mapd_id').find(":selected").val();
        //console.log(fotoAPD);
        $( ".owl-carousel" ).remove();

        var dataString = 'loadId='+ ma_id;
        $.ajax({
            url : base_url+controller+'/get_img_apd_ajax',
            type: "GET",
            data: dataString,
            dataType: "JSON",
            success: function(result)
            {
                const obj = JSON.parse(result.foto_mapd);
                let html = '<div class="carousel-full owl-carousel owl-theme" >';
                for (let i = 0; i < obj.length; i++) {
                    //console.log('result delete: ' + obj[i]);
                    html += '<div class="item" style="width:350px"><img src="'+base_url+'assets/petugas/APD/'+obj[i]+'" alt="alt" class="imaged img-fluid"></div>';
                }
                html += '</div>';
                $( "#img-apd" ).append( html );
                //$('#img-apd').owlCarousel();
                $('.owl-carousel').owlCarousel({
                    margin:10,
                    autoWidth:true,
                    items:1
                })
            }
        });


        /*if ( fotoAPD !== '') {
            imgSrc = defaultPath + fotoAPD;
        } else {
            imgSrc = defaultPath + 'no-image.png';
        }
        $("#apdImg").attr("src",imgSrc);*/
      });
});