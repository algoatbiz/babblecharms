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
				data = getFormValues(form),
				msgContainer = form.querySelector('#form-message');

			data.c_time = performance.now() >= 7000 ? 0 : 1;
			data.form_id = this.id;

			form.querySelector('button').className = 'disable';

			ax.post(form.action, formDataAjax(data))
			.then(function(r) {
				clearErrors(form);
				msgContainer.innerHTML = r.data.message;
			 	msgContainer.className = 'success';

				form.querySelector('button').className = '';
			})
			.catch(function(e) {
				clearErrors(form);
			 	setErrors(form, e.response.data);
			 	msgContainer.innerHTML = e.response.data.message;
			 	msgContainer.className = 'error';

				form.querySelector('button').className = '';
			});
		});
	}
}

function formDataAjax(data) {
	var formData = new FormData();

	for(var key in data)
		formData.append(key, data[key]);

	return formData
}

function getFormValues(form) {
	var data = {},
		types = ['INPUT', 'TEXTAREA', 'SELECT'];

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
	for(var key in responseData.errors) {
		var field = form.querySelector('#'+key);

		if(field)
			field.className += ' error';
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

/*=============================
=          Add To Bag         =
=============================*/

var bagCookieName = 'shopping-bag',
	shoppingBag = document.querySelector('#shopping-bag span'),
	addToBagButton = document.querySelectorAll('.add-bag:not(.added)');

if(addToBagButton.length > 0) {
	for(var i=0; i<addToBagButton.length; i++) {
		addToBagButton[i].addEventListener('click', function(e) {
			e.preventDefault();

			updateCartCookie(this.getAttribute('product-id'), false);

			this.innerHTML = 'Added to bag';
			this.className += ' added';

			shoppingBag.innerHTML = parseInt(shoppingBag.innerHTML) + 1;
		});
	}
}

function updateCartCookie(id, el) {
	var bag = getCookie(bagCookieName),
		products = bag ? JSON.parse(bag) : {},
		currentQty = products[id] ? parseInt(products[id]) : 1,
		qty = el ? (el.classList.contains('add') ? currentQty + 1 : currentQty - 1) : currentQty;

	products[id] = qty;

	document.cookie = bagCookieName + '=' + JSON.stringify(products) + ';path=/';

	return qty;
}

function getCookie(cname) {
	var name = cname + '=',
		ca = document.cookie.split(';');

	for(var i=0; i<ca.length; i++) {
		var c = ca[i];

		while(c.charAt(0) == ' ')
			c = c.substring(1);

		if(c.indexOf(name) == 0)
			return c.substring(name.length, c.length);
	}

	return false;
}

/*=============================
=        Update Quantity      =
=============================*/

var qtyButtons = document.querySelectorAll('.quantity-buttons a');

if(qtyButtons.length > 0) {
	for(var i=0; i<qtyButtons.length; i++) {
		qtyButtons[i].addEventListener('click', function(e) {
			e.preventDefault();

			loading(true);

			var productId = this.parentNode.dataset.productId,
				product = document.querySelector('li.product-'+productId);
				qty = updateCartCookie(productId, this),
				data = {};

			product.querySelector('.quantity').innerHTML = qty;

			data.product_id = productId;
			data.quantity = qty;

			ax.post('cart-process', formDataAjax(data))
			.then(function(r) {
				product.querySelector('.total-price').innerHTML = r.data.product_total;
				document.getElementById('subtotal').innerHTML = r.data.subtotal;

				loading(false);

				if(qty > 1 && !r.data.max)
					product.querySelector('.quantity-buttons .disabled').classList.remove('disabled');
				else if(qty == 1)
					product.querySelector('.quantity-buttons .decrease').className += ' disabled';
				else if(r.data.max)
					product.querySelector('.quantity-buttons .add').className += ' disabled';
			})
			.catch(function(e) {
				console.log(e.error);
			});
		});
	}
}

var shipping_methods = document.querySelectorAll('input[name="shipping_method"]');

if(shipping_methods.length > 0) {
	for(var i=0; i<shipping_methods.length; i++) {
		shipping_methods[i].addEventListener('change', function() {
			loading(true);

			document.cookie = 'shipping-method' + '=' + this.value + ';path=/';

			ax.post('cart-process')
			.then(function(r) {
				document.getElementById('subtotal').innerHTML = r.data.subtotal;

				loading(false);
			})
			.catch(function(e) {
				console.log(e.error);
			});
		});
	}
}

function loading(show) {
	document.getElementById('loading').style.display = show ? 'block' : 'none';
}