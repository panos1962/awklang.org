<a name="NVG_top"></a>
<h1>AWK tutorial</h1>

<p>
AWK refers to the program itself which may have been implemented as
<code>awk</code>, <code>nawk</code>, <code>mawk</code>, <code>gawk</code> etc,
or to the language in which AWK programs are written.
So, we can say that <i>AWK is a heavily used software tool</i>
(referring to the program itself),
or that <i>AWK is an easy to learn language</i>
(referring to the AWK language).
AWK can be used as an autonomous program to be executed in the command line
during an interactive shell session,
but it's more likely to use AWK in shell scripts, usually in conjuction with other
software tools.
</p>

<p>
AWK programs may be just one&#x2011;liners given on the fly as the first AWK's command line argument,
but it's not rare for an AWK program to extend in hundreds or even thousands of lines;
if an AWK program counts more than one line, then it's easier (and safer)
to save the program in a file and use <code>&#x2011;f</code> option in the command line.
We can use multiple scripts using <code>&#x2011;f</code> more than once in the
command line; in this case the programs are concatenated and run as a whole.
</p>

<p>
Beyond <i>static</i> AWK scripts written in specified files,
it's a common practice to construct AWK scripts on the fly from other running programs,
save them in temporary files, run AWK with the constructed programs and
then remove the scripts.
</p>

<a name="NVG_awksyntax"></a>
<h2>AWK syntax</h2>

<p>
Every AWK program is just a pattern/action series:
</p>

<p>
<pre>
pattern { action }
pattern { action }
...
</pre>
</p>

<p>
Every input line is checked against each pattern in turn.
If the line matches a pattern, then the coreesponding action is taken.
To skip further pattern checks after a taken action,
one can use the <code>next</code> keyword in the corresponding action.
</p>

<p>
There exist two special "patterns".
The <code>BEGIN</code> pattern action is executed before any input has been read,
while the <code>END</code> pattern action is executed just before AWK program exits.
The following program print "Hello!" before any input line is read, then counts lines
with an even number of fields, and lines with more than five fields.
After all input lines have been read, the program prints the two counters,
then prints "Bye..." and exit:
</p>

<?php HTML::code_quote("awk/count.awk"); ?>

<p>
An AWK program may have no actions at all;
in that case AWK just prints input to output just like <code>cat</code> program does.
If a pattern lacks an action, then every line that matches the patern is printed (default action).
If an action lacks a pattern, then this action is executed for all lines reached this pattern.
There is nothing else to say about the basic syntax of every AWK program.
</p>

<a name="NVG_even"></a>
<h2>Counting even numbers</h2>

<p>
We start this tutorial course by writing a program to count all even numbers in input.
Input consists of lines, where each line contains an integer number.
To count all even numbers in the input stream we devide each number by 2 and check the remainder to be 0:
</p>

<?php HTML::code_quote("awk/even.awk"); ?>

<p class="warning warningBlue">
<strong>Did you know?</strong>
<br>
As you might have already guessed, <code><strong>$0</strong></code> refers to the current
input line, while <code><strong>NF</strong></code> refers to the number of fields in the
current input line.
By the way, <code><strong>$1</strong></code> refers to the first field of the current input line,
<code><strong>$2</strong></code> refers to the second field of the current input line and so on.
</p>

<p>
Now let's make some changes in order to count not only evens, but also multiples of 3.
Our first (and obvious) approach is to use two counters istead of one,
then add another pattern/action concerning division by 3 remainders
and finally to print the two counters instead of one:
</p>

<?php HTML::code_quote("awk/count23.awk"); ?>

<p>
In order to understand how AWK is working, we'll trace AWK's logic by hand
for some input numbers.
Let's say we get number 4 in the input.
AWK checks number 4 against the first given pattern, that is if the remainder
of 4 devided by 2 is 0; this pattern is matched by number 4, therefore the relevant action is taken,
that is <code>count2</code> will be increased; because it's the first time that variable
<code>count2</code> comes into play, it has no value, so adding 1 to
<code>count2</code> set that variable to 1, as undefined variables involved
in numerical expressions will be considered as zero valued.
By the way, undefined variables involved in alphahnumeric (string) expressions,
will be considered as empty strings. 
</p>

