Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "AWK related software",
			link: "#NVG_top",
		}),

		new Nitem({
			title: "sed stream editor",
			link: "#NVG_sed",
		}),

		new Nitem({
			title: "Perl programming language",
			link: "#NVG_perl",
		}),

		new Nitem({
			title: "AWK versions and implementations",
			nlist: [
				new Nitem({
					link: "#NVG_versions",
				}),
				new Nitem({
					title: "nawk or BWK",
					description: "nawk - also known as BWK, by Brian Kernighan",
					link: "#NVG_nawk",
				}),
				new Nitem({
					title: "gawk, the GNU AWK",
					description: "GNU awk, by Arnold Robbins",
					link: "#NVG_gawk",
				}),
				new Nitem({
					title: "mawk, the fast AWK",
					description: "Very fast AWK implementation, by Mike Brennan",
					link: "#NVG_mawk",
				}),
			],
		}),
	));
};
