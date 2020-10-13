################################################################################
#
# PPL -- Producing Random Data
#
# PPL functions produce random data in the form of strings, numbers or dates.
# PPL functions may be used in conjunction with SPAWK functions in order to
# populate database tables mainly for testing purposes.
#
# This is not a great library; it's here mainly for tutorial purposes but any
# can use it to produce random data of some basic types.
#
# Because of rand AWK function particularity, data produced from specific AWK
# programs are random, but always the same when the same AWK executable is used.
# This may be useful for testing purposes; if real random data is needed, then
# srand function must be called in the BEGIN section before any PPL function is
# called.
#
# If you want to produce random strings from foreign character sets, e.g el_GR
# UTF-8, then you must create a palette and call ppl_string function with this
# custom palette:
#
#	BEGIN {
#		srand()
#		ppl_palette("αβγδεζηθικλμνοξοπρστυφψω", gr_lower)
#		ppl_palette("ΑΒΓΔΕΖΗΘΙΚΛΜΝΟΞΟΠΡΣΤΥΦΨΩ", gr_upper)
#
#		for (i = ppl_integer(100, 200); i > 0; i--)
#		print ppl_string(10, 20, gr_upper)
#	}
#
# The above script will print a random number (between 100 and 200) of random
# strings of random length (between 10 and 20) constructed of greek uppercase
# letters.
#
################################################################################

BEGIN {
	ppl_palette("abcdefghijklmnopqrstuvwxyz", ppl_lower)
	ppl_palette("ABCDEFGHIJKLMNOPQRSTUVWXYZ", ppl_upper)
}

function ppl_string(min, max, palette,		s) {
	if (max < min)
	max = min

	max = int(rand() * (max + 1 - min)) + min

	for (min = 0; min < max; min++)
	s = s palette[int(rand() * palette[0]) + 1]

	return s;

}

function ppl_integer(min, max) {
	return int(rand() * (max + 1 - min)) + min
}

function ppl_float(min, max) {
	return (rand() * (max - min)) + min
}

function ppl_timestamp(min, max) {
	if (!max)
	max = systime()

	if (min < 0)
	min = max + min

	return ppl_integer(min, max)
}

function ppl_login() {
	return ppl_string(3, 16, ppl_lower)
}

function ppl_domain() {
	return ppl_string(3, 20, ppl_lower) "." ppl_string(2, 3, ppl_lower)
}

function ppl_email() {
	return ppl_login() "@" ppl_domain();
}

function ppl_name() {
	return ppl_string(1, 1, ppl_upper) \
		ppl_string(3, 16, ppl_lower) " " \
		ppl_string(1, 1, ppl_upper) \
		ppl_string(3, 16, ppl_lower)
}

function ppl_palette(spalette, palette) {
	return (palette[0] = split(spalette, palette, ""))
}
