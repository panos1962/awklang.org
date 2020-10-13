################################################################################
#
# SPAWK -- SQL Powered AWK
#
################################################################################
#
# Copyright 2017, by Panos I. Papadopoulos
# All Rights Reserved
#
################################################################################
#
# SPAWK is an AWK library to be used for MySQL/MariaDB database access.
# The library makes use of the "coprocessing" AWK ability; AWK coprocessing is
# about two-way communication between AWK and children processes.
#
# SPAWK maintains a stack of database clients, where clients of each level
# handle sibling SQL queries. Whenever a new SQL query is being submitted, a
# new database client is pushed in the stack and executes the submitted query.
# The client will be popped off the stack after all of the query result rows
# have been returned to the caller; the caller must request the result rows from
# the client for the client to be released and popped off the stack. If another
# query is being submitted while the current client has not yet been released,
# a new client will be pushed in the stack and so on. Clients popped off the
# stack do not exit but stay active while the parent AWK process is still alive.
#
# SPAWK library defines a small number of global objects, namely functions and
# variables, e.g. "spawk_query", "spawk_fetchrow", "spawk_dlm", "spawk_level",
# etc. However, it's a fact that all of the SPAWK global object names begin with
# "spawk_".
#
# In order to use the SPAWK library, either you can include the library in the
# command line using -f AWK option, or you can include the library literally
# using "@include" directive in the beginning of AWK scripts. Both of these two
# methods may involve the AWKPATH environment variable.
#
################################################################################
#
# SPAWK API
# =========
#
# The user may communicate with the database via a small number of SPAWK library
# functions. This subset of the SPAWK library functions form the so called
# "SPAWK API".
#
# Warning!
# Although, it's not a good practice to submit direct calls of non API SPAWK
# functions, nor to access or modify internal SPAWK objects of any kind
# (variables etc), sometimes it's inevitable to do so.
#
################################################################################
#
# SPAWK API Global Variables
# ==========================
#
# SPAWK uses a small number of global variables. Some of them are intrinsic,
# others are more conceivable.
#
# spawk_logfile
# ..............................................................................
# Specify a log file in the command line in order to log the submitted queries
# and the data returned from the clients.
#
# Warning!
# Log data will be appended to the specified logfile. If the file exists it will
# be modified!
#
# spawk_dlm
# ..............................................................................
# The internal delimiter used to separate one query from the next. You may not
# change the delimiter, or else SPAWK will fail. By default the delimiter is the
# Control-A character (ascii 1).
#
# Warning!
# Do not submit DELIMITER commands for SPAWK execution.
#
# spawk_eod
# ..............................................................................
# Means End-Of-Data. Used for signaling the caller that a client has no more
# result rows available. By default the EOD character is Control-D (ascii 4).
#
# spawk_level
# ..............................................................................
# SPAWK maintains the current stack level; stack_level 0 means that there are no
# available result rows to be returned, 1 means that the current client has more
# result rows available, 2 means that the top two clients  have more result rows
# available and so on.
#
################################################################################
#
# SPAWK API Functions' Reference Manual
# =====================================
#
# spawk_sesami(user, password, database)
# ..............................................................................
# Call "spawk_sesami" once in the BEGIN section of the application SPAWK
# program. The parameters will be used for database clients' authentication.
# Sometimes it's unavoidable to call "spawk_sesami" more than once, e.g. one may
# have to submit DDL queries as the database root user and then submit DML
# queries as a normal database user.
#
# spawk_query(query)
# ..............................................................................
# To submit an DQL query (select), just call "spawk_query". After such a call,
# the results must be retrieved by calling other API functions. Submitting other
# queries while results from previously submitted queries have not yet been
# retrieved, causes a new database client to be pushed on the stack.
#
# spawk_fetchrow(row, nosplit)
# ..............................................................................
# After query submition, "spawk_fetchrow" repetitive calls return one row at a
# time. Each row is returned in "row[0]", while each column value is returned in
# "row[1]", "row[2]", "row[3]" etc. Passing of "nosplit" true value, e.g. 1,
# causes "spawk_fetchrow" not to split fetched row to columns. The only reason
# why not to split the result row in columns is efficiency.
#
# Returns 0 if no rows selected, else returns the number of columns, or 1 when
# in no-split mode.
#
# spawk_fetchrest()
# ..............................................................................
# Call of "spawk_fetchrest" function causes any unreaded rows to be read, thus
# causing the current database client to be popped off the stack. Rows readed
# are lost.
#
# spawk_fetchone(row, nosplit)
# ..............................................................................
# Fetch only the first result row and skip the rest of the query results.
# Everything else is just like "spawk_fetchrow".
#
# spawk_submit(query, ret)
# ..............................................................................
# Submit an insert/update/delete or DDL query for execution. For non DDL queries
# "ret[1]" is set to affected rows count, while "row[2]" is set to the last
# inserted ID.
#
# Returns -1 on failure, affected rows count on success.
#
################################################################################

