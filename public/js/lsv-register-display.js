jQuery(function( $ ) {
	'use strict';
	function isEmail(email) {
  		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  		return regex.test(email);
	}


	// Ignoring errors
	$('input').on("keyup", function () {
		$(this).css('border-color','#ddd');
	});

	// Error shoing
	function errorsshow(txt) {
		$('.submitwrp').append('<span class="errors">'+txt+'</span>');
		setTimeout(() => {
			$('.errors').remove();
		}, 1500);
	}
	// Emailvalidity
	var access = false;

	// Check email validity
	$('#email').on('blur', function () {
		let eml = $(this).val();
		$.ajax({
			type: "post",
			url: public_ajax_requ.ajaxurl,
			data: {
				action: "lsv_email_check",
				email: eml,
				nonce: public_ajax_requ.nonce
			},
			dataType: "json",
			success: function (response) {
				if (response.exist) {
					access = false;
					$('#email').css('border-color', 'red');
					$('.emlwrp').append('<span class="errors">This email already exist.</span>');
					setTimeout(() => {
						$('.errors').remove();
					}, 3000);
					return false;
				}
				if (response.notexist) {
					access = true;
				}
			}
		});
	});

	// Registering process
	$('.login-form form').on("submit", function (e) {
		e.preventDefault();

		let firstname = $('#firstname').val();
		let lastname = $('#lastname').val();
		let email = $('#email').val();
		let phone = $('#phone').val();
		let country = $('#country').val();

		if (firstname == "") {
			$('#firstname').css('border-color', 'red');
			errorsshow('First name required');
			return false;
		}
		if (lastname == "") {
			$('#lastname').css('border-color', 'red');
			errorsshow('Last name required');
			return false;
		}
		if (email == "") {
			$('#email').css('border-color', 'red');
			errorsshow('Email is required');
			return false;
		}

		if (!isEmail(email)) {
			$('#email').css('border-color', 'red');
			errorsshow('Invalid Email');
			return false;
		}

		if (country == "") {
			$('#country').css('border-color', 'red');
			errorsshow('Country is required');
			return false;
		}

		// Rest of the code will be here
		if (access === true) {
			let data = { firstname, lastname, email, phone, country };
			$.ajax({
				type: "post",
				url: public_ajax_requ.ajaxurl,
				data: {
					action: "lsv_registration_process",
					data: data,
					nonce: public_ajax_requ.nonce
				},
				beforeSend: () => {
					$(".submitwrp input.btn").prop('disabled', true);
					$(".submitwrp input.btn").val("Processing...");	
				},
				dataType: "json",
				success: function (response) {
					if (response.success) {
						location.href = response.success;
					} else {
						location.reload();
					}
				}
			});
		}

	});

});
