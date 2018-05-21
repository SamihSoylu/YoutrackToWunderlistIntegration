<?php
class Miscellaneous {

  /**
  * Saves an error message to file. Creates a .log file if it does not exist
  *
  * @param string $errorLocation - Name of the file where error has occured
  * @param string $errorMessage - Message to put in to file.
  * @return void
  */
  public static function Log($errorMessage, $filename='error') {

    # @todo: to further improve this function, implement a base path.

    # Makes logging compatible with arrays.
    if(is_array($errorMessage)) {

      # Does not log if there is nothing to log.
      if(sizeof($errorMessage) < 1) {
        return;
      }

      # Turns array to string.
      $errorMessage = print_r($errorMessage, true);

    }

    # Where the error will be saved
    $errorFile = dirname(__FILE__).'/../logs/' . $filename . '.log';

    # Before the error message, date is displayed
    $date_of_error = "[".date('d M Y H:i:s')."] :: ";

    # Creates log if it does not exist
    if(!file_exists($errorFile)) {
      $openedFile = fopen($errorFile, "w");
      fclose($openedFile);
    }

    # Reads error log
    $errorFileData = file_get_contents($errorFile);

    # Amendments to variable
    $errorFileData .= $date_of_error . $errorMessage . "\n";

    # Writes to file
    file_put_contents($errorFile, $errorFileData);

  }
}
