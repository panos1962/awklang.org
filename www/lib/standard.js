$(window).on('load', () => Globals.setup());
$(document).ready(() => Globals.setup());

//////////////////////////////////////////////////////////////////////////////@

Globals.setupCount = 0;

Globals.setup = function() {
	switch (Globals.setupCount) {
	case 0:
		Globals.
		windowSetup().
		navigatorSetup().
		sliderSetup().
		mainSetup();
		break;
	case 1:
		Globals.navigatorExpand && Globals.headerIconDOM.click();
		Globals.windowDOM.resize();
		break;
	}

	Globals.setupCount++;

	return Globals;
};

Globals.windowSetup = function() {
	Globals.windowDOM = $(window);
	Globals.bodyDOM = $(document.body);

	Globals.headerDOM = $('#header');
	Globals.footerDOM = $('#footer');
	Globals.arenaDOM = $('#arena');

	Globals.navigatorColumnDOM = $('#navigatorColumn');
	Globals.navigatorDOM = $('#navigator');
	Globals.sliderDOM = $('#sliderColumn');

	Globals.mainColumnDOM = $('#mainColumn');
	Globals.mainDOM = $('#main');

	Globals.headerIconDOM = $('#headerIcon');
	Globals.skinDOM = $('#skin');

	Globals.windowDOM.on('resize', () => Globals.windowResize());

	Globals.bodyDOM.
	on('mouseenter', '.tab', function(e) {
		e.stopPropagation();

		if (Globals.tabArmTimer) {
			clearTimeout(Globals.tabArmTimer);
			delete Globals.tabArmTimer;
		}

		$('.tabArmed').removeClass('tabArmed');
		$(this).addClass('tabArmed');
	}).
	on('mouseleave', '.tab', function(e) {
		e.stopPropagation();

		$(this).removeClass('tabArmed');
		Globals.tabArmTimer = setTimeout(() => $(this).parent().closest('.tab').addClass('tabArmed'), 50);
	});

	return Globals;
};

Globals.navigatorPaddingRight = 2;

Globals.navigatorSetup = function() {
	Globals.navigatorDOM.
	css('padding-right', Globals.navigatorPaddingRight + 'px').
	on('click', '.ntab', function(e) {
		e.stopPropagation();

		var nitem = $(this).data('nitem');

		if (nitem.linkGet())
		return;

		e.preventDefault();

		if (nitem.isOpen())
		nitem.menuClose();

		else
		nitem.menuOpen();
	});

	return Globals;
};

Globals.sliderSetup = function() {
	Globals.sliderDOM.on('mousedown', (e) => Globals.resizeStart(e));
	return Globals;
};

Globals.mainSetup = function() {
	if (Globals.main)
	Globals.main();

	Globals.navigatorCheck();
	return Globals;
};

//////////////////////////////////////////////////////////////////////////////@

Globals.windowResize = function() {
	const bodyHeight = Globals.bodyDOM.outerHeight(true);
	const headerHeight = Globals.headerDOM.outerHeight(true);
	const footerHeight = Globals.footerDOM.outerHeight(true);
	const arenaHeight = bodyHeight - headerHeight - footerHeight;

	Globals.arenaDOM.css('height', arenaHeight + 'px');
	Globals.navigatorDOM.css('height', arenaHeight + 'px');

	Globals.mainWidthLoosen();
	Globals.mainDOM.css('height', arenaHeight + 'px');
	Globals.mainWidthTighten();
	Globals.navigatorWidthAdjust();

	if (Globals.initOk)
	return Globals;

	Globals.initOk = true;

	// "arena" visibility is set to hidden in order to avoid
	// annoying flashing when page renders.

	Globals.arenaDOM.css('visibility', 'visible');

	// When URL anchor is present, Chrome and other browsers
	// may not scroll to put specified anchor into view.
	// Maybe this has to do with the initial visibility state
	// of the "main" division. Problem solved by reseting the
	// anchor.

	if (!location.hash)
	return Globals;

	const hash = location.hash;
	location.hash = '';
	location.hash = hash;

	return Globals;
};

Globals.resizeStart = function(e) {
	e.preventDefault();

	Globals.resizeMouseX = e.clientX;

	Globals.windowDOM.
	on('mousemove', (e) => Globals.resizeMove(e)).
	on('mouseup', (e) => Globals.resizeStop(e));

	return Globals;
};

