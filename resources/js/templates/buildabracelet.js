/*=============================
=          Step Links         =
=============================*/

var stepLinks = document.querySelectorAll('#steps a');
for(var i=0; i<stepLinks.length; i++) {
	stepLinks[i].addEventListener('click', function(e) {
		e.preventDefault();

		if(document.querySelector('#canvas .bracelet').style !== '') {
			document.querySelector('#steps .current').className = '';
			this.className = 'current';
		}
	});
}

/*=============================
=         Product Select      =
=============================*/

var products = document.querySelectorAll('#product-select-container a');
for(var i=0; i<products.length; i++) {
	products[i].addEventListener('click', function(e) {
		e.preventDefault();

		if(this.className == 'bracelet') {
			document.querySelector('#canvas .bracelet').style.backgroundImage = this.querySelector('span:first-child').style.backgroundImage;
		}
	});
}