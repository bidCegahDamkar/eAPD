$(document).ready(function() {
    $('.select2-basic-single').select2();

    $("#statusid").select2();

    $("#statusid").on("change", function () {
        $("#jabatanid option[value]").remove();
        
        var newOptions = []; // the result of your JSON request
      
        //$("#jabatanid").append(newOptions).val("").trigger("change");
      });
      
    $("#jabatanid").select2({
        ajax: {
            url: base_url+controller+'/list_jabatan_select2',
            dataType: 'json',
            delay: 250,
            type: 'GET',
            data: function (params) {
                return {
                    id: $("#statusid").val(),
                    jabid: jab_id,
                    search: params.term
                }
            },
        }
    });

    $("#plt").select2({
        ajax: {
            url: base_url+controller+'/set_plt_select2',
            dataType: 'json',
            delay: 250,
            type: 'GET',
            data: function (params) {
                return {
                    search: params.term
                }
            },
        }
    });

    $('.myselect2').select2({
    });

    $("#penugasan").select2({
        ajax: {
            url: base_url+controller+'/penugasan_select2',
            dataType: 'json',
            type: 'GET',
            data: function (params) {
                return {
                    kode_pos: $("#sektor").val(),
                    search: params.term
                }
            },
        }
    });

});