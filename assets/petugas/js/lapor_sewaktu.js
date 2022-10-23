$( document ).ready(function() {
	//$('.needs-validation').attr('novalidate', true);
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
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
    })();
	
	$("#jenis_lap").on('change', function() {
		$('#apd')
			.find('option')
			.remove()
			.end()
		;
        if (this.value == '1') {
			if($( '.lvl_rsk' ).hasClass( 'hide-kus' )){
                $('.lvl_rsk').removeClass( 'hide-kus' )
            }
			$.each(list_item_rusak, function (i, item) {
				$('#apd').append($('<option>', { 
					value: item.val,
					text : item.text 
				}));
			});
		}else{
			$.each(list_item_hilang, function (i, item) {
				if(! $( '.lvl_rsk' ).hasClass( 'hide-kus' )){
					$('.lvl_rsk').addClass( 'hide-kus' )
				}
				$('#apd').append($('<option>', { 
					value: item.val,
					text : item.text 
				}));
			});
		}
	});

	$('.datepicker').datepicker({
        format: "dd-MM-yyyy",
        todayBtn: "linked",
        clearBtn: true,
        language: "id",
		endDate: "today",
		todayHighlight: true,
		daysOfWeekHighlighted: "0,6",
		container:'#date-container'
    });
});