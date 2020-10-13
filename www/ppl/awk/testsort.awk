@include "ppl.awk"

BEGIN {
	OFS = "\t"

	srand()
	for (count += 0; count > 0; count--)
	print ppl_integer(10000000, 99999999), ppl_string(40, 60, ppl_lower)
}
