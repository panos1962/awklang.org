@include "spawk.awk"

BEGIN {
	OFS = "\t"

	# Flag "all" controls the overall program's behavior on
	# how to specify users for counting their popularity.
	# If "all" has a non-zero value (default) then the program
	# will read login names, full names or emails from standard input.
	# In that case, we can specify the key column by setting "ucol"
	# variable (default 1).

	all += 0

	# Check the key (login/email/name) column. If not specified,
	# then the first column is assumed as the key column.

	ucol += 0

	if (ucol <= 0)
	ucol = 1

	# We have the option to specify users by keys other than the
	# user login name. To do so we must specify the key using the
	# "key" variable, e.g. awk -v key="email" will use user email
	# instead of user login name.

	valid_keys["login"]
	valid_keys["email"]
	valid_keys["name"]

	if (!key)
	key = "login"

	else if (!(key in valid_keys))
	fatal(key ": invalid key")

	# By default the login name and the key will be printed.
	# However, we can specify which columns to print by setting
	# the "projection" variable to a list of any user column names.
	# The list may be separated by any special character, e.g. comma,
	# colon, space etc.

	if (!projection)
	projection = (key == "login" ? "login" : "login," key)

	nprjcols = split(projection, prjcols, "[^a-zA-Z0-9_]+")

	# The user select SQL clause is fixed, so we store this in
	# the "uselect" variable, as it will be used more than once
	# in the script

	uselect = "SELECT `login`, `name`, `email` FROM `user`"

	spawk_sesami("chat", "xxx", "chat")

	if (all)
	exit(process_all())
}

NF < ucol {
	next
}

{
	spawk_query(uselect " WHERE `" key "` LIKE '" $ucol "'")

	while (spawk_fetchrow(user))
	process_user(user)
}

function process_user(user,		relation, count, i) {
	spawk_query("SELECT `relationship` FROM `relation` " \
		"WHERE `related` = '" user[1] "'")

	while (spawk_fetchrow(relation, 1))
	count[relation[0]]++

	user["login"] = user[1]
	user["name"] = user[2]
	user["email"] = user[3]

	for (i = 1; i <= nprjcols; i++)
	printf user[prjcols[i]] OFS

	print count["FRIEND"] + 0, count["BLOCKED"] + 0
}

function process_all(			user) {
	spawk_query(uselect)

	while (spawk_fetchrow(user))
	process_user(user)

	exit(0)
}

function fatal(msg) {
	print msg >"/dev/stderr"
	exit(2)
}
