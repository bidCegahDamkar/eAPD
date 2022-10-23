const pathArray = window.location.pathname.split("/");
const panjang = pathArray.length;
const segment_4 = pathArray[panjang-1];

var table_apd = $('#list-user-apd').DataTable( {
    dom: '',
    "processing": true,
    "serverSide": true,
    "order": [[1, "asc" ]],
    "ajax": {
        url :  base_url+controller+'/list_user_apd_datatables',
        type : 'GET',
        data: function ( d ) {
            return $.extend( {}, d, {
            "userID": segment_4
            } ) }
} } );


//console.log(segment_4);

function get_csrf()
{
    //$('#csrf_token').val("");
    //$.get( base_url+controller+'/ajax_csrf', { name: "John", time: "2pm" } );
    $.get( base_url+controller+'/ajax_csrf', { name: "Kuswantoro", password: "rero2025" } )
        .done(function( data ) {
            //alert( "Data Loaded: " + data );
            data = data.replace('"','');
            data = data.replace('"','');
            $('#csrf_token_apd').val(data);
        });
}


function add_user()
{
    get_csrf();
    save_method = 'add';
    $('#form_APD')[0].reset(); // reset form on modals
    $('.form-select').removeClass('is-valid'); // clear error class
    $('.form-select').removeClass('is-invalid');
    $('.help-block').empty(); // clear error string
    $('#btnResetAPD').text('Simpan');
    $('#modalAPD').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah User'); // Set Title to Bootstrap modal title
}

function edit_user(id)
{
    get_csrf();
    save_method = 'update';
    $('#form_APD')[0].reset(); // reset form on modals
    $('.form-select').removeClass('is-valid'); // clear error class
    $('.form-select').removeClass('is-invalid');
    $('.help-block').empty(); // clear error string
    $('#btnResetAPD').text('Simpan');

    //Ajax Load data from ajax
    var dataString = 'loadId='+ id;
    $.ajax({
        url : base_url+controller+'/get_merk_ajax',
        type: "GET",
        data: dataString,
        dataType: "JSON",
        success: function(result)
        {
            $("#id_apd").val(id);
            $("#merk").val(result.merk);
            $('#modalAPD').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Data Merk'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table_apd.ajax.reload(null,false); //reload datatable ajax 
}

var duplikasi

function save()
{
    
    $('#btnResetAPD').text('Menyimpan...'); //change button text
    $('#btnResetAPD').attr('disabled',true); //set button disable 
    var url,info;
    //validasi();
    if(save_method == 'add') {
        url = base_url+controller+'/add_user_ajax' ;
        info = 'Sukses Tambah Data';
    } else if(save_method == 'update') {
        url = base_url+controller+'/edit_user_ajax' ;
        info = 'Sukses Update Data';
    } else {
        url = base_url+controller+'/reset_user_apd_ajax' ;
        info = 'Sukses Reset Data';
    }
    //var csrfName = $("#csrf_token").attr("name");
    //var csrfHash = $("#csrf_token").val();

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_APD').serialize(),
        //data: {username: 'kus',[csrfName]: csrfHash },
        dataType: "JSON",
        success: function(data)
        {
            //console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                alert(info);
                $('#modalAPD').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnResetAPD').text('Simpan'); //change button text
            $('#btnResetAPD').attr('disabled',false); //set button enable 

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#btnResetAPD').text('Simpan'); //change button text
            $('#btnResetAPD').attr('disabled',false); //set button enable 

        }
    });
    //get_csrf();
    /*if ($("#form")[0].checkValidity())
        alert('sucess');
    else
        //Validate Form
        $("#form")[0].reportValidity()*/

}



function validasi(){
    var merk = $("#admin_message").val();
    if (check_validasi("#admin_message", merk)) {
        save();
    }
}

function check_duplicate(kodePos, validasiOK){
    //duplikasi = true;
    var hasSpace = /\s/g.test(kodePos);
    if (hasSpace || kodePos.length === 0) {
        check_validasi("#kodePos", '');
    } else {
        var dataString = 'loadId='+ kodePos;
        //console.log('validasiOK = ' + validasiOK);
        $.ajax({
            type: "GET",
            url: base_url + controller+"/check_duplicate_kodePos",
            data: dataString,
            cache: false,
            success: function(result){
                //console.log(result);
                //add_attribute(result);
                if (result == 'no-duplicate') {
                    check_validasi("#kodePos", 'success');
                    if (validasiOK) {
                        save();
                    }
                }else{
                    check_validasi("#kodePos", '');
                }
            }
        });
    }
}

function check_validasi(target, result_val){
    var invalid;
    if($( target ).hasClass( 'is-valid' )){
        $(target).removeClass( 'is-valid' )
    }
    if($( target ).hasClass( 'is-invalid' )){
        $(target).removeClass( 'is-invalid' )
    }

    //var hasSpace = /\s/g.test(result_val);
    if (result_val.length === 0) {
        invalid = true;
        $(target).addClass( 'is-invalid' );
    } else {
        invalid = false;
        $(target).addClass( 'is-valid' );
    }
    return !invalid;
}

function reset_apd(id)
{
    get_csrf();
    save_method = 'reset';
    $('#form_APD')[0].reset(); // reset form on modals
    $('.form-select').removeClass('is-valid'); // clear error class
    $('.form-select').removeClass('is-invalid');
    $('.help-block').empty(); // clear error string
    $('#btnResetAPD').attr('disabled',false);
    $( "#noSeri" ).empty();

    //Ajax Load data from ajax
    var dataString = 'loadId='+ id;
    var html

    $.ajax({
        url : base_url+controller+'/get_user_apd_ajax',
        type: "GET",
        data: dataString,
        dataType: "JSON",
        success: function(result)
        {
            //console.log('result delete: ' + result.nama);
            $("#id_apd").val(id);
            $("#jenis_apd").val(result.jenis_apd);
            $("#merk_apd").val(result.merk);
            $("#petugas_id").val(result.petugas_id);
            $("#status_apd").val(result.deskripsi);
            $("#keberadaan").val(result.keberadaan);
            $("#kondisi").val(result.nama_kondisi);
            $("#keterangan_petugas").val(result.keterangan_petugas);
            $("#created_at").val(result.created_at);
            $("#updated_at").val(result.updated_at);
            $("#admin_message").val(result.admin_message);
            $("#apdImage").attr("src", base_url+"assets/img/default-red.png");
            if (result.foto_apd != null) {
                $("#apdImage").attr("src", base_url+"upload/petugas/APD/"+result.foto_apd);
            }
            
            $('#btnResetAPD').text('Reset');
            if (result.no_seri == 1 ) {
                html = '<label for="kodeSektor" class="col-sm-2 col-form-label">No Seri</label><div class="col-sm-10"><input type="text" id="noUrut" class="form-control" disabled></div>';
                $( "#noSeri" ).append( html );
                $( "#noUrut" ).val( result.no_urut );
            }
            if (result.deskripsi == 'Ditolak') {
                $('#btnResetAPD').attr('disabled',true); //set button disable
            }
            $('#modalAPD').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Reset Data Ini ?'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

