@include "spawk.awk"
@include "ppl.awk"

BEGIN {
	srand(PROCINFO["pid"])
	spawk_sesami("chat", "xxx", "chat")

	# "ucount" is the number of users to be inserted.
	# "ucount" may be defined in the command line via -v option

	ucount += 0

	# After inserting users in the database, every user
	# will obtain a random number of relations with other
	# users. The number of relations to be inserted for
	# each user is random but less than "maxrel" which
	# can be defined in the command line via -v oprion.

	maxrel += 0

	relation_populate(user_populate())
}

function user_populate(			i, user) {
	nuser = 0
	while (ucount-- > 0) {
		user["login"] = ppl_login()
		user["registration"] = ppl_timestamp()
		user["name"] = ppl_name()
		user["email"] = ppl_email()
		user["password"] = ppl_string(6, 20, ppl_lower)

		if (spawk_submit("INSERT INTO `user` (`login`, `registration`, " \
			"`name`, `email`, `password`) VALUES ('" user["login"] "', " \
			"FROM_UNIXTIME(" user["registration"] "), '" user["name"] "', " \
			"'" user["email"] "', SHA1('" user["password"] "')) " \
			"ON DUPLICATE KEY UPDATE `name` = VALUES(`name`)") != 1)
			continue	# duplicates return 2, not 1

		ulist[++nuser] = user["login"]
	}

	return nuser
}

function relation_populate(nuser,		i, nrel, query) {
	for (i = 1; i <= nuser; i++) {
		nrel = ppl_integer(1, maxrel)

		query = "INSERT INTO `relation` (`user`, `related`, `relationship`) VALUES "

		while (nrel-- > 1)
		query = query relation_row(ulist[i]) ","

		query = query relation_row(ulist[i]) \
		" ON DUPLICATE KEY UPDATE `relationship` = VALUES(`relationship`)"
		spawk_submit(query)
	}
}

function relation_row(user,			related) {
	while((related = ulist[ppl_integer(1, nuser)]) == user)
	;

	return "('" user "', '" ulist[ppl_integer(1, nuser)] "', '" \
		(ppl_integer(0, 1) ? "FRIEND" : "BLOCKED") "')"
}
