BEGIN {
	sep = "print"
}

{
	print "(($0 % " $0 ") == 0) { count" $0 "++ }"
	cntcmd = cntcmd sep " count" $0
	sep = ","
}

END {
	print "END { " cntcmd " }"
}
