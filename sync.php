<?php

# PHP Settings
ini_set('display_errors', 'On');
error_reporting(E_ALL);
date_default_timezone_set('Europe/Amsterdam');

# Import dependencies
require_once(dirname(__FILE__)."/vendor/autoload.php");

# Youtrack credentials
define('YOUTRACK_URL', 'https://youtrack.taurusmedia.nl/');
define('YOUTRACK_USERNAME', 'samih');
define('YOUTRACK_PASSWORD', 'xoyLA3Q(jysjt@ied84v');

# Creates connection to youtrack
$youtrack = new YouTrack\Connection(
    YOUTRACK_URL,
    YOUTRACK_USERNAME,
    YOUTRACK_PASSWORD
);

// make sure, this exists!
$issues = $youtrack->getIssuesByFilter('#Unresolved for:me');
foreach ($issues as $issue) {
    echo "<br />" . " #" . $issue->getPriority() . " | " . $issue->getId() . " | " . $issue->getSummary() . PHP_EOL;
    echo "<br />" . $issue->getReporterFullName() . ": ". str_replace("\n", "<br />", $issue->getDescription()) . "<br /><br />";

    /*
    [attributes:protected] => Array
            (
                [id] => LEYLINES-122
                [entityId] => 82-2735
                [projectShortName] => LEYLINES
                [numberInProject] => 122
                [summary] => OHHG - Direct Debit (automatische incasso) isn't working as expected
                [description] => Direct Debit (automatische incasso) isn't working as expected. Please test it and fix it.
                [created] => 1523006524987
                [updated] => 1524236351619
                [updaterName] => Samih
                [updaterFullName] => Samih Soylu
                [reporterName] => roland
                [reporterFullName] => Roland Haselager
                [commentsCount] => 11
                [votes] => 0
                [Priority] => Normal
                [Type] => Bug
                [State] => Waiting
                [Assignee] => Samih
                [Estimation] => 150
                [Spent time] => 118
                [%Done] => 0%
            )

    */
    //print_r($issue);

    /*
      @todo Connect with wunderlist, and make it so initial description also ends up in wunderlist
      and also all the descriptions of comments that follow up after that.

      @resources

      https://github.com/nepda/youtrack-client/tree/master/examples

      https://github.com/jeroendesloovere/wunderlist-php-api
      
    */
}
?>
<style>
  * { font-family:sans-serif; }
</style>
