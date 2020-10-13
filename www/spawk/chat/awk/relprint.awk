@include "spawk.awk"

BEGIN {
	spawk_sesami("chat", "xxx", "chat")

	if (all)
	exit(print_all_users())
}

{
	spawk_query("SELECT `login` FROM `user` WHERE `login` LIKE '" $0 "'")

	while (spawk_fetchrow(user, 1))
	print_user(user[0])
}

function print_user(user,		relation, count) {
	spawk_query("SELECT `relationship` FROM `relation` WHERE `related` = '" user "'")

	while (spawk_fetchrow(relation, 1))
	count[relation[0]]++

	print user, count["FRIEND"] + 0, count["BLOCKED"] + 0
}

function print_all_users(		user) {
	spawk_query("SELECT `login` FROM `user`")

	while (spawk_fetchrow(user, 1))
	print_user(user[0])

	return 0
}
