@include "ppl.awk"

BEGIN {
	srand()
	for (i = ppl_integer(100, 200); i > 0; i--)
	print ppl_string(10, 20, ppl_lower)
}