<p>
After finishing with the first pattern/action, AWK moves to the next pattern/action
which is to check the number against division by 3 which clearly fails,
as 4 is not a multiple of 3. Therefore no action is taken and there exist no more
pattern/action entitites to be processed, so AWK reads the next input line,
which let's assume is the number 11.
</p>

<p>
Clearly, 11 doesn't divide exactly neither by 2, nor by 3, so AWK takes no action
and reads the next input line, let's say the number 12.
Number 12 is devided by 2, so <code>count2</code> will be increased.
Number 12 is also devided by 3, so <code>count3</code> will be increased as well.
The same procedure will be followed for every input line.
After reading all input lines, AWK will execute the <code>END</code> action which
is to print <code>count2</code> and <code>count3</code> counters, and then exits.
Mission accomplished!
</p>

<a name="NVG_morecount"></a>
<h2>More counting…</h2>

<p>
Our second approach in counting evens and multiples of three is to
make the number counting AWK program more flexible, that is not just
to count multiples of 2 and 3, but to count multiples of any given numbers;
we'll call these numbers as <i>check numbers</i>.
Clearly, there are two tasks involved in that process:
</p>

<li>
Specify <i>check numbers</i>
</li>

<li>
Read input and count multiples
</li>

<p>
Following the above scheme we may run the program in two phases.
Because this is an AWK tutorial, we choose to implement both of these two phases using AWK.
First phase consists of constructing another AWK script to be used in the second phase:
</p>

<?php HTML::code_quote("awk/morecount.awk"); ?>

<p>
Assuming that the above script is saved in <code>morecount.awk</code>,
run AWK and type the <i>check numbers</i> 2, 3, 5 and 7:
</p>

<p>
<pre>
awk -f morecount.awk
<div class="input">2</div>
<div class="output">(($0 % 2) == 0) { count2++ }</div>
<div class="input">3</div>
<div class="output">(($0 % 3) == 0) { count3++ }</div>
<div class="input">5</div>
<div class="output">(($0 % 5) == 0) { count5++ }</div>
<div class="input">7</div>
<div class="output">(($0 % 7) == 0) { count7++ }</div>
<div class="inputControl">[Control-D]</div>
<div class="output">END { print count2, count3, count5, count7 }</div>
</pre>
</p>

<p>
The output is an AWK script similar to the script we wrote by hand earlier
in order to count 2 and 3 multiples.
In other words, our AWK program <i>produces</i> another AWK script which will count
the multiples of 2, 3, 5 and 7.
That's great, but running the above on the fly is of not much use because the output is lost.
In order to create a script from the above output, we shall redirect output to a file:
</p>

<p>
<pre>
awk -f morecount.awk &gt;count2357.awk
<div class="input">2</div>
<div class="input">3</div>
<div class="input">5</div>
<div class="input">7</div>
<div class="inputControl">[Control-D]</div>
</pre>
</p>

<p>
The <code>count2357.awk</code> file will contain the final AWK script which shall
be used to count the multiples of 2, 3, 5 and 7 for any input numbers:
</p>

<p>
<pre>
<?php system("for i in 2 3 5 7; do echo \$i; done | awk -f awk/morecount.awk"); ?>
</pre>
</p>

<p>
We've just used AWK as an AWK program generator!
That means, we can now run:
</p>

<p>
<pre>
awk -f count2357.awk <i>input_file</i>
</pre>
</p>

<p>
The above command line will count multiples of 2, 3, 5 and 7 for all numbers
in <code><i>input_file</i></code> and print the results to the standard output.
However we didn't write <code>count2357.awk</code> by hand but we ran another
AWK script to produce <code>count2357.awk</code> script for us.
Cool!
</p>

<a name="NVG_countbetter"></a>
<h2>Counting better</h2>

<p>
We'll implement the multiple counter program using another method.
There are two reasons for doing so: first, this is an AWK tutorial and that's
the way to do such kind of things in tutorials.
The second reason is to make the program better, faster and functional;
supplying the <i>check numbers</i> to a separate program to produce a script
and then run another program using that script may be an interesting approach,
but it would be much better to run a single command.
</p>

<p>
We choose to specify <i>check numbers</i> in the command line, let's say
as a comma separated list of numbers.
We also choose to use some kind of looping in the core logic of counting
multiples, because to carry out a series of similar checks, one after the other,
may be the right thing to do as a human, but for the computer that's
a totally wrong way to do it.
</p>