Globals.resizeMove = function(e) {
	if (!Globals.hasOwnProperty('resizeMouseX'))
	return Globals;

	let w = Globals.navigatorColumnDOM.width() + e.clientX - Globals.resizeMouseX;
	Globals.mainWidthLoosen();
	Globals.resizeMouseX = e.clientX;

	if (w < 0) {
		w = 0;
		var display = 'none';
	}
	else {
		display = 'table-cell';
	}

	Globals.navigatorColumnDOM.css({
		width: w + 'px',
		maxWidth: w + 'px',
		display: display,
	});

	Globals.
	navigatorWidthAdjust().
	mainWidthTighten();

	return Globals;
};

Globals.navigatorWidthAdjust = function() {
	let w = Globals.navigatorColumnDOM.width() - Globals.navigatorPaddingRight;

	if (w < 0)
	w = 0;

	Globals.navigatorDOM.css({
		width: w + 'px',
		maxWidth: w + 'px',
	});

	return Globals;
};

Globals.resizeStop = function(e) {
	Globals.windowDOM.
	off('mousemove').
	off('mouseup');

	delete Globals.resizeMouseX;
	Globals.mainDOM.css('width', Globals.mainColumnDOM.width());
	$('pre').css('display', '');

	return Globals;
};

// Loosen "main" width in order to be set according to "mainColumn" width.
// In order to do so we have to set "pre" elements width styling property
// to zero.

Globals.mainWidthLoosen = function() {
	$('pre').css('width', '0px');
	Globals.mainDOM.css('width', '');

	return Globals;
};

// Tighten "main" width for "pre" elements to display horizontal scrollbars
// when needed. Then reset "pre" elements width styling property to default.

Globals.mainWidthTighten = function() {
	Globals.mainDOM.css('width', Globals.mainColumnDOM.width() + 'px');
	$('pre').css('width', '');

	return Globals;
};


//////////////////////////////////////////////////////////////////////////////@

Globals.navigatorSet = function(navigator) {
	Globals.navigator = navigator;
	Globals.navigatorDOM.append(navigator.domGet());

	if (navigator.notMenu())
	return Globals;

	Globals.headerIconDOM.
	addClass('headerIconOn').
	attr('title', 'Toggle navigator').
	on('click', function(e) {
		e.preventDefault();
		e.stopPropagation();
		Globals.navigatorToggle();
	});

	return Globals;
};

Globals.navigatorCheck = function() {
	if (Globals.navigator)
	return Globals;

	Globals.navigatorColumnDOM.css('display', 'none');
	Globals.sliderDOM.css('display', 'none');

	return Globals;
};

Globals.navigatorToggle = function() {
	if (!Globals.navigator)
	return Globals;

	// Search navigation items for open ones. If found, then these must
	// be closed.

	let count = 0;

	Globals.navigator.nitemWalk((nitem) => {
		if (nitem.isOpen()) {
			nitem.menuClose();
			count++;
		}
	});

	// If no navigation item found open, then all navigation items are
	// closed, so we open them all.

	if (count)
	Globals.headerIconDOM.removeClass('headerIconOff').addClass('headerIconOn');

	else {
		Globals.navigator.nitemWalk((nitem) => nitem.menuOpen(true));
		Globals.headerIconDOM.removeClass('headerIconOn').addClass('headerIconOff');
	}

	return Globals;
};

//////////////////////////////////////////////////////////////////////////////@

Globals.skinToggle = function() {
	switch (Globals.skin) {
	case 'debug':
		Globals.skin = 'muted';
		break;
	case 'muted':
		Globals.skin = 'metro';
		break;
	case 'metro':
		Globals.skin = 'default';
		break;
	default:
		Globals.skin = 'debug';
		break;
	}

	Globals.skinDOM.attr('href', Globals.baseUrl + 'lib/skin/' + Globals.skin + '.css');
	$.get(Globals.baseUrl + 'lib/skin.php?skin=' + Globals.skin);

	return false;
};

//////////////////////////////////////////////////////////////////////////////@

// Class "Nitem" represents a navigator item, that is a navigator tab.
//
// Navigator items may have the following properties:
//
//	title		The text to be displayed
//	description	A more descriptive text for the item
//
//	link		Link or anchor
//	nlist		Sub nitem list

Nitem = function(attr) {
	if (attr === undefined)
	attr = {};

	for (const i in attr)
	this[i] = attr[i];
};

Nitem.prototype.titleSet = function(title) {
	this.title = title;
	return this;
};

Nitem.prototype.titleGet = function() {
	return this.title;
};

Nitem.prototype.descriptionGet = function() {
	return this.description ? this.description : this.titleGet();
};

