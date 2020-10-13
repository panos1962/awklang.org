{
	count[$2]++
}

END {
	for (domain in count)
	print domain, count[domain]
}
