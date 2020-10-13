<a name="NVG_top"></a>
<h1>AWK related software</h1>

<p>
There exist software tools other than AWK that a programmer may use instead.
Some of them are easier to use for specific tasks, others may run faster than AWK does.
Anyway, it's not bad to know that AWK is not the only porgrammable tool for
filtering and processing data; it's also fun to try to solve specific problems
with more than one ways and compare the results for effectiveness, resource usage,
clarity and beauty.
</p>

<a name="NVG_sed"></a>
<h2>sed stream editor</h2>

<p>
<code>sed</code> is a <i>stream editor</i>, means that <code>sed</code>
is not an interactive editor where the writer is seating in front
of a terminal, writing and editing some text.
<code>sed</code> is a batch program that can take some directives and then
reads data, filter them according to those directives and prints the
resulting data, without any user intervention while processing the data.
The GNU <code>sed</code> <a href="https://www.gnu.org/software/sed/manual/sed.html"
target="_blank">manual</a> is very instructive, but there are also many
good books about <code>sed</code>, e.g. the classical
"<a href="http://shop.oreilly.com/product/9781565922259" target="_blank">sed &amp; awk</a>"
by Dale Dougherty and Arnold Robbins.
</p>

<p>
<code>sed</code> has many things in common with AWK, e.g. regular expressions.
Almost always, one can use AWK instead of <code>sed</code>, but sometimes
doing so may be an overkill.
</p>

<p class="warning warningYellow">
<strong>Check this out!</strong>
<br>
In my computer, the size of the <code>gawk</code> executable is about nine times
the size of the <code>sed</code> executable.
</p>

<p>
Another significant reason for not using AWK where you can use </code>sed</code>,
is that in many cases <code>sed</code> directives may be more clear than the
correspondng AWK program, to carry out the same task.
</p>

<p>
Let's say we want to filter our data as follows:
We want to substitute all occurences of "<code>@</code>" symbol with the
"<code>#</code>" symbol, but only in lines begining with a capital letter.
Here is the <code>sed</code> approach:
</p>

<p>
<pre>
sed '/^[[:upper:]]/s/@/#/g'
</pre>
</p>

<p>
The corresponding AWK apporach is:
</p>

<p>
<pre>
gawk '/[[:upper:]]/ {
	gsub(/@/, "#")
}

{
	print
}'
</pre>
</p>

<p>
After running the above on a huge amount of data, I've found out that
<code>sed</code> may run up to 2 times faster than <code>gawk</code>!
<code>mawk</code> runs faster than <code>gawk</code>, but lacks the
<code>[[:upper:]]</code> pattern for matching UTF-8 capital letters.
</p>

<a name="NVG_perl"></a>
<h2>The Perl programming language</h2>

<p>
To be honest, I don't know
<a href="https://www.perl.org/about.html" target="_blank">Perl</a>,
but it's more than a sure fact that Perl is a very popular language.
There exist innumerable shell scripts that use Perl scripts inside,
and there exist even more application programs based on Perl.
The fact that Perl uses regular expressions and is programmable,
just like AWK does, makes the two languages to be similar and
competitive.
</p>

<p>
There is no answer to the question who is better, Perl or AWK,
as there is no answer to many other similar questions,
e.g. Apache vs NGINX, Linux vs FreeBSD, GNOME vs KDE,
LAMP vs MEAN etc.
I prefer AWK, but as I said earlier, I don't know how to use Perl
effectively because I don't know Perl at all.
Many of my colleagues prefer Perl, others use both of these two
magnificent languages.
</p>

<p>
The truth is that the majority of AWK programs are more readable
than the correspondig Perl ones.
In fact AWK happens to be one of the most elegant software tools ever
written, while it's a known joke that Perl programs are write-only
programs.
To be serious, Perl is an extremely poweful software tool standing
side by side with AWK in the programmer's quiver.
</p>
