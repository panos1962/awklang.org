#!/usr/bin/env bash

usage() {
	echo "usage: ${progname}" >&2
	exit 1
}

progname=$(basename $0)
errs=

while getopts "" opt
do
	case "${opt}" in
	\?)
		errs="yes"
		;;
	esac
done

[ -n "${errs}" ] && usage

shift $(expr ${OPTIND} - 1)

exec gawk 'BEGIN {
	FS = ""
}

{
	for (i = 1; i <= NF; i++) {
		count[$i]++
	}
}

END {
	n = asorti(count, cidx)
	for (i = 1; i <= n; i++) {
		c = cidx[i]
		printf("%s: %d\n", c, count[c])
	}
}' $*
