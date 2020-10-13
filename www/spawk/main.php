<a name="NVG_top"></a>
<h1>SPAWK &mdash; SQL Powered AWK</h1>

<img src="<?php print HTML::url("image/spawk/spawk256.png"); ?>">

<a name="NVG_intro"></a>
<h2>Introduction</h2>

<p>
Based on two-way pipes, known as <i>coprocesses</i> in <code>gawk</code>'s jargon,
SPAWK forms a tiny library of AWK functions that make possible for AWK to communicate
with MySQL/MariaDB databases.
Using SPAWK, one can submit SQL queries to MySQL/MariaDB database servers from within
AWK scripts and process the result rows in the same AWK script.
DDL/DML SQL queries may be submitted as well.
Let's take a look at what a SPAWK script may look like:
</p>

<p>
<pre><?php require "chat/awk/relcount.awk" ?></pre>
</p>

<p>
The above SPAWK script includes the SPAWK library at first.
In the <code>BEGIN</code> section, the database credentials are being set.
For every input line, the first field is considered to be a user login name
for which the program will select the related users and count the selected
relations based on the relation kind.
Then, the user login name along with the <i>friends</i> and <i>blocked</i>
counts for this user will be printed.
</p>

<p class="warning warningYellow">
<strong>Notice!</strong>
<br>
This is an introductory, light version of SPAWK.
While you can download and run this version of SPAWK in your local system,
it's strongly recommended not to do so.
Just read this page as you may read any article in your favourite Linux magazine.
If you find SPAWK interesting, visit the official SPAWK site at
<a href="http://spawk.info" target="spawkinfo">spawk.info</a>
and download the real SPAWK following the instructions there.
</p>

<p>
A similar SPAWK script can be used to print <i>friends</i> and <i>blocked</i>
counts for ALL registered users:
</p>

<p>
<pre><?php require "chat/awk/relcountall.awk" ?></pre>
</p>

<a name="NVG_chat"></a>
<h2>The <i>chat</i> application</h2>

<p>
Assume a <i>chat</i> application based on a relational database holding <i>user</i>
accounts and <i>relations</i> between users.
Every user may create relations of type "FRIEND" or "BLOCKED" with other registered
users of the application.
<a name="NVG_chatSchema"></a>
A simple database schema for the <i>chat</i> database would be:
</p>

<p>
<pre><?php require "chat/database/schema.sql" ?></pre>
</p>

<p>
<a href="chat/database/schema.sql" download="schema.sql">Download</a>
</p>

<a name="NVG_chatCreate"></a>
<h3>Creating the database</h3>

<p>
Assuming that the above database schema is stored in <code>schema.sql</code> file,
run the following command as the <i>root</i> user of the database to (re)create the database:
</p>

<p class="warning warningRed">
<strong>Warning!!!</strong>
<br>
The following command will drop any already existing <i>chat</i> database
in your system; all relevant data will be erased!
If there exists a database user named <i>chat</i>, this user will also
be dropped!
</p>

<p>
<pre>mysql -u root -p &lt;schema.sql</pre>
</p>

<a name="NVG_chatPopulate"></a>
<h3>Populating the database</h3>

<p>
After creating the <i>chat</i> database and the <i>chat</i> generic database user
granted with DQL/DML database access, we want to populate the database with test data
in order to develop programs and run various test suits for the <i>chat</i> application.
We'll use SPAWK and PPL AWK libraries for populating the database with random data:
</p>

<p>
<pre>gawk -v ucount=1000 -v maxrel=10 -f populate.awk</pre>
</p>

<p>
After running for a couple of seconds, your <i>chat</i> database will be populated
with 1000 <i>user</i> rows, and about 50000 <i>relation</i> rows.
There exists a possibility of unique constraints violations on <i>user</i> insert
operations; in such a case your database may contain less than 1000 <i>user</i> rows.
We can avoid duplications writing some extra SPAWK code, but it's not worthwhile to do so.
</p>

<p>
It's time to take a look at the <code>populate.awk</code> SPAWK script:
</p>

<p>
<pre><?php require "chat/awk/populate.awk" ?></pre>
</p>

