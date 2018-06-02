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