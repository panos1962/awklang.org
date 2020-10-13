Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "PPL - Producing random data",
			link: "#NVG_top",
		}),

		new Nitem({
			title: 'The PPL library',
			nlist: [
				new Nitem({
					title: 'PPL library inclusion',
					link: "#NVG_ppllib",
				}),
				new Nitem({
					title: "PPL API Functions Reference",
					link: "#NVG_pplAPI",
				}),
				new Nitem({
					title: 'View PPL library source',
					link: "ppllib?child",
					target: 'ppllib',
				}),
				new Nitem({
					title: 'Download PPL library',
					link: "<../awklib/ppl.awk",
				}),
			],
		}),
	));
};
