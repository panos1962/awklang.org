{
	sep = ""

	for (i = 0; i <= NF; i++) {
		printf sep mean_calc($i)
		sep = " "
	}
}

function mean_calc(l) {
	n = split(l, a, ",")

	tot = 0

	for (i in a)
	tot += a[i]

	return n ? tot / n : "NaN"
}
