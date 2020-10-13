@include "spawk.awk"

BEGIN {
	spawk_sesami("chat", "xxx", "chat")
}

{
	count_relations($1)
}

function count_relations(user,		count) {
	spawk_query("SELECT `relationship` FROM `relation` WHERE `user` LIKE '" user "'")

	while (spawk_fetchrow(row, 1))
	count[row[0]]++

	print user, count["FRIEND"] + 0, count["BLOCKED"] + 0
}
