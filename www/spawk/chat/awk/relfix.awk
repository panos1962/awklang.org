@include "spawk.awk"

BEGIN {
	spawk_sesami("chat", "xxx", "chat")

	concordant = 0
	contradictive = 0

	# Non-zero "introdel" flag value causes self relationships to be delete.
	# SPAWK returns the number of affected rows on DML queries.

	introverted = introdel ? spawk_submit("DELETE FROM `relation` WHERE `user` = `related`") : 0

	spawk_query("SELECT `user`, `related`, `relationship` FROM `relation`")

	while (spawk_fetchrow(relation))
	process_relation(relation)

	# concordant and contradictive relations have been counted twice,
	# once for the "user" and once for the "related".

	print concordant / 2, contradictive / 2, introverted
}

function process_relation(relation,			reverse) {
	# skip self relations

	if (relation[1] == relation[2])
	return introverted++

	# select the reverse relation (if exists)

	spawk_query("SELECT `relationship` FROM `relation` " \
		"WHERE `user` = '" relation[2] "' AND `related` = '" relation[1] "'")

	# if the reverse relation does not exist, then do nothing

	if (!spawk_fetchone(reverse, 1))
	return

	# check for the same or different kind of the reverse relation

	if (reverse[0] == relation[3])
	concordant++

	else
	contradictive++
}
