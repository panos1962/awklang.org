<a name="NVG_top"></a>
<h1>PPL &mdash; Producing random data</h1>

<a name="NVG_intro"></a>
<h2>Introduction</h2>

<p>
Based on AWK <code>rand</code>/<code>srand</code> functions, PPL library may be
used to produce random data of some of the basic data types: strings, numbers
and dates.
We can use PPL functions to produce millions of data rows in order to populate
databases for testing purposes (<i>PPL</i> name, indeed, comes from the word <i>populate</i>).
The following AWK script will produce a random number (between 100 and 200)
of random length (between 10 and 20 characters long) strings of lowercase english
letters:
</p>

<p>
<pre><?php require "awk/renstr.awk" ?></pre>
</p>

<p>
PPL functions may be used not only for populating test databases,
but for testing any kind of program that manipulates ascii data.
Let's say we've just developed a C program that implements a new sort
algorithm (wow!) and we need to run some tests in order to validate the
correctness and check the efficiency of our newly created program.
One of our tests could be to sort a million line ascii data file of two tab separated columns,
namely an 8-digit integer in the first column and a string of length 40 to 60 characters long
in the second column:
</p>

<p>
<pre><?php require "awk/testsort.awk" ?></pre>
</p>

<p>
<a href="awk/testsort.awk" download="testsort.awk">Download</a>
</p>

<p>
As you see, <code>count</code> variable is not set, so one can set it in
the command line. Given that our program is stored in the <code>testsort.awk</code>
file, run the following command to create the file <code>test1M.data</code>:
</p>

<p>
<pre>
awk -v count=1000000 -f testsort.awk >test1M.data
</pre>
</p>

<p>
In less than 10 seconds 1000000 lines of random data will be written by AWK
in file <code>test1M.data</code>:
</p>

<p>
<pre class="output">
<?php system('AWKPATH=".:../awklib" awk -v count=10 -f ./awk/testsort.awk'); ?>
...
</pre>
</p>

<a name="NVG_ppllib"></a>
<h2>The PPL library</h2>

<p>
The PPL library is just an AWK script that contains a <code>BEGIN</code>
section and some function definitions; you can
<a href="ppllib?child" target="ppllib">view</a>
the code online, but it's better to
<a href="../awklib/ppl.awk" download="ppl.awk">download</a>
the libray and view the code locally.
It's a good practice to locate the library in one of the <code>AWKPATH</code>
directories, so you can include it in your AWK scripts as follows:
</p>

<p>
<pre>
@include "ppl.awk"
</pre>
</p>

<p>
Alternatively you can include the PPL library in the command line using
the <code>-f</code> option:
</p>

<p>
<pre>
awk -f ppl.awk -f <i>your_script data_files…</i>
</pre>
</p>

<p>
However, including the library in the application AWK script is more compact
and gives the reader a clear sign that random data will be used in the
sciprt.
</p>

<a name="NVG_pplAPI"></a>
<h3>The PPL API</h3>

<p>
PPL API consists of a small number of AWK functions that can be called
from AWK scripts.
Actually there are less than 10 functions in the API.
</p>

<hr>

<h4><code>ppl_string(min, max, palette)</code></h4>

<p>
<code>spawk_string</code> returns a random length string of letters
randomly choosed from the <i>palette</i>.
<code>min</code> and <code>max</code> parameters governs the length of
the string, as the returned string will be of random length between
these to numbers (inclusive).
<code>palette</code> is an array of characters (1-based).
The length of the array must be stored in <code>palette[0]</code>,
e.g. a palette of three letters, namely "A", "B" and "C" would be
an array like this:
<p>

<p>
<pre>
ABC[0] = 3
ABC[1] = "A"
ABC[2] = "B"
ABC[3] = "C"
</pre>
</p>

<p>
Because it's cumbersome to form a palette one item at a time,
PPL function <code>ppl_palette</code> may be used to produce
palettes from arbitrary strings; the above palette would have
been produced by calling:
</p>

<p>
<pre>
ppl_palette("ABC", ABC)
</pre>
</p>

<hr>

<h4><code>ppl_integer(min, max)</code></h4>

<p>
<code>ppl_integer</code> function returns a random integer number value
between <code>min</code> and <code>max</code> (inclusive).
</p>

<hr>

<h4><code>ppl_float(min, max)</code></h4>

<p>
<code>ppl_float</code> function returns a random floating point number value
between <code>min</code> and <code>max</code>.
Returned values may reach <code>min</code> but they never
reach <code>max</code>, just like <code>rand</code> values are returned
in </code>[0,&nbsp;1)</code> range.
</p>

<hr>

<h4><code>ppl_timestamp(min, max)</code></h4>

<p>
<code>ppl_timestamp</code> function returns a random timestamp between
values <code>min</code> and <code>max</code>.
This function behaves much like the <code>ppl_integer</code> function
but with these two particularities: if <code>max</code> is missing,
then it will be set to the current system time;
if <code>min</code> is negative then <code>min</code> will be calculated
by subtracting the given number from <code>max</code>.
</p>

<hr>

<h4><code>ppl_login()</code></h4>

<p>
<code>ppl_login</code> function returns a random string to simulate
simple login names, e.g. panos, maria, arnold etc.
Of course the names produced by <code>ppl_login</code> are rarely
pronounceable, but who cares; programs do not pronounce login names,
do they?
</p>

<hr>

<h4><code>ppl_domain()</code></h4>

<p>
<code>ppl_domain</code> function returns a random string to simulate
domain names, as <i>sdfheuejdj.asd</i>, <i>fhepll.xx</i>, <i>esdfdfgfg.syo</i> etc.
As you may know, the after dot part is called TLD (top level domain);
TLDs produced by <code>ppl_domain</code> function are 2 or 3 characters long,
while the before dot part of the produced domain is a random string of lowercase
english letters.
</p>

<hr>

<h4><code>ppl_email()</code></h4>

<p>
<code>ppl_email</code> function returns random email addresses
produced as a random login name, followed by a "<code>@</code>" character,
followed by a random domain name.
</p>

<hr>

<h4><code>ppl_name()</code></h4>

<p>
<code>ppl_name</code> function returns random names, that is two-part space
separated character strings, the two parts to simulate the first and the last name.
Each part begins with a random uppercase english letter and is followed
by a number of lowercase english letters.
</p>

<hr>

<h4><code>ppl_palette(string, palette)</code></h4>

<p>
Function <code>ppl_palette</code> is used for <i>palette</i> construction.
The first parameter is an arbitrary string, while the second is
the palette to be constructed.
Palette is constructed as an 1-based array of all characters of the passed
string, while at position 0 of the array the palette length must
be stored.
<code>ppl_palette</code> returns the length of the palette.
</p>
