<?php
$title = Globals::param_get("TITLE");
?>

<a name="NVG_top"></a>
<h1><span class="anchor">It's not about AWK</span></h1>

<p>
This is not an AWK help page, but rather it's a page that tries to give
an intuitive description of the <?php print $title; ?> site,
the <i>site for things related to the awk language</i>.
As you may have already noticed, most <?php print $title; ?> pages conform
to a uniform design.
Actually, most of the pages has the following areas:
<span class="spot" spot="#header"><i>header</i></span> (top),
<span class="spot" spot="#navigator"><i>navigator</i></span> (left),
<span class="spot" spot="#main"><i>main</i></span> (right) and
<span class="spot" spot="#footer"><i>footer</i></span> (bottom).
You may have also noticed that some of the pages lack of navigation area,
as there is no navigation needed on those pages.
Each one of the above major areas plays a specific role which is described
below.
</p>

<a name="NVG_header"></a>
<h2><span class="spot anchor" spot="#header">Header</span></h2>

<p>
It's the top area of every page and contains an
<span class="spot" spot="#headerIcon"><i>icon</i></span> (left), a
<span class="spot" spot="#headerTitle"><i>title</i></span> and some
<span class="spot" spot="#headerTabs .tab"><i>buttons</i></span> (right).
The icon on the left side is called the
<span class="spot" spot="#headerIcon"><i>awk icon</i></span>,
or <i>navigation toggle button</i>.
Actually, this icon is just an icon on navigator missing pages,
while it becomes a navigation toggle button on pages equipped with navigation area.
</p>

<p>
Navigation toggle button can be used to expand/shrink the navigator
as a whole with just one click.
Users, at anytime, may click any navigator button, but it's rather helpful to
full expand or totally shrink the navigator as a whole by just clicking on the awk icon.
<p>

<p>
At the right side of the header area are lying some
<span class="spot" spot="#header .tab"><i>tabs</i></span>
or <i>buttons</i>.
Most of the tabs open standard pages like <i>help</i> page, <i>FAQ</i> page etc.
Other tabs may be used to close children pages, change the theme of the page,
or return to the <i>home</i> page.
</p>

<a name="NVG_homeTab"></a>
<h3><span class="anchor">Home tab</span></h3>

<p>
By clicking the <i>Home</i> tab, home page is refreshed and focused.
That tab is present to all of the pages, even in the home page itself.
</p>

<a name="NVG_skinTab"></a>
<h3><span class="anchor">Skin tab</span></h3>

<p>
<i>Skin</i> tab can be used to toggle between a few number of preinstalled
color themes.
It's a matter of taste which theme every user uses.
Current skin is stored in a cookie, thus whenever the skin changes all pages
will open using the new skin.
Skin can be set in the URL using the <code>skin</code> variable,
e.g. <?php print $title . "?skin=<i>debug</i>"; ?>,
<?php print $title . "?skin=<i>metro</i>"; ?> etc.
Valid skin values are:<?php valid_skins(); ?>.
</p>

<a name="NVG_helpTab"></a>
<h3><span class="anchor">Help tab</span></h3>

<p>
<i>Help</i> tab displays the page you are reading now.
</p>

<a name="NVG_faqTab"></a>
<h3><span class="anchor">FAQ tab</span></h3>

<p>
<i>FAQ</i> tab displays a page of frequently asked questions and answers.

<a name="NVG_aboutTab"></a>
<h3><span class="anchor">About tab</span></h3>

<p>
<i>About</i> tab displays useful information about the
<i><?php print $title; ?></i> site.

<a name="NVG_closeTab"></a>
<h3><span class="anchor">Close tab</span></h3>

<p>
Clicking the <i>Close</i> tab causes current page to be closed.
<i>Close</i> tab is present only in pages initiated from other site pages.
</p>

<a name="NVG_navigator"></a>
<h2><span class="spot anchor" spot="#navigator">Navigator<span></h2>

<p>
Most pages are equipped with a navigator area on the left side.
Navigator area contains a set of
<span class="spot" spot="#navigator .tab"><i>navigation items</i></span>
displayed as a vertical series of <i>tabs</i> or <i>buttons</i>.
Evey navigation item may play one of the following roles:
External link, internal link (anchor), download button and sub-navigator.
Clicking on an external link item displays a new page,
while clicking on an internal link or anchor will scroll the current page
to the corresponding section.
Download buttons are used for downloading useful files such as
function libraries, programs ets.
</p>

<p>
Sub-navigators are more navigator items enclosed in parent navigator items.
Clicking on a sub-navigator item will open/close the relevant menu.
To open/close all of the navigator items to the deepest/lowest level
can be achieved by clicking the
<span class="spot" spot="#headerIcon"><i>awk icon</i></span>
at the upper left corner of the page.
Alternatively, the <strong><code>nvgexp</code></strong> URL parameter causes
navigator menus to fully expand on page load. Try this:
</p>

<pre>
<a href="<?php print HTML::url("?nvgexp"); ?>" target="_blank"><?php
	print Globals::param_get("TITLE"); ?>?nvgexp</a>
</pre>

<p>
Right next to the navigator area exists a
<span class="spot" spot="#sliderColumn"><i>slider handler</i></span>
which can be used to change the width of both the navigator and the main area
by dragging the slider horizontally.
</p>

<a name="NVG_main"></a>
<h2><span class="spot anchor" spot="#main">Main area</span></h2>

<p>
In the right side of the page lives the most significant part of the page,
called the <i>main</i> area.
Main content of the page is displayed in that area.
Content may contain
<span class="spot" spot=".anchor"><i>anchors</i></span>
which can be used to navigate up or down;
it's likely that the anchors have correspondig navigating items in the
navigation area, either as first level tabs, or enclosed in higher level
tabs (menus).
</p>

<a name="NVG_footer"></a>
<h2><span class="spot anchor" spot="#footer">Footer</span></h2>

<p>
Footer is the bottom part of every page, containing complementary site
information such as <span class="spot" spot="#licenseIcon">license</span>,
<span class="spot" spot="#footerCenter">modification date</span>,
<span class="spot" spot="#copyright">copyright</span> etc.
</p>

<?php

function valid_skins() {
	$sep = " ";
	foreach(scandir("../lib/skin") as $skin) {
		switch ($skin) {
		case ".":
		case "..":
			break;
		default:
			print $sep . "<i>" . str_replace(".css", "", $skin) . "</i>";
			$sep = ", ";
			break;
		}
	}
}

?>
