$(function () {
	$("#categorybtn").click(function () {
		$(".category").slideToggle(200);
	});

	$("#profile").click(function () {
		$("#profileDropDown").slideToggle(200);
	});

	var $window = $(window);
	if ($window.width() < 1000) {
		$("#profileDropDown").append($("#browsebtn").parent());

	}
	$window.resize(function () {
		if ($window.width() < 1000) {
			$("#profileDropDown").append($("#browsebtn").parent());
		} else {
			$("#searchform").before($("#browsebtn").parent());

		}
	});


	$("#profilesmall").click(function () {
		$("#profileDropDown").slideToggle(200);
		if ($("#smallprofileicon").attr('src') == 'Assets/Icons_Hamburger.png') {
			$("#smallprofileicon").attr('src', 'Assets/Icons_Cross.png');
			console.log('hi');
		} else {
			$("#smallprofileicon").attr('src', 'Assets/Icons_Hamburger.png');
		}
	});

	$("#browsebtn").click(function () {
		$("#browseDropDown").slideToggle(200);
	});

	$('main, footer').click(function (e) {
		$('#browseDropDown').slideUp(200);
		$('.category').slideUp(200);
		$('#profileDropDown').slideUp(200);

	});

	if (window.location.pathname.split('/').pop() == 'index.php') {
		$("#search").keyup(function () {
			$.ajax({
				url: 'search.php',
				data: {
					searchstring: $('#search').val(),
					index: true
				}
			}).done(function (result) {
				$("main").html('');
				$("main").append(result);

			});
		});
	} else if (window.location.pathname.split('/').pop() == 'category.php') {
		$("#search").keyup(function () {
			$.ajax({
				url: 'search.php',
				data: {
					searchstring: $('#search').val(),
					catid: id
				}
			}).done(function (result) {
				$("main").html('');
				$("main").append(result);

			});
		});

	} else if (window.location.pathname.split('/').pop() == 'orderbydate.php') {
		$('#search').keyup(function () {
			$.ajax({
				url: 'search.php',
				data: {
					searchstring: $('#search').val(),
					order: $('#order').val()
				}
			}).done(function (result) {
				$("main").html('');
				$("main").append(result);
				$('#order').change(orderFunction);

			});

		});

		function orderFunction() {
			$.ajax({
				url: 'search.php',
				data: {
					searchstring: $('#search').val(),
					order: $('#order').val()
				}
			}).done(function (result) {
				$("main").html('');
				$("main").append(result);
				$('#order').change(orderFunction);

			});

		}
		$('#order').change(orderFunction);
	}

});
