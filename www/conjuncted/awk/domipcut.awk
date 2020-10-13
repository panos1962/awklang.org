# Domain lines contain just the domain name, so keep it for
# future use.

NF == 1 {
	domain = $1
	next
}

# Whenever the visit count is greater/equal than the given minimum,
# print the line.

$2 >= mincount {
	# If current domain have not printed yet, print it now and
	# wipe it out.

	if (domain) {
		print domain
		domain = ""
	}

	print $0
}
