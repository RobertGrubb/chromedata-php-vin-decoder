### ChromeData VIN Decoder

Been looking for support regarding ChromeData's VIN Decoder? Couldn't find any good examples, or libraries that show you how to correctly retrieve data for a vehicle based on it's VIN in PHP from ChromeData? No worries, I've decided to build one.

#### Installation:

 1. Copy **ChromeData.php** from **src** to  your applications directory.
 2. Check "Example Usage" below on how to use this library.

#### Example Usage:

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

#### FAQS:

 - Can I run multiple VIN numbers at the same time? **No, not at this time with this library. However, I'm sure it would be easy to implement.**
 - Can I use this in CodeIgniter? **Yes! Simply place this in your application's libraries folder, and then load it via `$this->load->library('ChromeData');` and run the method by `$this->ChromeData->getVehicle();`**
 - Do you plan to provide support for multiple VINs? **Yes, at some point once my schedule clears.**
