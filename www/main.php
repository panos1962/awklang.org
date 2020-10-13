<a name="NVG_top"></a>
<h1>AWK in brief</h1>

<a name="NVG_intro"></a>
<h2>Introduction</h2>

<p>
AWK is a multi purpose software tool mainly used as a filter,
that is a program to read input and produce output.
Input is readed in <i>lines</i> and every line is split into <i>fields</i>.
After reading and splitting each line into fields, AWK can take some action e.g. print, count etc,
according to <i>patterns</i> matched by the line readed.
The following excerpt (pages 2-3) from the famous book
"<a href="https://ia802309.us.archive.org/25/items/pdfy-MgN0H1joIoDVoIC7/The_AWK_Programming_Language.pdf"
target="_blank" title="The AWK Programming Language [PDF]">The AWK Programming Language</a>"
shows the elegance of AWK:
</p>

<p>
	<img src="<?php print HTML::url("image/misc/awk_syntax.png"); ?>"
		style="margin: 20px; width: 600px; box-shadow: 1px 1px 1px #333, -1px -1px 1px #333; border-radius: 10px;">
<p>

<p>
In other words every AWK program is a series of <i>pattern</i>/<i>action</i> items. AWK reads input
one line at a time, checks each line against the specified patterns and takes the correspondig actions
for the matching lines.
Default action is "print the line", while missing pattern "matches" every input line,
meaning that an action without a pattern will be taken for every single line of input.
On the other hand, a pattern without an action causes matching lines to be printed,
while non matching lines will be whipped out.
There are two extra "patterns", namely <code>BEGIN</code> and <code>END</code>,
for actions to be taken before any input has been read and after all input has been read.
That's all!

<p>
Here is a simple example taken by an
<a href="https://youtu.be/6FWB9CJc_7w" target="_blank">interview</a>
of Brian Kernighan, one of the creators of AWK.
We have volcano eruption data in a file.
Each line consists of three columns separated by tab characters.
First column is the volcano name, second column contains the date of the eruption,
while third column is the magnitude of the eruption in a scale from 1 to 6.
</p>

<p>
Given that specific file of volcanic eruption data,
we can do some interesting things with AWK just by using one-liner AWK programs.
Let's say the data file is named <i>vedata</i>.
The following will print eruptions of magnitude greater than 4:
</p>

<p>
<pre>awk '$3 &gt; 4' vedata</pre>
</p>

<p>
In this case we used AWK as a <i>filter</i> on eruption magnitude.
Our program consists of just one pattern, that is check for magnitude to be
greater than 4. Action is missing, which means <i>print</i> (default action)
the matching lines.
</p>

<p>
Now lets' print all eruptions of volcano "Krakatoa", filtering data by volcano name:
</p>

<p>
<pre>awk '/Krakatoa/' vedata</pre>
</p>

<p>
or more precisely:
</p>

<p>
<pre>awk '$1 == "Krakatoa"' vedata</pre>
</p>

<p>
Assuming that eruption dates are formated as YYYYMMDD,
e.g. 18830827 is August 27 1883, we can print all eruptions of year 1996:
</p>

<p>
<pre>awk '($2 &gt;= 19960000) &amp;&amp; ($2 &lt; 19970000)' vedata</pre>
</p>

<p>
or
</p>

<p>
<pre>awk '($2 &gt; 19959999) &amp;&amp; ($2 &lt; 19970000)' vedata</pre>
</p>

<p>
We can produce some amazing results by writing just a few lines of code.
Let's say we want to print total eruption counts by magnitude:
</p>

<p>
<pre>awk '{
	count[$3]++
}
END {
	for (i in count) {
		print i, count[i]
	}
}' vedata | sort -n</pre>
</p>

<p>
Now let's print eruption count by year of eruption:
</p>

<p>
<pre>awk '{
	count[int($3 / 10000)]++
}
END {
	for (i in count) {
		print i, count[i]
	}
}' vedata | sort -n</pre>
</p>

<a name="NVG_history"></a>
<h2>History</h2>

