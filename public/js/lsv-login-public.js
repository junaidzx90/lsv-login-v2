jQuery(function( $ ) {
	'use strict';

	// Email check
	function isEmail(email) {
  		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  		return regex.test(email);
	}

	var access = false;

	// Ignoring errors
	$('input').on("keyup", function () {
		$(this).css('border-color','#ddd');
	});

	// Error shoing
	function errorsshow(txt) {
		$('.signinbtn').append('<span class="errors">'+txt+'</span>');
		setTimeout(() => {
			$('.errors').remove();
		}, 1500);
	}

	$('#signinbtn').on("click", function (e) {
		e.preventDefault();

		let email = $('#email').val();
		let participants = $('#participants').val();

		if (email == "") {
			$('#email').css('border-color', 'red');
			errorsshow('Email is required');
			return false;
		}
		if (participants == "") {
			$('#participants').css('border-color', 'red');
			errorsshow('Please type the number of participants you have.');
			return false;
		}

		if (!isEmail(email)) {
			$('#email').css('border-color', 'red');
			errorsshow('Invalid Email');
			return false;
		}

		$.ajax({
			type: "post",
			url: public_ajax_requ.ajaxurl,
			data: {
				action: "lsv_login_requests",
				email: email,
				participants: participants,
				nonce: public_ajax_requ.nonce
			},
			beforeSend: () => {
				$('#signinbtn').prop('disabled',true);
				$('#signinbtn').val('Signing...');
			},
			dataType: "json",
			success: function (response) {
				if (response.error) {
					access = false;
					$('#signinbtn').val('Sing In');
					$('#signinbtn').removeAttr('disabled');
					$('#email').css('border-color', 'red');
					errorsshow(response.error);
					return false;
				}
				if (response.success) {
					location.href = response.success;
				}
			}
		});
	});
});
