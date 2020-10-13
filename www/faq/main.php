[&nbsp;<a id="expand" href="#"></a>&nbsp;]

<a name="NVG_FAQ"></a>
<h1>Frequently asked questions</h1>

<a name="NVG_generalQuestions"></a>
<h2>General questions</h2>

<a name="NVG_awkName"></a>
<h3 class="question">What is the difference between awk, nawk, mawk, gawk etc?</h3>

<div class="response">
<p>
AWK is one of the oldest software tools found in every UNIX&#x2011;like
operating systems.
The first AWK version was implemented as <code>awk</code>.
AWK was significantly revised and expanded in 1985&#x2011;88,
resulting in the GNU AWK or <code>gawk</code> implementation
written by Paul Rubin, Jay Fenlason, and Richard Stallman, released in 1988
(maintained by Arnold Robbins since 1994).
</p>

<p>
Brian Kernighan's <code>nawk</code> (New AWK) source was first released in 1993 unpublicized,
and publicly since the late 1990s.
<code>mawk</code> is a very fast AWK implementation by Mike Brennan based on a bytecode interpreter
(maintained by Thomas Dickey since 2009),
<code>tawk</code> (Thompson AWK) is an AWK compiler for Solaris, DOS, OS/2, and Windows,
previously sold by Thompson Automation Software (which has ceased its activities).
There also exists <code>Jawk</code>, a project to implement AWK in Java etc.
</p>

<p>
There is no standard of what version of AWK is installed and running in a given system.
It's a matter of software packaging and software distribution.
It's likely to have more than one version of AWK installed in a system.
What version is running under the generic <code>awk</code> name is usually
a matter of links and <code>PATH</code> settings.
You can always check your AWK version with the <code>&#x2011;&#x2011;version</code> option;
shell <code>hash</code> command may also help.
</p>
</div>

<a name="NVG_tecnicalUqestions"></a>
<h2>Technical questions</h2>

<a name="NVG_localVariables"></a>
<h3 class="question">How can I define local variables in AWK?</h3>

<div class="response">
<p>
AWK is very loose in variable definition.
There is no need to define a variable in AWK, variables come to life whenever they are
used for the first time.
This seems to be a good thing but it's not, because this way all variables in AWK
become global objects and global objects are confusing and buggy.
Actually there are two kinds of global objects in AWK: functions and variables.
</p>

<p>
However, not all variables are global in AWK.
Some variables have only function scope, that is they are <i>local</i> to specific functions.
These variables are the <i>parameters</i> of a function.
Actually, we can make use of this fact to "define" <i>local</i> variables with function scope.
The usual practice is to list the desired <i>local</i> variables as function parameters,
after the normal function parameters.
In order to make this clear to humans, we choose to separate the normal function parameters
from the local variables, leaving some space (usually 1&#x2011;2 tabs) after the normal
function parameters:
</p>

<p>
<pre>
...
function total(amount,			tot, i) {
	tot = 0

	for (i in amount)
	tot += amount[i]

	return tot
}
...
</pre>
</p>

<p>
In the above AWK snippet we define function <code>total</code> which takes
an array of amounts as an argument and returns the sum of the array elements
(total amount).
In order to iterate over the array elements we need a local variable for the
index of the array (<code>i</code>) and another local variable for amount
accumulation (<code>tot</code>).
These two variables are made <i>local</i> to the function <code>total</code> by just
represent them as function parameters, although we'll never pass values for these
arguments to the function. Function calls will always take just one argument,
the array of the amounts to sum.
</p>

<p>
If the function does not accept any (normal) argument, then we leave some space before
the desired local variables:
</p>

<p>
<pre>
...
function <i>function_name</i>(			i, j) {
...
</pre>
</p>

<p>
In the above AWK snippet we define variables <code>i</code> and <code>j</code>
as <i>local</i> to the function <code><i>function_name</i></code>.
</p>
</div>

<a name="NVG_arrayScanOrder"></a>
<h3 class="question">In what order is an array scanned/traversed?</h3>

<div class="response">
<p>
By default, when a for loop traverses an array, the order is undefined,
meaning that the awk implementation determines the order in which the array is traversed.
This order is usually based on the internal implementation of arrays and will vary from
one version of awk to the next.
Read more in  the GNU AWK user's guide relevant
<a href="https://www.gnu.org/software/gawk/manual/html_node/Controlling-Scanning.html#Controlling-Scanning" 
target="_blank">section</a>.
</p>
