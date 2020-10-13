<a name="NVG_top"></a>
<h1>AWK by example</h1>

<p>
Perhaps, the easiest way to learn AWK is by reading AWK code written
by expert programmers.
Most of AWK programs are simple and easy to read, even for the newbies.
There are no heavy frameworks to learn, nor difficult APIs to grasp
for understanding existing AWK programs written by experienceed programmers.
In the following pages you can read some interesting AWK programs and
after a while you may find yourself writing your own AWK programs.
</p>

<a name="NVG_awkscript"></a>
<h2>AWK scripting</h2>

<p>
After more than 30 years of successful career,
AWK has been proved to be one of the most frequently and heavily used
programs, especially on UNIX/Linux systems, either as an everyday tool
or embedded in shell scripts or other programs.
Every dignified developer or administrator knows (or must know) how to use AWK,
though not all of AWK users are AWK experts.
The truth is that one does not have to be an AWK expert to make productive use of AWK program.
For example, a mail server administrator can produce almost every kind of report
on mail system logs without AWK senior level expertise needed;
just a little AWK knowledge suffices to write useful programs.
</p>

<p>
But why AWK is so easy to grasp where other similar programs fail to do so?
That's an interesting question with a more interesting answer.
AWK is written by three of the most brilliant computer scientists
of their times, nameley Brian Kernighan, Alfred Aho and Peter Winberger.
AWK was not the first neither by all means the only program developed by these three people;
on the contrary, before doing so they had already developed a vast amount of
the most difficult software, from compilers and device drivers to operating system kernels,
leading software engineering to the modern era.
</p>

<p>
Through their tremendous experience in software engineering,
AWK creators knew that to <i>make it work you keep it simple</i>.
They also knew that <i>primature optimisation is the root of all evil</i>,
as has been said by Donald Knuth.
So it's a clear fact that, Kernighan, Aho and Winberger had in their disposal
deep knowledge on almost every aspect of computer science
when the idea of a multi purpose programmable pattern/action software tool led them to AWK.
</p>

<p>
On the other hand, the designers of AWK knew better than anybody else what to expect
from AWK, because they developed AWK to be used for their own needs and purposes and not
to cover universal hypothetical needs.
Since then, any missing feature has been added when needed, leading from <code>awk</code>
to <code>nawk</code> and eventually to <code>gawk</code>,
which is the gorilla of the various AWK implementations.
</p>

<a name="NVG_shell"></a>
<h2>AWK in shell scripting</h2>

<p>
AWK has been used in shell scripting from the very beginning of its existence in the late 1970s.
Almost every UNIX/Linux shell script invloves <code>awk</code> calls in the code, one or more times.
There is no magic about why <code>awk</code> is so heavily used in shell scripting;
the fact that people created <code>awk</code> did not try to construct the super magic tool to
handle every kind of need in computer science, but just tried to make their own lifes easier
in everyday hacking, is the answer to that question.
</p>

<p>
Aho, Weinberger and Kernighan were three of the best programmers of their times,
so they knew exactly how to devide and conquere, that means they knew
how to split huge tasks to smaller ones in order to carry out the most
difficult computer programs by designing robust software in small and
manageable pieces, most of them carrying out the same kind o job repeatedly again and again.
These small sofware pieces are named software tools, with <code>awk</code> being one of the
most frequently used.
Other useful software tools are <code>sort</code>, <code>join</code>, <code>sed</code> etc,
but only few of them are programmable like <code>awk</code> does.
Anyway, that's enough talking, let's dive in easy shell scripting with <code>awk</code> assistance.
</p>

<a name="NVG_procrustes"></a>
<h3>procrustes.sh</h3>

<p>
Our first shell script, <code>procrustes.sh</code>, is about filtering input data according to
the value of one of the input columns.
Input is checked against <i>minimum</i> and <i>maximum</i> values,
and ony lines between those two limits are printed.
</p>

<p>
We use options <code>-m</code> and <code>-M</code> to specify min and max,
while option <code>-c</code> is used to specify the column to check (default 1).
If we like to use separators other than spaces and tabs, then we can use
<code>-t</code> option to specify another separator.
</p>

<p>
<pre><?php require "shell_script/procrustes.sh"; ?></pre>
</p>

<p>
<a href="shell_script/procrustes.sh" download="procrustes">Download</a>
</p>

<a name="NVG_cstats"></a>
<h3>cstats.sh</h3>

<p>
Our next script will print character statistics of input data.
This shell script will not take any options, but we are still using
the same overall design.
One of the most interesting parts of the awk script inside <code>cstats.sh</code>
is the input field separators which is set to an empty string.
That means that every single input character will be counted as a field of the
input line.
</p>

<p>
<pre><?php require "shell_script/cstats.sh"; ?></pre>
</p>

<p>
<a href="shell_script/cstats.sh" download="cstats.sh">Download</a>
</p>

<a name="NVG_cards"></a>
<h2>Playing cards with AWK</h2>

<p>
Our next AWK script creates a 52&#x2011;card deck, then shuffles and deals cards for a poker game.
We use the symbols <code>C</code>, <code>S</code>, <code>D</code> and <code>H</code>
for the <i>clubs</i>, <i>spades</i>, <i>diamonds</i> and <i>hearts</i> suits.
We also use the digits <code>2</code> through <code>9</code> for the ranks of the
corresponding cards. We use letters <code>T</code>, <code>J</code>, <code>Q</code>,
<code>K</code> and <code>A</code> for the <i>10s</i>, <i>jacks</i>, <i>queens</i>,
<i>kings</i> and <i>aces</i> respectively.
</p>

<p>
AWK is not an object oriented programming language, but we can simulate objects
and methods using good procedural programming techniques.
We can represent each card by a two letter string in the form of "<code>SR</code>",
where "<code>S</code>" is the suit and "<code>R</code>" is the rank,
e.g. "<code>S7</code>" is the Seven of Spades, "<code>HA</code>" is the Ace of Hearts
and "<code>DT</code>" is the Ten of Diamonds.
</p>

<p>
More, we can represent a set of cards as an AWK array. It's good to know how many cards
are contained in such a set, so we choose to store the cards' count in the 0&#x2011;indexed
array element, while cards are stored to the elements indexed from 1, e.g. the
cards Seven of Spades, Ace of Hearts, Queen of Clubs and Ten of Diamonds will be
indexed as: [0]4 meaning that there are four cards in the set, [1]S7, [2]HA, [3]CQ, [4]DT.
Using the same technique we can represent a whole deck with an array of 53 elements,
where 0&#x2011;indexed element will contain the number of 52.
</p>

<p>
<pre><?php print htmlspecialchars(file_get_contents("awk_script/poker.awk")); ?></pre>
</p>

<p>
<a href="awk_script/poker.awk" download="poker.awk">Download</a>
</p>

<p>
After specifying the players' count and the type of hands representation,
click the [Run] button to run the AWK command and get a new set of random
5&#x2011;card hands on&#x2011;line.
</p>

<form action="poker.php" method="post" target="pokerFrame">
<input type="text" name="pokerCommand" readonly>
<br>
Players
<input type="number" name="pokerPlayers" placeholder="Players" min="1" max="10" value="4">
<input type="radio" name="pokerPrinter" value="0" checked>Text
<input type="radio" name="pokerPrinter" value="1">HTML
<input type="submit" name="pokerRun" value="Run">
</form>
<iframe name="pokerFrame"></iframe>