function spawk_sesami(user, password, database,		version, n, v) {
	spawk_reset()
	spawk_user = user

	if (password)
	spawk_password = password

	if (database)
	spawk_database = database

	if (!spawk_password)
	return

	# Beginning with version 4.2, gawk does update its own environment when
	# ENVIRON is changed, thus changing the environment seen by programs
	# that it creates.

	version = PROCINFO["version"]

	n = split(version, v, ".")

	# It's safer to put database user's password in the environment than
	# expose it in the database client's command line.

	if ((n >= 2) && ((v[1] + 0) >= 4) && ((v[2] + 0) >= 2))
	ENVIRON["MYSQL_PWD"] = spawk_password

	# The password will be exposed because it will be part of the database
	# client's command line.

	else
	spawk_password_cli = " --password='" spawk_password "'"
}

# Function "spawk_query" accepts a select SQL query and submits that query to a
# new client for execution. The new client may have started earlier from another
# SPAWK call. Selected data must be retrieved using appropriate SPAWK functions,
# e.g. "spawk_fetchrow", "spawk_fetchone" etc.

function spawk_query(query) {
	spawk_logwrite("SQL/DQL: " query)
	print query spawk_dlm "SELECT '" spawk_eod "'" spawk_dlm |& spawk_push()
}

# Function "spawk_fetchrow" fetches the next row from the result set of rows
# returned from a previously submitted query. Row is returned in the array
# passed as first parameter to the function. Indexed 0 is the row as a whole,
# while indexed 1 is the first column's value, indexed 2 is the second column's
# value etc.
#
# The number of columns is returned to the caller, until the end of data is
# reached; on end of data 0 is returned.
#
# A second parameter with non zero value may be passed in order not to split the
# columns (for efficiency reasons). In such case only 0 indexed item is returned
# in the array passed, and the returned value is always 1, except on end of
# data.

function spawk_fetchrow(row, nosplit,			data, n) {
	delete row
	data = spawk_fetchdata()
	spawk_logwrite(data)

	if (data == spawk_eod)
	return 0

	if (nosplit) {
		row[0] = data
		return 1
	}

	n = split(data, row, "\t")
	row[0] = data

	# If "data" is an empty string split returns 0, though in this
	# case we must return 1.

	return n ? n : 1
}

# Function "spawk_fetchrest" may be called to "eat" remaining results.

function spawk_fetchrest(		count) {
	for (count = 0; spawk_fetchdata() != spawk_eod;)
	count++;

	spawk_logwrite("\t[[ " count " rows skipped ]]")
}

# Function "spawk_fetchone" is like "spawk_fetchrow", but remaining rows of the
# result set are sent to trash.

function spawk_fetchone(row, nosplit,			n) {
	if (n = spawk_fetchrow(row, nosplit))
	spawk_fetchrest()

	return n
}

# Whenever an insert/update/delete or DDL query is to be submitted,
# "spawk_submit" function must be used instead of "spawk_query". Affected rows
# and last inserted ID are returned in an array passed as second parameter.
#
# On failure -1 is returned to the caller, otherwise the number of affected rows
# is returned.

function spawk_submit(query, ret,		logfile) {
	spawk_logwrite("DML/DDL: " query)
	logfile = spawk_logfile
	spawk_logfile = ""

	spawk_query(query spawk_dlm "SELECT ROW_COUNT(), LAST_INSERT_ID()")
	spawk_logfile = logfile

	spawk_fetchone(ret)

	ret[1] += 0
	ret[2] += 0

	return ret[1]
}

function spawk_error(msg) {
	print "SPAWK: " msg >"/dev/stderr"
}

function spawk_fatal(msg, err) {
	spawk_error(msg)
	exit(err + 0)
}

################################################################################

BEGIN {
	spawk_dlm = spawk_debug ? ";" : "\001"		# command delimiter
	spawk_eod = spawk_debug ? "_EOD_" : "\004"	# end of data
	spawk_level = 0
}

function spawk_push(			client) {
	if (spawk_level > 99)
	spawk_fatal("too many clients pushed")

	spawk_data[++spawk_level] = 1
	client = spawk_client()

	if (client)
	return client

	spawk_logwrite("Creating SPAWK database client " spawk_level)

	client = "SPAWKLEVEL=" spawk_level " mysql"
	client = client " --no-defaults"
	client = client " --batch"
	client = client " --raw"
	client = client " --force"
	client = client " --silent"
	client = client " --no-beep"
	client = client " --skip-column-names"
	client = client " --unbuffered"
	client = client " --delimiter " spawk_dlm
	client = client " --user=" spawk_user
	client = client spawk_password_cli

	if (spawk_database)
	client = client " --database=" spawk_database

	spawk_stack[spawk_level] = client
	return client
}

function spawk_pop() {
	spawk_data[spawk_level--] = 0
}

function spawk_client() {
	if (spawk_level <= 0)
	spawk_fatal("no active database client")

	return spawk_stack[spawk_level]
}

function spawk_reset(			i) {
	for (i in spawk_stack)
	close(spawk_stack[i])

	delete spawk_stack
	delete spawk_data
}

function spawk_fetchdata(			ret, data) {
	if (!spawk_data[spawk_level])
	spawk_fatal("no active client")

	ret = (spawk_client() |& getline data)

	if (ret == -1)
	spawk_fatal("I/O error")

	if (ret == 0)
	spawk_fatal("data drain")

	if (data == spawk_eod)
	spawk_pop()

	return data
}

function spawk_logwrite(x,			i) {
	if (!spawk_logfile)
	return

	for (i = 0; i < spawk_level; i++)
	printf "\t" >>spawk_logfile

	print x >>spawk_logfile
}