<p>
<a href="chat/awk/populate.awk" download="populate.awk">Download</a>
</p>

<a name="NVG_chatRelprint"></a>
<h3>Printing relations</h3>

<p>
After populating the <i>chat</i> database, it's time to make productive use of SPAWK.
Our first program will print the counts of relations declared for specified users.
</p>

<p>
<pre><?php require "chat/awk/relpr.awk" ?></pre>
</p>

<p>
<a href="chat/awk/relpr.awk" download="relpr.awk">Download</a>
</p>

<p>
Run the above script and type in some user login names from your keyboard.
For each name you'll get the results, that is the user login name, the count of
users declared the specified user as "FRIEND", and the count of users
declared the specified user as "BLOCKED":
</p>

<p>
<pre>
gawk -f relpr.awk
<div class="input">asdasd</div>
<div class="output">asdasd 9 12</div>
<div class="input">sdfhww</div>
<div class="output">sdfhww 3 8</div>
<div class="input">gfuuzsy</div>
<div class="output">gfuuzsy 19 5</div>
...
<div class="input">sdfuirui</div>
<div class="output">sdfuirui 2 6</div>
</pre>
</p>

<p>
Maybe the login names seem biazarre, but don't forget that this is a randomly
populated database!
</p>

<p>
Anyway, we'll improve our SPAWK program adding an option to print all users
instead of selected users given as input.
To accomplish that, we can use an AWK variable which will be used as a flag:
if this variable has a non zero value, then all users will be printed,
else (default) the users login names will be read as input line by line.
Choosing <code>all</code> as a name for this flag, our script becomes:
</p>

<p>
<pre><?php require "chat/awk/relprint.awk" ?></pre>
</p>

<p>
<a href="chat/awk/relprint.awk" download="relprint.awk">Download</a>
</p>

<p>
To run the above program for users supplied as input, just type:
</p>

<p>
<pre>gawk -f relprint.awk</pre>
</p>

<p>
But if you want to run the above program for all users in the database,
then you must supply a non-zero value to the variable <code>all</code>
and this can be accomplished via AWK <code>-v</code> option in the
command line:
</p>

<p>
<pre>gawk -v all=1 -f relprint.awk</pre>
</p>

<a name="NVG_chatWAP"></a>
<h3>Writing application programs</h3>

<p>
It's time to enclose all of the above SPAWK stuff about printing relations
in a handy shell script.
In order to do so, we must take care of <code>AWKPATH</code> environment
variable for <code>spawk.awk</code> to be accessible from any directory.
Our application SPAWK scripts must also be located in a well known directory.
We can also specify absolute pathnames for AWK scripts, but it's
strongly recommended not to do so.
</p>

<p>
Let's assume that we are using <code>/usr/local/share/awklib</code>
directory for storing system AWK libraries, e.g. <code>spawk.awk</code>
for accessing databases, <code>ppl.awk</code> for producing random
data etc.
In this case the <code>AWKPATH</code> variable may be set as:
</p>

<p>
<pre>export AWKPATH=".:/usr/local/share/awklib"</pre>
</p>

<p>
Now, let's assume that our <i>chat</i> application uses
<code>/usr/local/apps/chat</code> directory as a base directory.
A possible directory structure under this directory would
contain subdirectories as <code>bin</code>, <code>database</code>,
<code>lib</code> etc. It's good to create an <code>awklib</code>
subdirectory under <code>/usr/local/apps/chat</code> directory
in order to store various AWK scripts concerning the <i>chat</i>
application.
At last, assume that the base directory of the <i>chat</i>
application is stored in the <code>CHAT_BASEDIR</code>
environment variable:
</p>

<p>
<pre>export CHAT_BASEDIR="/usr/local/apps/chat"</pre>
</p>

<p>
Assuming the above <i>chat</i> enviroment, we'll develop the
<code>relprint</code> program in <code>/usr/local/apps/chat/bin</code>
directory.
There is no need to write any complicated C program,
neither to write spaghetti SQL scripts.
All we need is to move the <code>relprint.awk</code> SPAWK script to
<code>/usr/local/apps/chat/awk</code> directory,
and then write a trivial shell script to call AWK properly.
Most of the job will be executed by SPAWK, leaving only trivial tasks
for the shell to do, like parsing command line arguments etc:
</p>