<p>
AWK is a program developed in Bell Labs of AT&amp;T in the late 1970s
as a general purpose tool to be included in the UNIX toolset.
The name AWK is derived from the names of its creators:
<a href="https://en.wikipedia.org/wiki/Alfred_Aho" target="_blank">Al <b>A</b>ho</a>,
<a href="https://en.wikipedia.org/wiki/Peter_J._Weinberger" target="_blank">Peter <b>W</b>einberger</a>
and <a href="https://en.wikipedia.org/wiki/Brian_Kernighan" target="_blank">Brian <b>K</b>ernighan</a>.
</p>

<p>
From the first days after its birth, AWK has been prooved to be an invaluable tool
and has been used in more and more shell scripts and other UNIX tools.
After GNU project creation, AWK was significantly revised and expanded,
eventualy resulted in <b><code>gawk</code></b>, the GNU AWK implementation
written by Paul Rubin, Jay Fenlason and Richard Stallman.
GNU AWK is being maintained by Arnold Robbins since 1994.
</p>

<p>
Over the years, there have been implemented other versions of awk as well.
Brian Kernighan himself developed <code>nawk</code>, which has been dubbed the <i>one-true-awk</i>;
<code>nawk</code> has been used in many operating systems e.g.
FreeBSD, NetBSD, OpenBSD, macOS and illumos.
Another well known AWK implementation is <code>mawk</code>, written by Mike Brennan,
now maintained by Thomas Dickey.
</p>

Nowdays, plain <code>awk</code> is no more in use because of <code>gawk</code>'s increased functionality.
Most of modern operating systems offer plain awk as another name (link) of gawk.
In my current Linux system there is a symbolic link of
<code>/usr/bin/awk</code> to <code>/etc/alternatives/awk</code> which in turn is linked to
<code>/usr/bin/gawk</code>.
</p>

<p>
However, every piece of software must be constantly evolving to reflect modern trends in
software or hardware development and AWK is no exception.
Over the years there were  many aspects to be handled by <code>awk</code>/<code>gawk</code>
maintainers, e.g. internationalization, localization, wide characters, need for extensions,
C&#8209;hooks, two-way communications etc.
Last improvements of <code>gawk</code> are about <i>dynamic extensions</i>,
but there are more improvements to be done in the future.
</p>

<a name="NVG_basics"></a>
<h2>Basics</h2>

<p>
To use AWK effectively, one must know the <i>core loop</i> of AWK processing and some
basic concepts affecting AWK's view of <i>lines</i> and <i>columns</i>.
There are few handy command line options which may prove indispensable to know.
Basic knowledge of
<a href="http://www.rexegg.com/regex-quickstart.html" target="_blak">regular expressions</a>
is also needed to use pattern matching the right way.
It's also good to know how to redirect AWK's standard output to files or pipes;
you can also redirect standard input or standard error.
All of the above form a good background to start using AWK effectively,
but you can also make beneficial use of AWK with much less,
as long as you know what you are doing.
</p>

<p>
One thing that must be clear before using AWK is the execution road map
of every AWK program:
AWK executes some initialization code given in the <code>BEGIN</code>
section (optional).
Then, follows the so called <i>core loop</i>; AWK reads input one <i>line</i>
at a time and checks each <i>line</i> against given patterns (optional).
By the word <i>line</i> we usually mean one line of input, but you can change that
by setting <code>RS</code> variable to any single character or regular expression,
e.g. if <code>RS</code> is set to "<code>\n@\n</code>", then each <i>line</i> may contain
more than one lines of input, that means anything between lines consisting
just of a single "<code>@</code>" character is considered a <i>record</i>.
</p>

<p>
If a line (record) matches a pattern, then the corresponding action is taken.
A <code>next</code> command in a taken action causes all remaining pattern/action
entities to be skipped for the line at hand and the next input line is readed.
The same process is repeated until no more input lines remain.
After all input lines have been readed (and processed),
the <code>END</code> action is executed (if exists) and AWK exits.
</p>

<a name="NVG_lrf"></a>
<h3>Lines, Records and Fields</h3>

