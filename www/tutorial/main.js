Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "AWK tutorial",
			link: '#NVG_top',
		}),

		new Nitem({
			title: "AWK syntax",
			link: '#NVG_awksyntax',
		}),

		new Nitem({
			title: "Counting even numbers",
			link: '#NVG_even',
		}),

		new Nitem({
			title: "More counting…",
			link: '#NVG_morecount',
		}),

		new Nitem({
			title: "Counting better",
			link: '#NVG_countbetter',
		}),

		new Nitem({
			title: "Regular Expressions",
			link: '#NVG_regexp',
		}),

		new Nitem({
			title: "Arrays",
			link: '#NVG_arrays',
		}),

		new Nitem({
			title: "Functions",
			nlist: [
				new Nitem({
					link: '#NVG_functions',
				}),
				new Nitem({
					title: "Built-in functions",
					link: '#NVG_builtinFunctions',
				}),
				new Nitem({
					title: "User defined functions",
					link: '#NVG_userFunctions',
				}),
				new Nitem({
					title: "Local variables",
					link: '#NVG_localVariables',
				}),
			],
		}),
	));
};