<p>
<pre><?php require "chat/bin/relprint"; ?></pre>
</p>

<p>
<a href="chat/bin/relprint" download="relprint">Download</a>
</p>

<a name="NVG_chatRefinement"></a>
<h3>Program refinement</h3>

<p>
Now that our goal has been accomplished, we can make some program refinements.
To be more specific, we'll add an option for specifying the key column to be other
than the user login name, that is email or full name.
We'll also add some flexibility on what to print.
We choose not to change the <code>relprint</code> program but rather to develop
the <code>relsel</code> program and the corresponding SPAWK script:
</p>

<p>
<pre><?php require "chat/awk/relsel.awk"; ?></pre>
</p>

<p>
<a href="chat/awk/relsel.awk" download="relsel">Download</a>
</p>

<p>
The <code>relsel</code> shell script follows. It's very similar to
<code>relprint</code>, but there are some new options and the
SPAWK script passed to <code>gawk</code> is <code>relsel.awk</code> instead of
<code>relprint.awk</code>:
</p>

<p>
<pre><?php require "chat/bin/relsel"; ?></pre>
</p>

<p>
<a href="chat/bin/relsel" download="relsel">Download</a>
</p>

<a name="NVG_chatRelstats"></a>
<h3>Relation statistics</h3>

<p>
We'll close this introductory SPAWK course by developing one more SPAWK script
to produce some interesting statistics based on our <i>chat</i> user relations.
To be more specific, we want to calculate the number of <i>concordant</i>
as well as the number of <i>contradictive</i> user relations in the database.
A relation is considered to be <i>concordant</i> if the reverse relation
exists and is of the same kind;
conversely, a relation is considered as <i>contradictive</i> if the reverse
relation exists and is of a different kind.
This seems not to be a trivial task to accomplish, but SPAWK comes to our rescue:
</p>

<p>
<pre><?php require "chat/awk/relstats.awk"; ?></pre>
</p>

<p>
<a href="chat/awk/relstats.awk" download="relstats.awk">Download</a>
</p>

<p>
Of course there exist <i>dark corners</i> in our program,
most of them having to do with concurrency issues.
For example, when <i>relation</i> rows are being inserted, deleted or modified
while <code>gawk</code> process is still running,
you may get weird (non integer) results in heavily used databases,
as some of the concordant or contradictive relationships may not be
count twice as they should.
Such undesirable situations are almost impossible to avoid without imposing
table locks, but doing so may lead to much more complicated situations.
As it has been said by Brian Kernighan, one of the creators of AWK:
<i>Dark corners are basically fractal&ndash;no matter how much you illuminate,
there’s always a smaller but darker one</i>.
</p>

<a name="NVG_relfix"></a>
<h3>Fixing anomalies</h3>

<p>
SPAWK is capable of executing DML and DDL queries too.
Based on <code>relstats.awk</code>, the <code>relfix.awk</code> script
will accept the <code>introdel</code> flag in order to delete
self relationships, that is relations where <i>user</i> and <i>related</i>
is the same person.
Relation statistics have also been extended to include self relationships.
</p>

<p>
<pre><?php require "chat/awk/relfix.awk"; ?></pre>
</p>

<p>
<a href="chat/awk/relfix.awk" download="relfix.awk">Download</a>
</p>

<p>
To print relation statistics using <code>relfix.awk</code>:
</p>

<p>
<pre>
gawk -f relfix.awk
<div class="output">146 130 22</div>
</pre>
</p>

<p>
To print relation statistics deleting any self relationships:
</p>

<p>
<pre>
gawk -v introdel=1 -f relfix.awk
<div class="output">146 130 22</div>
</pre>
</p>

<p>
Print relation statistics once again:
</p>

<p>
<pre>
gawk -f relfix.awk
<div class="output">146 130 0</div>
</pre>
</p>

