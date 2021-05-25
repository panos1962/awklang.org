window.name = 'base';

Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "AWK in brief",
			nlist: [
				new Nitem({
					title: "Introduction",
					link: "#NVG_intro",
				}),
				new Nitem({
					title: "History",
					link: "#NVG_history",
				}),
				new Nitem({
					title: "Basics",
					nlist: [
						new Nitem({
							link: "#NVG_basics",
						}),
						new Nitem({
							title: "Lines, Records and Fields",
							link: "#NVG_lrf",
						}),
						new Nitem({
							title: "Regular Expressions",
							link: "#NVG_regexp",
						}),
						new Nitem({
							title: "I/O redirection",
							link: "#NVG_io",
						}),
					],
				}),
				new Nitem({
					title: "Conclusion",
					link: "#NVG_conclusion",
				}),
			],
		}),

		new Nitem({
			title: "Documentation",
			description: "Various AWK documents",
			nlist: [
				new Nitem({
					title: "User's guide",
					description: "The GNU Awk User's Guide",
					link: "https://www.gnu.org/software/gawk/manual/gawk.html",
				}),

				new Nitem({
					title: "Online manual",
					description: "Gawk: Effective AWK Programming",
					link: "https://www.gnu.org/software/gawk/manual/",
				}),

				new Nitem({
					title: "AWK Books",
					nlist: [
						new Nitem({
							title: "Effective awk Programming, 4th Edition",
							link: "http://shop.oreilly.com/product/0636920033820.do",
						}),
						new Nitem({
							title: "sed & awk, 2nd Edition",
							link: "http://shop.oreilly.com/product/9781565922259.do",
						}),
						new Nitem({
							title: "Mastering Regular Expressions, 3rd Edition",
							link: "http://shop.oreilly.com/product/9780596528126.do",
						}),
						new Nitem({
							title: "The AWK Programming Language",
							link: "https://www.amazon.com/dp/020107981X",
						}),
						new Nitem({
							title: "More Programming Pearls: Confessions of a Coder, 1st Edition",
							link: "https://www.amazon.com/dp/0201118890",
						}),
					]
				}),

				new Nitem({
					title: "Learn AWK by example",
					link: "https://github.com/learnbyexample/Command-line-text-processing/blob/master/gnu_awk.md",
				}),

				new Nitem({
					title: "RosettaCode AWK wiki page",
					link: "http://rosettacode.org/wiki/Category:AWK",
				}),

				new Nitem({
					title: "Cheat sheets",
					nlist: [
						new Nitem({
							title: "Peteris Krumins, 2007",
							link: "asset/krumnisCheatSheet.pdf",
						}),
						new Nitem({
							title: "V. Ledos, 2010",
							link: "asset/ledosCheatSheet.pdf",
						}),
					]
				}),
			],
		}),

		new Nitem({
			title: "Tutorials",
			nlist: [
				new Nitem({
					title: "Local AWK tutorial",
					description: "Local AWK tutorial page",
					link: "tutorial?child",
				}),

				new Nitem({
					title: "TutorialsPoint",
					description: "AWK tutorial at tutotialspoint.org",
					link: "https://www.tutorialspoint.com/awk",
				}),

				new Nitem({
					title: "F’Awk Yeah!",
					description: "Advanced sed and awk Usage",
					link: "https://posts.specterops.io/fawk-yeah-advanced-sed-and-awk-usage-parsing-for-pentesters-3-e5727e11a8ad",
				}),

				new Nitem({
					title: "AWK@Grymoire",
					description: "Grymoire AWK tutorial",
					link: "http://www.grymoire.com/Unix/Awk.html",
				}),

				new Nitem({
					title: "RE@Grymoire",
					description: "Grymoire Regular Expressions tutorial",
					link: "http://www.grymoire.com/Unix/Regular.html",
				}),

				new Nitem({
					title: "MySQL-RE@guru99",
					description: "MySQL REGEXP by Chirag Sharma",
					link: "https://www.guru99.com/regular-expressions.html",
				}),
			],
		}),

		new Nitem({
			title: "AWK by example",
			link: "example?child",
		}),

		new Nitem({
			title: "AWK in action!",
			description: "Collected AWK software",
			link: "action?nvgexp&child",
			target: 'action',
		}),

		new Nitem({
			title: "Groups & Fora",
			nlist: [
				new Nitem({
					title: "comp.lang.awk",
					desription: "comp.lang.awk Google group",
					link: "https://groups.google.com/forum/#!forum/comp.lang.awk",
				}),
				new Nitem({
					title: "AWK at reddit",
					link: "https://www.reddit.com/r/awk",
				}),
			],
		}),

		new Nitem({
			title: "Miscellaneous",
			description: "Miscellaneous topics",
			nlist: [
				new Nitem({
					title: "Related software",
					description: "AWK like software",
					link: "related?child",
					
				}),
				new Nitem({
					title: "Conjuncted software",
					description: "Software to be used in conjunction with AWK",
					link: "conjuncted?child",
				}),
				new Nitem({
					title: "Useful AWK libraries by Daniel Mills",
					link: "https://github.com/e36freak/awk-libs",
				}),
				new Nitem({
					title: "More AWK libraries…",
					nlist: [
						new Nitem({
							title: "awkenough",
							description: "Utility functions, AWK wrapper, useful scripts etc",
							link: "https://github.com/dubiousjim/awkenough",
						}),
						new Nitem({
							title: "takubo",
							description: "Yet another AWK library",
							link: "https://github.com/takubo/awk_lib",
						}),
						new Nitem({
							title: "awk++",
							description: "Various helper functions for awk",
							link: "https://github.com/xfix/awk-plus-plus",
						}),
						new Nitem({
							title: "runawk",
							description: "Small wrapper for AWK interpreter that impements modules system and helps one to write the standalone AWK programs",
							link: "https://github.com/cheusov/runawk",
						}),
						new Nitem({
							title: "wcwidth",
							description: "An implementation of wcwidth/wcswidth in pure AWK",
							link: "https://github.com/ericpruitt/wcwidth.awk",
						}),
					],
				}),
				new Nitem({
					title: "TCP/IP Internetworking",
					description: "Gawkinet: TCP/IP Internetworking with Gawk",
					link: "https://www.gnu.org/software/gawk/manual/gawkinet",
				}),
				new Nitem({
					title: "PPL - Procucing random data",
					link: "ppl?child&nvgexp",
				}),
				new Nitem({
					title: "SPAWK - SQL Powered AWK",
					link: "spawk?child",
				}),
				new Nitem({
					title: "Awka: C Converter",
					description: "Awka: AWK to C Conversion Tool",
					link: "http://awka.sourceforge.net/index.html",
				}),
				new Nitem({
					title: "Archives",
					nlist: [
						new Nitem({
							title: "awk.info (INTERNET ARCHIVE)",
							description: "AWK community portal (archive)",
							link: "https://web.archive.org/web/20130517223341/http://awk.info/",
						}),
						new Nitem({
							title: "awk.info (repository)",
							link: "https://github.com/timm/lawker",
						}),
						new Nitem({
							title: "comp.lang.awk FAQ (out-of-date but useful FAQ archive)",
							description: "Useful awk FAQ collection by Russell Schulz (2002)",
							link: "http://www.faqs.org/faqs/computer-lang/awk/faq/",
						}),
					],
				}),
			],
		}),

		new Nitem({
			title: "Wikipedia",
			description: "AWK on the Wikipedia",
			link: "https://en.wikipedia.org/wiki/AWK",
		}),

		new Nitem({
			title: "40 years of awk",
			description: "AWK: from 1980 to 2020",
			link: "https://www.fosslife.org/awk-power-and-promise-40-year-old-language",
		}),

		new Nitem({
			title: "The AWK dragon",
			description: "AWK dragon in action!",
			link: "dragon?child",
			target: "dragon",
		}),
	));
};
