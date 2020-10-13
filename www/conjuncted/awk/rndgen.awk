@include "ppl.awk"

BEGIN {
	OFS = "\t"

	for (count += 0; count > 0; count--)
	print ppl_login(), ppl_name(), ppl_email()
}
