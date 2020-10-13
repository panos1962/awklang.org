BEGIN {
	# Parameter check shall be defined in the command line
	# via -v option, as a comma separated list of numbers.
	# Split the comma separated list and put the check
	# numbers in an array.

	n = split(check, a, ",")

	# If no numbers have been specified, then do nothing.

	if (n == 0)
	exit(0)

	# Create an associative array indexed by the given numbers.

	for (i in a)
	counter[a[i]] = 0
}

{
	# For every input number, iterate all the check numbers and
	# check the remainders; if the input number is a multiple of
	# a check number, then increase the corresponding counter.

	for (i in counter) {
		if (($0 % i) == 0)
		counter[i]++
	}
}

END {
	# Input has been read, so print the counters for every one
	# of the check numbers given.

	for (i in counter)
	print i, counter[i]
}
