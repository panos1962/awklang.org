Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "SPAWK - SQL Powered AWK",
			link: "#NVG_top",
		}),

		new Nitem({
			title: "Introduction",
			link: "#NVG_intro",
		}),

		new Nitem({
			title: 'The <i>chat</i> application',
			nlist: [
				new Nitem({
					title: "Introduction",
					link: "#NVG_chat",
				}),
				new Nitem({
					title: 'The schema',
					link: "#NVG_chatSchema",
				}),
				new Nitem({
					title: 'Creating the database',
					link: "#NVG_chatCreate",
				}),
				new Nitem({
					title: 'Populating the database',
					link: "#NVG_chatPopulate",
				}),
				new Nitem({
					title: 'Printing relations',
					link: "#NVG_chatRelprint",
				}),
				new Nitem({
					title: 'Writing application programs',
					link: "#NVG_chatWAP",
				}),
				new Nitem({
					title: 'Program refinement',
					link: "#NVG_chatRefinement",
				}),
				new Nitem({
					title: 'Relation statistics',
					link: "#NVG_chatRelstats",
				}),
				new Nitem({
					title: 'Fixing anomalies',
					link: "#NVG_relfix",
				}),
			],
		}),

		new Nitem({
			title: 'The SPAWK library',
			nlist: [
				new Nitem({
					title: 'SPAWK library inclusion',
					link: "#NVG_spawklib",
				}),
				new Nitem({
					title: "SPAWK API Functions Reference",
					link: "#NVG_spawkAPI",
				}),
				new Nitem({
					title: 'View SPAWK library source',
					link: "spawklib?child",
					target: 'spawklib',
				}),
				new Nitem({
					title: 'Download SPAWK library',
					link: "<../awklib/spawk.awk",
				}),
			],
		}),

		new Nitem({
			title: 'Official SPAWK site',
			link: "http://spawk.info",
		}),
	));
};
