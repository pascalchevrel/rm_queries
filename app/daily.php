<?php
$tomorrow = new DateTime('tomorrow');
$day = new DateTime($firefox_versions["LAST_MERGE_DATE"]);

$bz_link = 'https://bugzilla.mozilla.org/buglist.cgi? '
. '&query_format=advanced'
. '&chfield=cf_status_firefox' . $main_nightly
. '&chfieldvalue=fixed'
. '&classification=Client%20Software'
. '&classification=Components'
. '&resolution=FIXED'
. '&order=component'
. '&f1=product'
. '&o1=notequals'
. '&v1=Testing';

echo "<ul>";
while ( $day->format('Y-m-d') !=  $tomorrow->format('Y-m-d')) {
	$d = '&chfieldfrom=' . $day->format('Y-m-d') . '&chfieldto=' . $day->format('Y-m-d');
	$title = '&title=Nightly%20Bugs%20' . $day->format('Y-m-d');
	echo '<li><a href="' . $bz_link . $d . $title . '" target="_blank" rel="noopener">' . $day->format('Y-m-d') . '</a></li>';
	$day->modify('+1 day');
}
echo "</ul>";
