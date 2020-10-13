Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "AWK conjuncted software",
			link: "#NVG_top",
		}),

		new Nitem({
			title: "Conjunted software in action!",
			nlist: [
				new Nitem({
					title: "Processing firewall data",
					link: "#NVG_firewall",
				}),
				new Nitem({
					title: "Most visited domains",

					link: "#NVG_iptopvisit",
				}),
				new Nitem({
					title: "Popular domains",
					link: "#NVG_popular",
				}),
				new Nitem({
					title: "Domain/IP counts",
					link: "#NVG_domip",
				}),
			],
		}),

		new Nitem({
			title: "ssconvert spreadsheet converter",
			description: "ssconvert - command line spreadsheet format converter",
			link: "#NVG_ssconvert",
		}),
	));
};