Nitem.prototype.linkGet = function() {
	return this.link;
};

Nitem.prototype.targetGet = function() {
	return this.target;
};

Nitem.prototype.isMenu = function() {
	if (!this.nlist)
	return false;

	return (this.nlist.length > 0);
};

Nitem.prototype.notMenu = function() {
	return !this.isMenu();
};

Nitem.prototype.openSet = function() {
	this.open = true;
	return this;
};

Nitem.prototype.closeSet = function() {
	delete this.open;
	return this;
};

Nitem.prototype.isOpen = function() {
	return this.open;
};

Nitem.prototype.bulletSet = function(bullet) {
	this.bullet = bullet;
	return this;
};

Nitem.prototype.bulletGet = function() {
	return this.bullet;
};

Nitem.prototype.bulletDomSet = function(bullet) {
	this.bulletDOM.html(this.bullet ? this.bullet : bullet);
	return this;
};

Nitem.prototype.nitemWalk = function(callback) {
	if (!this.nlist)
	return this;

	this.nlist.forEach((nitem) => callback(nitem));

	return this;
};

Nitem.prototype.domGet = function() {
	if (this.DOM)
	return this.DOM;

	this.DOM = $('<div>').addClass('nitem');
	this.bulletDOM = $('<div>').addClass('nitemBullet');
	const ntabDOM = $('<div>').addClass('tab ntab').attr('title', this.descriptionGet()).data('nitem', this).
		append(this.bulletDOM).append($('<div>').addClass('nitemTitle').html(this.titleGet()));

	const link = this.linkGet();

	if (!link) {
		this.DOM.append(ntabDOM);

		if (this.notMenu()) {
			ntabDOM.addClass('ntabInactive');
			this.bulletDomSet('&#215;');
			return this.DOM;
		}

		this.bulletDomSet('&#9660;');

		this.nitemWalk((nitem) => {
			// If title missing, then get parent's title

			const title = nitem.titleGet();

			if (!title) {
				nitem.titleSet(this.titleGet());
				nitem.bulletSet('&#8627;');
			}

			ntabDOM.append(nitem.domGet());
		});
			
			
		return this.DOM;
	}

	let anchorDOM = $('<a>');

	switch (link.substr(0, 1)) {
	case '#':	// internal link (anchor)
		anchorDOM.attr('href', link);
		this.bulletDomSet('&#9727;');
		break;
	case '<':	// download button
		anchorDOM.attr('href', link.substr(1));
		this.bulletDomSet('&#8595;');
		anchorDOM.attr('download', '');
		break;
	default:	// external link
		anchorDOM.attr('href', link);
		this.bulletDomSet('&#9721;');

		let target = this.targetGet();

		if (!target)
		target = '_blank';

		anchorDOM.attr('target', target);
	}

	this.DOM.append(anchorDOM.append(ntabDOM));
	return this.DOM;
};

Nitem.prototype.menuOpen = function(expand) {
	if (this.notMenu())
	return this;

	this.openSet();
	this.bulletDOM.html('&#9650;');
	this.nitemWalk((nitem) => {
		nitem.domGet().css('display', 'block');

		if (expand)
		nitem.menuOpen();
	});

	return this;
};

Nitem.prototype.menuClose = function() {
	if (this.notMenu())
	return this;

	this.closeSet();
	this.bulletDOM.html('&#9660;');
	this.nitemWalk((nitem) => nitem.menuClose().domGet().css('display', 'none'));

	return this;
};

//////////////////////////////////////////////////////////////////////////////@

Navigator = function() {
	this.nlist = [];
	[].slice.call(arguments).forEach((nitem) => this.nitemPush(nitem));
	return this;
};

Navigator.prototype.nitemPush = function() {
	[].slice.call(arguments).forEach((arg) => this.nlist.push(arg));
	return this;
};

Navigator.prototype.nitemWalk = function(callback) {
	this.nlist.forEach((nitem) => callback(nitem));
	return this;
};

Navigator.prototype.isMenu = function() {
	for (let i = 0; i < this.nlist.length; i++) {
		if (this.nlist[i].isMenu())
		return true;
	}

	return false;
};

Navigator.prototype.notMenu = function() {
	return !this.isMenu();
};

Navigator.prototype.domGet = function() {
	if (this.hasOwnProperty('DOM'))
	return this.DOM;

	this.DOM = $('<div>');
	this.nitemWalk((nitem) => this.DOM.append(nitem.domGet().css('display', 'block')));

	return this.DOM;
};
