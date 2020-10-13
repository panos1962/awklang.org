<div style="text-align: center; vertical-align: middle;">
<?php

$html = $_POST["pokerPrinter"];

if (!$html)
print '<pre style="margin: 0px; font-size: 4.5em; line-height: 1.4em;">';

system("cd awk_script && " . $_POST["pokerCommand"]);

if (!$html)
print '</pre>';
?>
</div>
