<?php

/*
  Sources:
    - https://github.com/nepda/youtrack-client/tree/master/examples
    - https://github.com/johnRivs/wunderlist
    - https://github.com/vlucas/phpdotenv
*/

# Loads all settings and dependencies
require_once(dirname(__FILE__).'/init.php');

/* * * * * * * * * * * * * * * * * * * * * * * * * *
* Wunderlist API & Basic Settings
* * * * * * * * * * * * * * * * * * * * * * * * * */

# Instantiates the handler class
$w = new WunderlistHandler();

# Gets the list id of specific list. Used later to get list of tasks
$wunderlist_list_id = $w->getListId($_ENV['WL_LIST']);

/* * * * * * * * * * * * * * * * * * * * * * * * * *
* YouTrack API & Basic Settings
* * * * * * * * * * * * * * * * * * * * * * * * * */

# Instantiates the handler class
$yt = new YoutrackHandler();

/* * * * * * * * * * * * * * * * * * * * * * * * * *
* Processing tickets to Wunderlist
* * * * * * * * * * * * * * * * * * * * * * * * * */

# Gets all existing tickets on wunderlist; Returns an array of ticket ids (BE-109, KHS-1, LEYLINES-75, etc..)
$all_existing_tickets_on_wunderlist = $w->getAllExistingTaskIds($wunderlist_list_id);

# An array of strings which are un-synced tickets.
#$tickets_to_be_synced = $yt->getAllUnSyncedTickets($all_existing_tickets_on_wunderlist);
#$tickets_to_be_synced = array('Test task');

# Syncs the array of tasks with wunderlist.
$result = $w->syncTasks($tickets_to_be_synced, $wunderlist_list_id);

# Logs the end result
Miscellaneous::log($result, 'sync_results');
