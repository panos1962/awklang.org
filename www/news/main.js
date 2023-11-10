News = {};

Globals.main = function() {
	Globals.navigatorSet(new Navigator(
		new Nitem({
			title: "November 2023",
			link: "#NVG_202311",
		}),
		new Nitem({
			title: "October 2023",
			link: "#NVG_202310",
		}),
		new Nitem({
			title: "April 2023",
			link: "#NVG_202304",
		}),
		new Nitem({
			title: "September 2022",
			link: "#NVG_202209",
		}),
		new Nitem({
			title: "September 2021",
			link: "#NVG_202109",
		}),
		new Nitem({
			title: "April 2020",
			link: "#NVG_202004",
		}),
		new Nitem({
			title: "March 2018",
			link: "#NVG_201803",
		}),
		new Nitem({
			title: "January 2018",
			link: "#NVG_201801",
		}),
	));

	News.setup();
};

News.setup = function() {
	News.setupDates();

	return News;
};

News.setupDates = function() {
	$('.newsPost').each(function() {
		var date = $(this).attr('date');

		if (!date)
		return true;

		$('<div>').addClass('newsDate').html(date).
		insertBefore($(this).children().first());
	});

	return News;
};