<p>
Actually, <code>RS</code> means <i>record separator</i>, so it's more
accurate to say that AWK reads input one <i>record</i> at a time,
than the usual <i>line</i> by <i>line</i>.
However, due to default behavior of AWK, the words <i>record</i> and
<i>line</i> are used interchangeably in the AWK's jargon.
Let's see the use of <code>RS</code> using input consisting of login names,
followed by email addresses and phone numbers.
Each user name ends with a single "<code>@</code>" character:
</p>

<p>
<pre>
panos
panos1962@gmail.com
0030-2310-999999
@
arnold
arnold@yahoo.com
0045-6627-999999
@
mike
mike@awk.info
0020-2661-999999
@
maria
maria@baboo.com
0035-9932-999999
</pre>
</p>

<p>
We want to construct a spreadsheet file of two columns, namely the login name
and the phone number. We can achieve this task with AWK, just by setting "<code>@</code>"
as record separator, tab character as output field separator and pipping the results
to <code>ssconvert</code>:
</p>

<p>
<pre>
awk 'BEGIN {
	RS = "\n@\n"	# records separated by "@" lines
	OFS = "\t"	# set tab character as output field separator
}

{
	print $1, $3	# print user name and phone number, tab separated
}' user | ssconvert fd://0 user.ods
</pre>
</p>

<p>
That was less than 10 lines of AWK code, but the following one-liner would be suffice:
</p>

<p>
<pre>
awk -v RS='\n@\n' -v OFS='\t' '{ print $1, $3 }' user | ssconvert fd://0 user.ods
</pre>
</p>

<p>
Note the use of <code>-v</code> command line option in order to set the <i>record
separator</i> (<code>RS</code>) and the <i>output field saparator</i>
(<code>OFS</code>) in the command line.
We talked earlier about the <code>RS</code> variable, but what is the <code>OFS</code>?
</p>

<p>
We can print output using the <code>print</code> command in AWK.
To print many items, side by side, we can specify the items just after the
<code>print</code> command. In this case, the items are printed one after the other
without any kind of separation in between.
Using the comma separator between items to be printed, causes AWK to print the
items one after the other, although separated by spaces.
If we want the sparator to be something else, then we can do so by setting the
<code>OFS</code> variable to the separator wanted:
</p>

<p>
<pre>
awk -v OFS='&lt;&lt;&gt;&gt;' '{ print $1, $NF }'
</pre>
</p>

<p>
Assuming input consists of lines, were each line starts with the user's name, and
ends with the user's country, the above AWK one-liner will print the first and last
fields of every input line separated by "<code>&lt;&lt;&gt;&gt;</code>":
</p>

<p>
<pre>
panos&lt;&lt;&gt;&gt;Greece
maria&lt;&lt;&gt;&gt;India
arnold&lt;&lt;&gt;&gt;Israel
mike&lt;&lt;&gt;&gt;England
...
</pre>
</p>

<p>
As you may have already guessed, <code>NF</code> variable is the columns' count
of the current input line (record).
Just after reading each input line, AWK counts the fields (coulumns) of the line
and set the <code>NF</code> accordingly.
We can then refer to each field as <code>$1</code>, <code>$2</code>, …<code>$NF</code>,
while <i>fields</i> are separated by AWK by the means of the <code>FS</code>
variable.
<code>FS</code> stands for <i>field separator</i> and by default is any number of
consequent space and tab cracters.
If input is a CSV file, then we must set <code>FS</code> to "<code>,</code>",
while if input fields are tab separated, then we must set <code>FS</code>
to "<code>\t</code>".
</p>

<a name="NVG_regexp"></a>
<h3>Regular Expressions</h3>

<p>
Regular Expressions (RE) are tightly coupled with AWK because AWK
talks the language of REs out of the box, e.g. in the following AWK script
the pattern sections are just RE matches:
</p>

<p>
<pre>
BEGIN { ascetion = bsection = 0 }
/^[aA][0-9]+/ { asection++ }
/^[bB][0-9]+/ { bsection++ }
END { print asection, bsection }
</pre>
</p>

