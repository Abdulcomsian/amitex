// $('.desktop').menuBreaker();
// $(window).on('load resize', function() {
// 	$('.mobile').height($(window).height() - $('nav').height());
// });
$(document).ready(function() {
	$(".menu li a").each(function() {
		if($(this).next().length > 0) {
			$(this).addClass("parent");
		};
	})
	var menux = $('.menu li a.parent');
	$('<div class="more"><img src="'+base_url+'assets/images/btn-hamburger.png" alt=""></div>').insertBefore(menux);
	$('.more').click(function() {
		$(this).parent('li').toggleClass('open');
	});
	$('.menu-btn').click(function() {
		$('nav').toggleClass('menu-open');
	});
	$("img").on("contextmenu", function(e) {
		return false;
	});
	$("img").mousedown(function(e) {
		e.preventDefault()
	});
	$('#table_id').DataTable({
		responsive: true,
		bAutoWidth: false
	});
});
(function($) {
	'use strict';
	// call our plugin
	var Nav = new hcOffcanvasNav('#main-nav', {
		disableAt: false,
		customToggle: '.toggle',
		levelSpacing: 40,
		navTitle: 'Navigation',
		levelTitles: true,
		levelTitleAsBack: true,
		pushContent: false,
		labelClose: false,
		position: 'left'
	});
	// add new items to original nav
	$('#main-nav').find('li.add').children('a').on('click', function() {
		var $this = $(this);
		var $li = $this.parent();
		var items = eval('(' + $this.attr('data-add') + ')');
		$li.before('<li class="new"><a href="#">' + items[0] + '</a></li>');
		items.shift();
		if(!items.length) {
			$li.remove();
		} else {
			$this.attr('data-add', JSON.stringify(items));
		}
		Nav.update(true); // update DOM
	});
	// demo settings update
	const update = function(settings) {
		if(Nav.isOpen()) {
			Nav.on('close.once', function() {
				Nav.update(settings);
				Nav.open();
			});
			Nav.close();
		} else {
			Nav.update(settings);
		}
	};
	$('.actions').find('a').on('click', function(e) {
		e.preventDefault();
		var $this = $(this).addClass('active');
		var $siblings = $this.parent().siblings().children('a').removeClass('active');
		var settings = eval('(' + $this.data('demo') + ')');
		if('theme' in settings) {
			$('body').removeClass().addClass('theme-' + settings['theme']);
		} else {
			update(settings);
		}
	});
	$('.actions').find('input').on('change', function() {
		var $this = $(this);
		var settings = eval('(' + $this.data('demo') + ')');
		if($this.is(':checked')) {
			update(settings);
		} else {
			var removeData = {};
			$.each(settings, function(index, value) {
				removeData[index] = false;
			});
			update(removeData);
		}
	});
})(jQuery);


