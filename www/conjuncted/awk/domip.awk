BEGIN {
	OFS = "\t"
}

# When new domain arises, print last IP count for the previous domain
# (if exists) and setup environment for the new domain.

$2 != domain {
	# print previous domain's last IP count
	print_ip()

	# set and print current domain
	print (domain = $2)

	# set current IP to the first IP for the new domain
	ip = $1
}

# When new IP arises, print the previous IP count and reset IP and counter.

$1 != ip {
	# print previous IP count (if exists)
	print_ip()

	# reset current IP and counter.
	ip = $1
	ipcount = 0
}

# For lines reached this point, just increase IP counter.

{
	ipcount++
}

END {
	print_ip()
}

function print_ip() {
	if (ip)
	print ip, ipcount
}