<p>
The first pattern is a regular expression match as the two slashes (<code>/</code>) denote.
Actually the first pattern means: $0 matches <code>^[aA][0-9]+</code> regular expression.
But what exactly does this mean? In the RE dialect the <code>^</code> denotes the beginning
of the checked item (in this case the checked item is the whole line, so the <code>^</code>
symbol denotes the beginning of the line), followed by the letters <code>a</code> and
<code>A</code> enclosed in brackets, which means one of the two letters <code>a</code>
or <code>A</code>, followed by a string of numerical digits (at least one).
This RE matches the following lines:
</p>

<p>
<pre>
<strong>a1</strong> This is a…
<strong>A1</strong>.1 In this chapter…
<strong>A29</strong> All of the…
<strong>a30</strong>B12 Any of these…
</pre>
</p>

<p>
We've emphasized the matched part of the line.
The second pattern is similar, but the first letter must be <code>b</code> or
<code>B</code> for the line to be matched.
</p>

<p>
Now let's say that we want the above REs to be matched
by the second field instead of the whole line.
There comes&nbsp;<code>~</code>, the so called <i>match</i> operator, to rescue:
</p>

<p>
<pre>
BEGIN { ascetion = bsection = 0 }
$2 ~ /^[aA][0-9]+/ { asection++ }
$2 ~ /^[bB][0-9]+/ { bsection++ }
END { print asection, bsection }
</pre>
</p>

<p>
The above script is readed as: <i>Before reading any input, set <code>asection</code>
and <code>bsection</code> counters to zero.
For lines where the second field begins with <code>a</code> or <code>A</code> followed
by at least one numerical digit, increase <code>asection</code> by one.
For lines where the second field begins with <code>b</code> or <code>B</code> followed
by at least one numerical digit, increase <code>bsection</code> by one.
After processing all of the input lines, print <code>asection</code> and
<code>bsection</code> counters.</i>
</p>

<a name="NVG_io"></a>
<h3>I/O redirection</h3>

<p>
It's very easy for AWK to redirect input and output.
Actually, redirecting I/O in AWK is very similar to shell I/O redirection:
AWK and shell use the same redirection operators, namely "<code>&gt;</code>"
for output redirection, "<code>&lt;</code>" for input redirection,
"<code>&gt;&gt;</code> for appending data to files and
"<code>|</code>" for piping data to another running program.
</p>

<p class="warning warningRed">
<strong>Warning!</strong>
<br>
Because it's so easy for AWK to redirect output to files, you must know
what you are doing or else you are risking your files and data!
</p>

<p>
Let's say we want our program to filter input lines as follows:
All lines with 3d column value less than a given number (<i>min</i>) to be collected to a file,
lines with 3d column value greater than another given number (<i>max</i>) to be collected to another file,
while all lines with 3d column value between <i>min</i> and <i>max</i> to be printed to standard output.
Here is the AWK program to run this task:
</p>

<p>
<pre>
$3 &lt; min { print &gt;less; next }
$3 &gt; max { print &gt;more; next }
{ print }
</pre>
</p>

<p>
Assuming the data file is named <code>data</code> and the above program is stored to
<code>filter.awk</code> file, we can create files <code>less10</code> and
<code>more20</code> by running:
</p>

<p>
<pre>
awk -v min=10 -v less="less10" -v max=20 -v more="more20" -f filter.awk data
</pre>
</p>

<a name="NVG_conclusion"></a>
<h2>Conclusion</h2>

<p>
Nowdays there exist plenty of amazing software languages, tools, frameworks, APIs etc
for handling almost every need arising in our full computerised world, from simple
calculations, to outer space communications, nanorobotics and machine learning.
There is no single piece of software to meet every computer need out there,
but AWK is a handy single program found in every computer system, standing there
listening to your needs without asking for tons of supporting software,
neither asking for super extra high payed experts on software engineering,
nor for supercomputer sized machines in order to carry out from the simplest
to the most complex tasks, just by following a few lines of code.
</p>
