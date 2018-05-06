<?php

use JohnRivs\Wunderlist\Wunderlist;

class WunderlistHandler {

  # @object API connection to wunderlist
  private $_connection;

  public function __construct() {
      $this->_connection = new Wunderlist(
        $_ENV['WL_CLIENT_ID'],
        $_ENV['WL_CLIENT_SECRET'],
        $_ENV['WL_CLIENT_ACCESSTOKEN']
      );
  }

  /**
  * Using the provided $title string this function connects to Wunderlist
  * and filters out the specific listing. Then returns the ID.
  * Examples of listing names: Sam Todos, John's todos, etc..
  *
  * @param string $title - listing name that will be used to sync tasks.
  */
  public function getListId($title) {

    try {
      # Gets all lists available on the wunder list account
      $all_lists = $this->_connection->getLists();
    } catch (GuzzleHttp\Exception\ClientException $e) {
      $error = '400 Bad Request; Credentials are incorrect'.$e;
      Miscellaneous::log($error, 'wunderlist_handler');
      exit();
    }

    foreach($all_lists AS $list){

      /*

      $list variable output;

      Array
      (
          [id] => 317053230
          [title] => TM - Mats - General shared list
          [owner_type] => user
          [owner_id] => 8438300
          [list_type] => list
          [public] =>
          [revision] => 898
          [created_at] => 2017-09-04T06:49:11.966Z
          [created_by_request_id] => 498d3ffc44ddfa2f275b:475a4103-402b-4f54-ba0a-25bf4b33cbc0:28a72cf2-f4ab-4b40-81ea-3af3-2a07a243:8438300:lwdadbddd1bcbb58985e6c2419b8b34b
          [type] => list
      )

      */

      # When there is a match based on provided title, return the list id
      if($list['title'] == $title) {
        return $list['id'];
      }
    } // end of for
  } // end of getListId()

  /**
  * Connects to wunderlist and retrieves all existing tasks from
  * the specific listing. This is later passed to YoutrackHandler class
  * function getAllUnSyncedTickets() to compare and determine which tasks to add.
  *
  * @param int $list_id - identification number of the listing (relates to $this->getListId($title))
  */
  public function getAllExistingTaskIds($list_id) {

    $all_tasks = $this->_connection->getTasks(['list_id' => $list_id]);

    $existing_ticket_ids_on_wunderlist = array();

    for($i = 0; $i < sizeof($all_tasks); $i++){

      /*
        $all_tasks[$i] variable output;

        Array
        (
            [0] => #Low
            [1] => BE-109
            [2] => Integrate Qwindo module
        )
      */

      $task = $all_tasks[$i]['title'];
      $task = explode(' | ', $task);

      # @note: sometimes $task[1] does not exist when custom tasks are added.
      $existing_ticket_ids_on_wunderlist[$i] = strtoupper(str_replace('#', '', @$task[1]));

    }

    return $existing_ticket_ids_on_wunderlist;

  }

  /**
  * Sends new tasks to Wunderlist API. Tasks are provided as a string
  * inside an array, they are then looped through and created on Wunderlist.
  *
  * @param array $tasks - one or multiple strings of tasks
  * @param int $list_id - id of the listing the tassks will be added to.
  */
  public function syncTasks($tasks, $list_id) {

    $api_result = array();

    $counter = 0;
    foreach($tasks AS $task) {
      $api_result[$counter] = $this->_connection->createTask([
          'list_id' => $list_id,
          'title'   => $task['taskTitle']
      ]);

      if(isset($api_result[$counter]['id'])) {
        $this->_connection->createComment([
            'task_id' => $api_result[$counter]['id'],
            'text'    => $task['taskComment']
        ]);
      }

      $counter++;
    }

    return $api_result;

  }

}
