@include "spawk.awk"

BEGIN {
	spawk_sesami("chat", "xxx", "chat")

	spawk_query("SELECT `login` FROM `user`")

	while (spawk_fetchrow(user, 1))
	count_relations(user[0])
}

function count_relations(user,		count) {
	spawk_query("SELECT `relationship` FROM `relation` WHERE `user` = '" user "'")

	while (spawk_fetchrow(row, 1))
	count[row[0]]++

	print user, count["FRIEND"] + 0, count["BLOCKED"] + 0
}
