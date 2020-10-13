#!/usr/bin/env bash

usage() {
	echo "usage: ${progname} [-m min] [-M max] [-c column] [-t separator] [files...]" >&2
	exit 1
}

progname=$(basename $0)
errs=
min=
max=
col=1
sep=

while getopts "m:M:c:t:" opt
do
	case "${opt}" in
	m)
		min="${OPTARG}"
		;;
	M)
		max="${OPTARG}"
		;;
	c)
		col="${OPTARG}"
		;;
	t)
		sep="${OPTARG}"
		;;
	\?)
		errs="yes"
		;;
	esac
done

[ -n "${errs}" ] && usage

shift $(expr ${OPTIND} - 1)

exec awk -v min="${min}" -v max="${max}" -v col="${col}" -v sep="${sep}" 'BEGIN {
	if (sep != "") {
		FS = sep
	}
}
NF < col { next }
(min != "") && ($col < min) { next }
(max != "") && ($col > max) { next }
{ print }' $*
