<?php
class YoutrackHandler {

  # @object connection to Youtrack API
  private $_connection;

  public function __construct() {

    # Creates connection to youtrack
    try {
      $this->_connection = new YouTrack\Connection(
          $_ENV['YT_URL'],
          $_ENV['YT_USERNAME'],
          $_ENV['YT_PASSWORD']
      );
    } catch (\YouTrack\IncorrectLoginException $e) {
      $error = 'Incorrect YouTrack username or password. Error details: '. $e; // @todo implement logging
      Miscellaneous::log($error, 'youtrack_handler');
      exit();
    } catch (Exception $e) {
      $error = 'Internal error, likely the given YouTrack URL is incorrect. Error details: '. $e;
      Miscellaneous::log($error, 'youtrack_handler');
      exit();
    }

  }

  /**
  * Retrieves all unresolved tickets based on user defined in .env file.
  * Then compares which tickets exist on Wunderlist by looking through the array provided
  * in the parameter.
  *
  * @param array $ticket_ids_on_wunderlist - existing ticket ids on wunderlist Example: BE-109, AS-12, etc..
  */
  public function getAllUnSyncedTickets($ticket_ids_on_wunderlist) {

    # Gets all issues based on filter
    $issues = $this->_connection->getIssuesByFilter('#Unresolved for:me');

    $unsynced_tickets_from_youtrack = array();
    $counter = 0;

    # Loops through all issues retrieved
    foreach ($issues as $issue) {

        // print_r($issue); exit();

        # Ticket details
        $ticketPriority    = $issue->getPriority();
        $ticketIdentifier  = strtoupper(str_replace('#', '', $issue->getId()));
        $ticketTitle       = strip_tags($issue->getSummary());
        $ticketStatus      = str_replace(" ", "-", $issue->getState());

        # Ticket details as one string
        $tasktitle         = "#" . $ticketPriority . " | " . $ticketIdentifier . " | " . $ticketTitle . " | #". $ticketStatus;

        $ticketCreator     = $issue->getReporterFullName();
        $ticketDescription = strip_tags(str_replace("\n", PHP_EOL, $issue->getDescription()));
        $taskCreator       = "Created by: ". PHP_EOL . $ticketCreator . "." . PHP_EOL;
        $taskDescription   = "Task description: ". PHP_EOL . $ticketDescription;

        $comment = $taskCreator . PHP_EOL . $taskDescription;

        # Do not process already existing tickets on wunderlist
        if(in_array($ticketIdentifier, $ticket_ids_on_wunderlist))
          continue;

        # Adds ticket to variable
        $unsynced_tickets_from_youtrack[$counter] = array(
          'taskTitle'   => $tasktitle,
          'taskComment' => $comment
        );
        $counter++;

    } // end of foreach

    return $unsynced_tickets_from_youtrack;

  } //end of getAllUnSyncedTickets()

}
