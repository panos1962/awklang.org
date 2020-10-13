<a name="NVG_top"></a>
<h1>AWK conjuncted software</h1>

<p>
There exist many software tools that a programmer can use in conjunction
with AWK to process data the easy way.
The truth is that almost any program designed to be used as a filter,
can be used in conjunction with AWK.
</p>

<a name="NVG_firewall"></a>
<h2>Processing firewall data</h2>

<p>
Let's say we want to process firewall output consisting of two column
tab separated data, namely IP adresses and visited domains:
</p>

<p>
<pre class="output">
<?php system("head -10 data/fdata"); ?>
...
</pre>
</p>

<p>
<a href="data/fdata" download="fdata">Download</a>
</p>

<p>
We want to produce some useful reports based on that kind of data.
Some of these reports may be:
</p>

<li>
Print 10 most visited domains
</li>

<li>
Print domains visited by more than 100 different IPs
</li>

<li>
Print each domain followed by IP lists with visit counts for each IP
</li>

<a name="NVG_iptopvisit"></a>
<h3>Print 10 most visited domains</h3>

<p>
An easy way to print the 10 most visited domains is to count the visits
for each domain, then print counts sorted by count and extract the 10 top lines.
Save the following AWK script to <code>iptopvisit.awk</code> file:
</p>

<p>
<pre>
<?php require "awk/iptopvisit.awk"; ?>
</pre>
</p>

<p>
<a href="awk/iptopvisit.awk" download="iptopvisit.awk">Download</a>
</p>

<p>
Assuming that the firewall data are stored in <code>fdata</code> file,
run the report with:
</p>

<p>
<pre>
awk -f iptopvisit.awk fdata | sort -k2n | tail -10
</pre>
</p>

<p>
AWK prints unordered domain visit counts, thus we must sort AWK's output
on count number and print the last 10 lines of sorted data.
In this case we used AWK in conjunction with <i>sort</i> and <i>tail</i>
standard UNIX/Linux tools.
</p>

<a name="NVG_popular"></a>
<h3>Print domains visited by more than 100 different IPs</h3>

<p>
To carry out that kind of report, we sort the firewall data by domain,
then pass sorted data to AWK and continue filtering with some other tools.
</p>

<p>
<pre>
<?php require "awk/popular.awk"; ?>
</pre>
</p>

<p>
<a href="awk/popular.awk" download="popular.awk">Download</a>
</p>

<p>
<pre>
sort -k2 fdata | awk -v min_count=100 -f popular.awk | sort -k2n -k1
</pre>
</p>

<p>
AWK used in conjunction with <i>sort</i> to process the report.
</p>

<a name="NVG_domip"></a>
<h3>Print domains followed by IP visit counts</h3>

<p>
To carry out that kind of report, we sort the firewall data by domain and IP,
then pass sorted data to AWK.
</p>

<p>
<pre>
<?php require "awk/domip.awk"; ?>
</pre>
</p>

<p>
<a href="awk/domip.awk" download="domip.awk">Download</a>
</p>

<p>
<pre>
sort -k2 -k1 fdata | awk -f domip.awk
</pre>
</p>

<p>
Once again AWK used in conjunction with <i>sort</i> to process the report.
The output of the above command may look like this:
</p>

<p>
<pre class="output">
<?php require "data/domip1.out"; ?>
</pre>
</p>

<p>
We can further process the above output adding another AWK component:
</p>

<p>
<pre>
<?php require "awk/domipcut.awk"; ?>
</pre>
</p>

<p>
To print domains visited more than twice from the same IP:
</p>

<p>
<pre>
sort -k3 -k1 fdata | awk -f domip.awk | awk -v mincount=2 -f domipcut
</pre>
</p>

<p>
Possible output of the above pipeline may look like:
</p>

<p>
<pre class="output">
<?php require "data/domip2.out"; ?>
</pre>
</p>

<p>
It becomes clear that there are just a few domains visited more than twice from the same IP;
it's also clear that there are no domains visited more than 3 times from the same IP.
To achieve all of the above there were needed less than
<?php system("cat awk/domip.awk awk/domipcut.awk | wc -l"); ?>
lines of straightforword, readable, elegant AWK code!
</p>

<a name="NVG_ssconvert"></a>
<h2>ssconvert - spreadsheet file format converter</h2>

<p>
<code>ssconvert</code> is part of
<a href="http://www.gnumeric.org" target="gnumeric"><i>Gnumeric</i></a>,
the GNU spreadsheet program.
<code>ssconvert</code> converts spreadsheet data files from one format to another,
e.g. from <i>xls</i> to <i>CSV</i>, or from <i>xlsx</i> to <i>ods</i> etc.
The program can be used as a filter, that means it can read data from standard input
(<code>fd://0</code>) or write output to standard output (<code>fd://1</code>).
</p>

<p>
In order to demonstrate <code>ssconvert</code> usage, we will produce random data using
<a href="<?php print HTML::url("ppl?child"); ?>" target="ppl">PPL</a>
libray functions:
</p>

<p>
<pre>
<?php require "awk/rndgen.awk"; ?>
</pre>
</p>

<p>
<a href="awk/rndgen.awk" download="rndgen.awk">Download</a>
</p>

<p>
If the above AWK script is saved to <code>rndgen.awk</code> we can easily
construct a LibreOffice Calc file with three columns, nameley <i>login</i>,
<i>full name</i> and <i>email</i>, just by pipping AWK output to <code>ssconvert</code>:
</p>

<p>
<pre>
awk -v count=1000 -f rndgen.awk | ssconvert \
	--import-type=Gnumeric_stf:stf_csvtab fd://0 user1000.ods
</pre>
</p>

<p>
The above command creates the file <code>user1000.ods</code> in LibreOffice Calc format.
Conversely, we are able to process spreadsheet data of any known format using AWK,
just by converting the data to ascii text and passing the output to AWK.
Thus, given the LibreOffice Calc <code>user1000.ods</code> file,
the following command will print just those lines with "us" email TLD:
</p>

<p>
<pre>
ssconvert --export-type=Gnumeric_stf:stf_assistant -O 'separator="^" quote=' \
	user1000.ods fd://1 | awk -F "^" -v tld=us '$3 ~ "\\." tld "$"'
</pre>
</p>