<a name="NVG_spawklib"></a>
<h2>The SPAWK library</h2>

<p>
The SPAWK library is just an AWK script that contains a <code>BEGIN</code>
section and function definitions; you can
<a href="spawklib?child" target="spawklib">view</a>
the code online, but it's better to
<a href="../awklib/spawk.awk" download="spawk.awk">download</a>
the libray and view the code locally.
It's a good practice to locate the library in one of the <code>AWKPATH</code>
directories, so you can include it in your SPAWK scripts as follows:
</p>

<p>
<pre>
@include "spawk.awk"
</pre>
</p>

<p>
Alternatively you can include the SPAWK library in the command line using
the <code>-f</code> option:
</p>

<p>
<pre>
awk -f spawk.awk -f <i>your_script data_files…</i>
</pre>
</p>

<p>
However, including the library in the application AWK script is more compact
and gives the reader a clear sign that database usage is involved in the
sciprt.
</p>

<a name="NVG_spawkAPI"></a>
<h3>The SPAWK API</h3>

<p>
SPAWK API consists of a small subset of SPAWK library functions that can be called
from AWK scripts.
Actually there are less than 10 functions in the API, each belonging to one
of these three categories: authentication, query submition, fetching results,
miscellaneous functions.
</p>

<hr>

<h4><code>spawk_sesami(user, password [, database])</code></h4>

<p>
<code>spawk_sesami</code> is the one and only authentication function in the API.
The parameters are straightforward, namely the database <i>user name</i>,
the <i>user</i>'s</i> password</i> and the <i>database name</i> (optional).
These parameters are used whenever a new client is to be created.
Usually <code>spawk_sesami</code> function is called in the <code>BEGIN</code>
section of an AWK script.
<p>

<hr>

<h4><code>spawk_query(query)</code></h4>

<p>
<code>spawk_query</code> submits a DQL query to the database server for execution.
DQL queries are SQL queries that produce result rows; usually a DQL
query starts with a <code>SELECT</code> statement.
After query submission via <code>spawk_query</code>, all of the result rows must be
retrieved from the client to release the client and get it ready to accept another query.
Submitting another query while there exist result rows waiting to be read,
causes a new client to be pushed in the SPAWK stack.
</p>

<hr>

<h4><code>spawk_fetchrow(row [, nosplit])</code></h4>

<p>
<code>spawk_fetchrow</code> asks for the next result row to be retrieved
from the client that processed the last query passed to <code>spawk_query</code>.
If there are no more result rows to be retrieved, the function returns 0,
else the result row is returned in <i>row</i>[0], while <i>row</i>[1],
<i>row</i>[2],… <i>row</i>[n] are filled in with the corresponding column
values and, finally, the number of columns is returned.
If passed a (non zero) <i>nosplit</i> value, <code>spawk_fetchrow</code>
does not split the row in columns and a value of 1 is returned on success.
</p>

<hr>

<h4><code>spawk_fetchrest()</code></h4>

<p>
<code>spawk_fetchrest</code> is used to skip all remainig result rows.
</p>

<hr>

<h4><code>spawk_fetchone(row [, nosplit])</code></h4>

<p>
<code>spawk_fetchone</code> function is just like <code>spawk_fetchrow</code>,
but skips all remaining result rows.
<code>spawk_fetchone</code> returns 0 if there's no result row to be returned.
</p>

<hr>

<h4><code>spawk_submit(query)</code></h4>

<p>
<code>spawk_submit</code> submits a DML/DDL query to the database server
for execution.
Normally, no result rows willl be produced from such queries.
<code>spawk_submit</code> returns the number of the rows affected,
or 0 for DDL queries and DML queries that did not affect any rows.
On failure -1 is returned.
</p>

<hr>

<h4><code>spawk_error(msg)</code></h4>

<p>
<code>spawk_error</code> is a miscellaneous function that prints an
error to the standard error.
</p>

<hr>

<h4><code>spawk_fatal(msg, err)</code></h4>

<p>
<code>spawk_fatal</code> is just like <code>spawk_error</code>,
but exits AWK program with exit status <code>err</code>.
</p>
