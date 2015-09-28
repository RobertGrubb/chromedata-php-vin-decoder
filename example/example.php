<?php

  // Require the library file:
  require_once '../src/ChromeData.php';

  // Instantiate the class
  $chromeData = new ChromeData;

  // Set a VIN to be decoded:
  $VIN = '1FMFU20586LA08243';

  // Get the Vehicle information. (Remember to specify a format)
  $vehicleInformation = $chromeData->getVehicle($VIN, 'array');

  // Check if an error occurred:
  if (!$vehicleInformation) {
      // If so, you can use the libraries error
      // variable to check and see what went wrong.
      print_r($chromeData->error);
  } else {
      //If an error didn't occurr, output the data.
      print_r($vehicleInformation);
  }
