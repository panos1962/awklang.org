@include "spawk.awk"

BEGIN {
	spawk_sesami("chat", "xxx", "chat")
}

{
	delete count
	spawk_query("SELECT `relationship` FROM `relation` WHERE `related` LIKE '" $0 "'")

	while (spawk_fetchrow(relation, 1))
	count[relation[0]]++

	print $0, count["FRIEND"] + 0, count["BLOCKED"] + 0
}
