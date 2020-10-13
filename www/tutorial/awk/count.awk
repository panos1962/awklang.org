BEGIN		{ print "Hello!"; count2 = 0; count5 = 0 }
(NF % 2) == 0	{ count2++ }
(NF > 5)	{ count5++ }
END		{ print count2, count5; print "Bye..." }
