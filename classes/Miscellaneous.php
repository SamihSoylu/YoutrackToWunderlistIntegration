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

    # Where the error will be saved
    $errorFile = 'logs/' . $filename . '.log';

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
