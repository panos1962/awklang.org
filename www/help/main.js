Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "It's not about AWK",
			link: "#NVG_top",
		}),

		new Nitem({
			title: "Header",
			nlist: [
				new Nitem({
					link: "#NVG_header",
				}),
				new Nitem({
					title: "Home tab",
					link: "#NVG_homeTab",
				}),
				new Nitem({
					title: "Skin tab",
					link: "#NVG_skinTab",
				}),
				new Nitem({
					title: "Help tab",
					link: "#NVG_helpTab",
				}),
				new Nitem({
					title: "FAQ tab",
					link: "#NVG_faqTab",
				}),
				new Nitem({
					title: "About tab",
					link: "#NVG_aboutTab",
				}),
				new Nitem({
					title: "Close tab",
					link: "#NVG_closeTab",
				}),
			],
		}),

		new Nitem({
			title: "Navigator",
			link: "#NVG_navigator",
		}),

		new Nitem({
			title: "Main area",
			link: "#NVG_main",
		}),

		new Nitem({
			title: "Footer",
			link: "#NVG_footer",
		}),
	));

	Globals.mainDOM.
	on('mouseenter', '.spot', function(e) {
		$($(this).attr('spot')).css('background-color', 'red');
	}).
	on('mouseleave', '.spot', function(e) {
		$($(this).attr('spot')).css('background-color', '');
	});
	
};
