$2 != domain {
	domain_stats()
	domain = $2
}

{
	visit[$1]++
}

END {
	domain_stats()
}

function domain_stats(		ip, count) {
	if (!domain)
	return

	for (ip in visit)
	count++

	delete visit

	if (count > min_count)
	print domain, count
}
