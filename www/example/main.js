Poker = {};

Globals.main = function() {
	Poker.playersDOM = $('input[name=pokerPlayers]').on('change', Poker.commandRefresh);
	Poker.printerDOM = $('input[name=pokerPrinter]').on('change', Poker.commandRefresh);
	Poker.commandDOM = $('input[name=pokerCommand]');
	Poker.commandRefresh();

	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "AWK by example",
			link: "#NVG_top",
		}),

		new Nitem({
			title: "AWK scripting",
			link: '#NVG_awkscript',
		}),

		new Nitem({
			title: "AWK in shell scripting",
			link: "#NVG_shell",
		}),

		new Nitem({
			title: "Shell scripts",
			description: "Use of AWK in shell scripts",
			nlist: [
				new Nitem({
					title: "procrustes",
					link: "#NVG_procrustes",
				}),
				new Nitem({
					title: "cstats",
					link: "#NVG_cstats",
				}),
			],
		}),

		new Nitem({
			title: "More AWK examples",
			description: "More than 150 examples of AWK programs",
			link: "https://github.com/learnbyexample/Command-line-text-processing/blob/master/gnu_awk.md",
		}),

		new Nitem({
			title: "Playing cards",
			description: "Using AWK for simulating playing cards issues",
			link: '#NVG_cards',
		}),
	));
};

Poker.commandRefresh = function() {
	Poker.commandDOM.val('awk -v n_players=' + Poker.playersDOM.val() +
		' -v html=' + Poker.printerDOM.filter(':checked').val() + ' -f poker.awk')
};
