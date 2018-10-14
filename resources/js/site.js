/*=============================
=          Initialize         =
=============================*/

var body = document.body,
	header = document.getElementById('header'),
	topbar = document.getElementById('topbar'),
	navigation = document.getElementById('nav-wrap'),
	menu = document.getElementById('menu'),
	mobileMenu = document.getElementById('mobile-menu'),
	desktopLinks = document.querySelectorAll('#menu .has-children > a'),
	main = document.getElementById('main');

function newAx() {
	return axios.create({
		headers: {
			'X-Requested-With':  'XMLHttpRequest'
		}
	});
}

var ax = newAx();

var mobile = isMobile();

body.className += ' ' + (mobile ? 'mobile' : 'desktop');

function isMobile() {
	var md = new MobileDetect(window.navigator.userAgent);
	return md.mobile() ? true : false;
}

var phone = window.innerWidth < 480 ? true : false;

/*=============================
=     IE / Browser Version    =
=============================*/

var ua = window.navigator.userAgent,
	msie = ua.indexOf('MSIE '),
	edge = ua.indexOf('Edge/'),
	trident = ua.indexOf('Trident/'),
	version = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);

body.className += trident > 0 || msie > 0 ? ' ie' : edge > 0 ? ' edge' : '';

/*=============================
=             Menu            =
=============================*/

for(var i=0; i<desktopLinks.length; i++)
	desktopLinks[i].addEventListener('click', toggleMenu);

body.addEventListener('click', function(e) {
	var isMenu = menu ? menu.contains(e.target) : false;
	if(!isMenu && menu) {
		closeMenu(this, menu);
	}
});

function toggleMenu(e) {
	if(e.currentTarget.getAttribute('href') !== '#')
		return true;

	e.preventDefault();

	var sub = this.nextSibling,
		animation = window.getComputedStyle(sub).display == 'block' ? 'slideUp' : 'slideDown';

	closeMenu(this, this.parentNode.parentNode);

	Velocity(sub, animation, {duration: 150});
	this.parentNode.classList.toggle('open');
}

function closeMenu(el, parent) {
	var openMenu = parent.querySelector('.open > a');
	if(openMenu && openMenu !== el) {
		Velocity(openMenu.nextSibling, 'slideUp', {duration: 150});
		openMenu.parentNode.classList.remove('open');

		var openChild = openMenu.nextSibling.querySelector('.open > a');
		if(openChild) {
			openChild.parentNode.classList.remove('open');
			openChild.nextSibling.style.display = 'none';
		}
	}
}

var productCategoryLinks = document.querySelectorAll('.product-submenu a');
for(var i=0; i<productCategoryLinks.length; i++)
	productCategoryLinks[i].addEventListener('mouseover', function() {
		document.querySelector('.product-submenu .product-image').style = "background-image: url('"+this.dataset.image+"')";
	});

/*=============================
=         Product Images      =
=============================*/

var products = document.querySelectorAll('body.product .photo-container img');
if(products.length > 0) {
	for(var i=0; i<products.length; i++) {
		products[i].addEventListener('mouseover', function() {
			document.querySelector('body.product .photo-container').style.backgroundImage = 'url("'+this.src+'")';
		});
	}
}

/*=============================
=            Gallery          =
=============================*/

var galleryContainer = document.querySelector('body#photo-gallery #gallery-container');
if(galleryContainer) {
	var images = galleryContainer.querySelectorAll('figure');

	for(var i=0; i<images.length; i++) {
		images[i].addEventListener('click', function() {
			setBigImage(this);
		});
	}

	var nextPrev = galleryContainer.querySelectorAll('.next, .prev');

	for(var i=0; i<nextPrev.length; i++) {
		nextPrev[i].addEventListener('click', function() {
			var index = parseInt(galleryContainer.querySelector('.current').dataset.index);
			index = this.className == 'next' ? (index+1)%images.length : (index == 0 ? images.length - 1 : index-1);
			setBigImage(images[index]);
		});
	}
}

function setBigImage(figure) {
	galleryContainer.querySelector('.current').className = '';
	galleryContainer.querySelector('div:first-child').style.backgroundImage = 'url('+figure.querySelector('img').src+')';
	figure.className = 'current';
}

/*=============================
=          Google Map         =
=============================*/

function initMap() {

    var latLng = new google.maps.LatLng(40.59649659999999, -112.48481989999999);

    var mapOptions = {
        zoom: 17,
        streetViewControl: false, // hide the yellow Street View pegman
        scaleControl: true, // allow users to zoom the Google Map
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false,
        center: latLng,
        disableDefaultUI: true
    };

    var map = new google.maps.Map(document.getElementById('map'), mapOptions);

	var marker = new google.maps.Marker({
		position: latLng,
		map: map,
	});
}

/*=============================
=         Form Process        =
=============================*/

var forms = document.querySelectorAll('form:not(#search)');

if(forms.length > 0) {
	for(var i=0; i<forms.length; i++) {
		forms[i].addEventListener('submit', function(e) {
			e.preventDefault();

			var form = this,
				data = getFormValues(form);

			data.c_time = performance.now() >= 7000 ? 0 : 1;

			var formData = new FormData();

			for(var key in data)
				formData.append(key, data[key]);

			ax.post(form.action, formData)
			.then(function(r) {
				form.querySelector('.message').innerHTML = r.data.message;
				// var content = document.querySelector('#top .content');

				// Velocity(content, 'scroll', {
				// 	duration: 400,
				// 	offset: -(header.offsetHeight)
				// });
			 // 	Velocity(content, 'slideUp', {
			 // 		progress: function(el, complete) {
			 // 			el[0].style.opacity = 1 - complete;
			 // 		},
			 // 		duration: 450,
			 // 		complete: function(el) {
			 // 			el[0].innerHTML = r.data.message;
			 // 			Velocity(el[0], 'slideDown', {
			 // 				progress: function(el, complete) {
			 // 					el[0].style.opacity = complete;
			 // 				},
			 // 				delay: 350,
			 // 				duration: 450
			 // 			});
			 // 		}
			 // 	});
			})
			.catch(function(e) {
				clearErrors(form);
			 	setErrors(form, e.response.data);
			 	form.querySelector('.message').innerHTML = e.response.data.message;
			});
		});
	}
}

function getFormValues(form) {
	var data = {};
	var types = ['INPUT', 'TEXTAREA', 'SELECT'];

	for(var i=0; i<types.length; i++)
		getInputValues(form, data, types[i]);

	return data;
}

function getInputValues(form, data, type) {

	var inputs = form.getElementsByTagName(type);

	for(var i=inputs.length-1; i>=0; i--) {
		if(inputs[i].name.length)
			data[inputs[i].name] = inputs[i].value;
	}

	return data;
}

function clearErrors(form) {

	var errors = form.getElementsByClassName('error');

	for(var e=errors.length - 1; e>=0; e--)
		errors[e].className = errors[e].className.replace(' error', '');
}

function setErrors(form, responseData) {

	// Velocity(document.querySelector('form'), 'scroll', {
	// 	offset: -(header.offsetHeight + 16),
	// 	duration: 400
	// });

	for(var key in responseData.errors) {
		var field = form.querySelector('#'+key);

		if(field)
			field.className += ' error';
			// field.parentNode.className += ' error';
	}
}

/*=============================
=            Search           =
=============================*/

var search = document.getElementById('search');
if(search) {
	search.addEventListener('submit', function(e) {
		var input = this.querySelector('input');
		if(input.value === '')
			e.preventDefault();
	})
}