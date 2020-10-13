{
	for (i = 1; i <= NF; i++)
	count[length($i)]++
}

END {
	for (len in count)
	print len, count[len]
}