<?php HTML::code_quote("awk/countbetter.awk"); ?>

<p>
To run the above AWK script for 2, 3, 5 and 7:
</p>

<p>
<pre>
awk -v check="2,3,5,7" -f countbetter.awk <i>input_file</i>
</pre>
</p>

<a name="NVG_regexp"></a>
<h2>Regular Expressions</h2>

<p>
Regular Expressions play major role in AWK.
But what are Regular Expressions (REs)?
Regular expressions originated in 1951, when Stephen Cole Kleene described
<i>regular languages</i> using his mathematical notation called <i>regular sets</i>.
Nowdays REs are used mainly for describing patterns to be matched by computer
programs such as text editors, interpreters, compilers etc.
In conclusion, we can define Regular Expressions to be a convenient way
to describe text patterns.
</p>

<p>
In order to use REs one must know the relative notation, e.g. the
dot symbol (<code>.</code>) means <i>any single character</i>, so the
RE <code>Sm.th</code> matches <code><i>Smith</i></code>, <code><i>Smyth</i></code> and <code><i>Smath</i></code>.
Characters enclosed in brackets mean <i>any of the enclosed characters</i>,
so RE <code>[sS]m.th</code> matches <code><i>Smith</i></code> and <code><i>Smyth</i></code>, but also
matches <code><i>smith</i></code>, <code><i>smyth</i></code>, <code><i>sm@th</i></code> and <code><i>sm!th</i></code>.
Brackets can be used with character ranges, such as <code>[a&#x2011;z]</code> to
match any lower english letter, or <code>[a&#x2011;zA&#x2011;Z]</code> to match any
english letter (lower or capital). In order to match numerical digits,
<code>[0&#x2011;9]</code> can be used.
There also exists a <i>negation</i> character (<code>^</code>) to be used in brackets,
so <code>[^a&#x2011;zA&#x2011;Z]</code> can be used to match any character <i>except</i>
english letters.
</p>

<p>
The asterisk (<code>*</code>) is used to denote repetition, so RE
<code>[0&#x2011;9]*</code> means a string of numerical digits (even none),
while the plus symbol (<code>+</code>) has the same effect except
that there must be at least one item to match.
RE <code>[ABC][0&#x2011;9]*[XYZ]</code> matches <code><i>A12X</i></code>,
<code><i>B11Z</i></code> and <code><i>CY</i></code>, while RE <code>[ABC][0&#x2011;9]+[XYZ]</code>
does not match <code><i>CY</i></code> because there must be at least one numerical digit
between <code>[ABC]</code> and <code>[XYZ]</code> patterns.
The </code>\{</code> and <code>\}</code> sequences can be used to
denote specific number of repetitions, e.g.
<code>[ABC][0&#x2011;9]\{3\}[XYZ]</code> means <i>letter A, B or C followed by three
numerical digits, followed by letter X, Y or Z</i>,
<code>[ABC][0&#x2011;9]\{3,6\}[XYZ]</code> means <i>letter A, B or C followed by three,
four, five or six numerical digits, followed by letter X, Y or Z</i>, while
<code>[ABC][0&#x2011;9]\{3,\}[XYZ]</code> means <i>letter A, B or C followed by at least three
numerical digits, followed by letter X, Y or Z</i>, while
</p>

<p>
Symbols <code>^</code> and <code>$</code> are called <i>anchor</i> characters and denote
the <i>start</i> and the <i>end</i> of the checked item respectively.
The RE <code>[ABC][0&#x2011;9]+</code>
matches <code><i>panos<strong>A1962</strong>xyz</i></code> but <code>^[ABC][0&#x2011;9]+</code> matches
<code><i><strong>A1962</strong>xyz</i></code> but not <code><i>panosA1962xyz</i></code>.
Similarily, <code>[ABC][0&#x2011;9]*$</code> matches <code><i>panos<strong>A1962</strong></i></code>, but not
<code><i>panos1962A1962xyz</i></code>.
Parentheses can be used in order to clarify ambiguities, while the pipe symbol
<code>|</code> means <i>or</i>, so if we want to match names <i>panos</i> or
<i>maria</i> followed by four numerical digits, we can use <code>(panos|maria)[0&#x2011;9]\{4\}</code>.
REs can be very confusing, but when properly used they can save you a lot lines of code.
</p>

<p>
AWK talks the language of REs out of the box, e.g. <code>FS</code> and <code>RS</code>
can be set to REs instead of single characters or literal strings. For example,
setting <code>FS</code> to <code>[^a&#x2011;zA&#x2011;Z0&#x2011;9]+</code> means that
any sequence of non&#x2011;letter or non&#x2011;digit characters will be considered as a field
separator.
Another use of REs in AWK is the notation <code>/<i>RE</i>/</code> which can be used
to match lines, while the tilde symbol (<code>~</code>) is called the <i>match</i>
operator and can be used for pattern matching checks on any textual entity.
The following awk script prints lines that contain strings in the form of
<code>[ABC][0&#x2011;9]+[XYZ]</code> and lines where the second field is <code>Smith</code>
or <code>Smyth</code>:
</p>

<p>
<pre>
/[ABC][0&#x2011;9]+[XYZ]/ {
	print $0
	next
}

$2 ~ /^Sm[iy]th$/ {
	print $0
	next
}
</pre>
</p>

<a name="NVG_arrays"></a>
<h2>Arrays</h2>

<p>
Arrays in AWK are associative lists, that is named lists of key/value pairs.
To iterate over the array elements one can use a special kind of a
<code>for</code> loop:
</p>

<p>
<pre>
for (<i>variable_name</i> in <i>array_name</i>) {
	<i>do something interesting here with each
	one of the array indices and elements</i>
}
</pre>
</p>

e.g.

<p>
<pre>
for (i in person) {
	print i, person[i]
}
</pre>
</p>

<p>
The above AWK code snippet uses variable <code>i</code> to iterate all of the
<code>person</code> array elements, printing each index along with the corresponding
<code>person</code> array value.
</p>

<p>
Of course, arrays can have sequential numeric indices, e.g. 1, 2, 3,…
It's clear that such arrays can be iterated using a classical
<code>for</code> syntax:
</p>

<p>
<pre>
for (i = 1; i < 100; i++) {
	print item[i]
}
</pre>
</p>

<a name="NVG_functions"></a>
<h2>Functions</h2>

<p>
Functions play a major role in every language and AWK is not an exception.
Like what's happening in most of the programming languages, there exist two kinds of functions in AWK:
bult&#x2011;in functions and user defined functions.
</p>

<a name="NVG_builtinFunctions"></a>
<h3>Built&#8209;in functions</h3>

<p>
Built&#8209;in functions include most of the classical functions found in <i>standard</i> function
libraries of most languages.
Measuring the length of a string, calcluating the square root of a number, file open/close,
get the system time etc, are usually tasks covered by built&#8209;in functions.
For a complete (and updated) list of AWK's built&#8209;in functions,
one can always refer to the relevant manual pages starting from
<a href="https://www.gnu.org/software/gawk/manual/html_node/Built_002din.html#Built_002din"
target="_blank">here</a>.
</p>

<p>
Let's see built&#8209;in functions in action.
To print statistics concerning the length of the words in a given text file,
we can use the <code>length</code> built&#8209;in function:
</p>

<?php HTML::code_quote("awk/wlstats.awk"); ?>

<p>
The script above is straightforward, but let's trace it by hand given the
following input lines:
</p>

<p>
<pre class="output">
The quick brown fox jumps over the lazy dog.
The five boxing wizards jump quickly.
</pre>
</p>

<p>
The first field of the first line is "The" with a length of 3 letters,
so <code>count[3]</code> will be increased and because it was unset it will be set to 1.
The second field of the first line is "quick" with a length of 5 letters,
so <code>count[5]</code> will be set to 1 for the same reasons.
The third field is "brown", again with a length of 5 letters,
so <code>count[5]</code> will be increased to 2.
Next field is "fox" with a length of 3 letters,
so <code>count[3]</code> will be increased to 2.
Reaching the last field of the first input line, we may notice a problem.
</p>

<p>
Actually, the last field will not be set to the word "dog" as someone would expect,
but rather to the string "dog." with the dot included.
The correspondig length will be 4 instead of 3 and our statistics will be proven
faulty;
this is absolutely normal, because AWK splits lines using sequences of "white"
characters (spaces and tabs) as the default field separator.
</p>

<p>
It's easy for AWK to change the default field separator: just set the
<code>FS</code> built&#8209;in variable to a single character or to a regular expression.
The hard part is how to choose the appropriate field separator in order to
split lines into real life words.
We can set the field separator to a sequence of non alphanumeric characters in
the <code>BEGIN</code> section:
<p>

<p>
<pre>
BEGIN {
	FS = "[^[:alnum:]]+"
}
...
</pre>
</p>

<p>
Now we are getting better results, but there showed up a zero length counter!
Don't panic, it's absolutely normal to have zero length "words" after changing
the field separator.
Actually, whenever a non alphanumeric character exists at the end of a line
causes AWK to "see" an empty field <i>after</i> the separator and <i>before</i> the end of the line.
To make that clear let's check the following line using "," as the field separator:
</p>

<p>
<pre>
panos,arnold,tim
</pre>
</p>

<p>
There are clearly three fields in the line above, namely "panos", "arnold" and "tim".
Now let's split the following line with the same field separator:
</p>

<p>
<pre>
,panos,arnold,tim,
</pre>
</p>

<p>
Now there exist two extra (empty) fields, one before "panos" and the other after "tim".
In our case the last fields of the first line are "lazy" (separated with spaces),
"dog" (separated with a space to the left and a dot to the right).
But there exists an empty field <i>after</i> the dot too;
this is where the zero length word comes into play for the first time.
The same is true for the second line which also ends with a dot.
</p>

<p>
It was easy to change the field separator in order to split lines in real life words.
It's also easy to avoid printing the zero length stats just by <i>deleting</i>
the zero indexed element before printing the array:
</p>

<p>
<pre>
...
END {
	<strong>delete count[0]</strong>

	for (len in count)
	print len, count[len]
}
</pre>
</p>

<p>
For a complete list of built&#8209;in functions refer to the
<a href="https://www.gnu.org/software/gawk/manual/html_node/Built_002din.html#Built_002din"
target="bultin">relevant chapter</a> of the AWK's reference manual.
</p>

<a name="NVG_userFunctions"></a>
<h3>User defined functions</h3>

<p>
Built&#8209;in functions cover many needs, but most of the time we need to write our
own functions for our programs to be clean and modular.
Use of functions helps the most in avoiding code repetition,
decreases program size and reduces global objects.
Function definition in AWK is starightforward, just write the keyword <code>function</code>,
followed by the function's name, followed by the function's parameters enclosed in parantheses,
followed by the function's body enclosed in curly brackets:
</p>

<p>
<pre>
function mean_calc(l, a) {
	...
}
</pre>
</p>

<p>
In the above AWK code snippet we define function <code>calc_mean</code> which accepts two
arguments (<code>l</code> and <code>a</code>).
Functions can return a value to the caller, but it's not mandatory to return something
(subroutine).
</p>

<p>
<pre><?php require "awk/mean_wrong.awk"; ?></pre>
</p>

<p>
The above AWK script will read lines where each line contains groups of comma separated
numerical values. For every input line we print the mean values of each group.
Of course there is a fatal error in this script as variable <code>i</code> is defined
and used as global, but later on we use the same variable in the user defined function
<code>mean_calc</code>.
In order to get things right we must use another variable inside the function, let's say
<code>j</code>, but it's clear that this is not safe; we cannot keep track of global
variables and change variable names whenever a conflict appears.
Then, how can we eliminate global variables in an AWK script?
</p>

<a name="NVG_localVariables"></a>
<h3>Local variables</h3>

<p>
The only variables with <i>local</i> scope in AWK are function parameters which
have function scope. In other words, the parameters passed to an AWK function are local
to the function and even if there exist other variables with the same name outside the function,
these variables don't relate by any means with each other.
</p>

<p>
<pre><?php require "awk/mean_correct.awk"; ?></pre>
</p>

<p>
<a href="awk/mean_correct.awk" download="mean.awk">Download</a>
</p>

<p>
As you can see, we've added some function parameters to <code>mean_calc</code> function.
Function parameters have local scope, so variables <code>n</code>, <code>a</code>,
<code>tot</code> and <code>i</code> become <i>local</i> to the <code>mean_calc</code> function.
Of course we don't pass these parameters when calling the function; we just pass the
comma separated list of numerical values (this is just a string) as the <code>l</code>
parameter and ommit the remaining arguments.
This is a clever trick to define <i>local</i> variables with function scope in AWK,
but always there is a price to pay for that kind of elegance and simplicity:
AWK functions cannot accept variable number of (normal) arguments.
</p>
