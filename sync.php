<?php

# Loads all settings and dependencies
require_once(dirname(__FILE__).'/init.php');

# Creates connection to youtrack
try {
  $youtrack = new YouTrack\Connection(
      $_ENV['YT_URL'],
      $_ENV['YT_USERNAME'],
      $_ENV['YT_PASSWORD']
  );
} catch (\YouTrack\IncorrectLoginException $e) {
  echo 'Incorrect username or password. Error details: '. $e; // @todo implement logging
  exit();
} catch (Exception $e) {
  echo 'Internal error, likely the given YouTrack URL is incorrect. Error details: '. $e;
  exit();
}

# Gets all issues based on filter
$issues = $youtrack->getIssuesByFilter('#Unresolved for:me');

echo '<pre>';

# Loops through all issues retrieved
foreach ($issues as $issue) {

    $ticketPriority    = $issue->getPriority();
    $ticketIdentifier  = $issue->getId();
    $ticketTitle       = strip_tags($issue->getSummary());
    $ticketStatus      = str_replace(" ", "-", $issue->getState());
    $ticketCreator     = $issue->getReporterFullName();
    $ticketDescription = strip_tags(str_replace("\n", PHP_EOL, $issue->getDescription()));

    $tasktitle         = "#" . $ticketPriority . " | " . $ticketIdentifier . " | " . $ticketTitle . " | #". $ticketStatus;
    $taskcreator       = "Created by: ". $ticketCreator . ".";
    $taskdescription   = "Task description: ". newline() . $ticketDescription;

    # PRESENTATION ON TO THE SCREEN
    echo $tasktitle . newline() . $taskcreator . newline() . $taskdescription . newline(4);

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
    /*
      @todo Connect with wunderlist, and make it so initial description also ends up in wunderlist
      and also all the descriptions of comments that follow up after that.

      Check what are the effects of youtrack being down? Change the url to something else to see.
      Maybe copy the try catch from the git repo.

      @resources

      https://github.com/nepda/youtrack-client/tree/master/examples

      https://github.com/jeroendesloovere/wunderlist-php-api

      https://github.com/vlucas/phpdotenv

    */
}

echo '</pre>';

function newline($increment=1) {
  $newLine = '';
  for($loop = 0; $loop <= $increment; $loop++) {
    $newLine .= PHP_EOL;
  }
  return $newLine;
}

?>
-----====-----
