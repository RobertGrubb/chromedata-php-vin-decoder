<?php

/**
 * ChromeData VIN Decoder
 * This is an example of decoding a vehicle's
 * VIN and getting the vehicle information through
 * ChromeData using a PHP OOP concept.
 *
 * @developer Matt Grubb
 **/
class ChromeData
{
    // Soap URL
    private $_soapUrl     = 'http://services.chromedata.com/Description/7a';

    // ChromeData user info:
    private $_userNumber  = '<CHROMEDATA_USER_NUMBER_HERE>';
    private $_userSecret  = '<CHROMEDATA_USER_SECRET_HERE>';

    // CURL Settings
    private $_curlTimeout = 60;
    private $_curlHeaders = array(
        'SOAPAction: ',
        'MIME-Version: 1.0',
        'Content-type: text/xml; charset=utf-8',
    );
    private $_curlReturnTransfer = 1;

    // Error Handling
    public $error = false; //Should return in array
    private $_errors = array(
        'VIN_UNDEFINED' => 'VIN Number must not be null',
        'BAD_RESPONSE'  => 'Response returned does not appear to be a SOAP document.',
    );

    /**
     * ****************************************.
     *
     * @method getVehicle
     * @desc Will build the SOAP Request that
     *       we will send to ChromeData.
     *
     * @param int    $vin
     * @param string $format
     * Available Formats:
     *   - XML Smart Object (default)
     *   - json
     *   - array
     *   - object
     * ****************************************
     **/
    public function getVehicle($vin = null, $format = null)
    {

        //If the VIN is null, then it wasn't set. Error out.
        if ($vin == null) {
            $this->error[] = $this->_errors['VIN_UNDEFINED'];

            return false;
        } else {
            // Setup request to be sent to ChromeData
            $xmlRequest = '<soapenv:Envelope
             xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
             xmlns:urn="urn:description7a.services.chrome.com">
               <soapenv:Header/>
               <soapenv:Body>
                  <urn:VehicleDescriptionRequest>
                     <urn:accountInfo
                      number="' . $this->_userNumber . '"
                      secret="' . $this->_userSecret . '"
                      country="US"
                      language="en"
                      behalfOf="?"
                     />
                     <urn:vin>'.$vin.'</urn:vin>
                  </urn:VehicleDescriptionRequest>
               </soapenv:Body>
            </soapenv:Envelope>';

            // Retrieve the data.
            $data = $this->_call($xmlRequest);

            // If _call returned false, output an error.
            if (!$data) {
                $this->error[] = $this->_errors['BAD_RESPONSE'];

                return false;
            // If all is good, return the data.
            } else {
                $formattedData = $this->_formatData($data, $format);

                return $formattedData;
            }
        }
    }

    /**
     * ****************************************.
     *
     * @method _formatData
     * @desc   Outputs data received depending
     *         on format specified.
     *
     * @param string $data
     * @param string $format
     *                       ****************************************
     **/
    private function _formatData($data, $format)
    {
        // JSON Format:
        if ($format == 'json') {
            $output = json_encode((array) $data);
        // OBJECT Format:
        } elseif ($format == 'object') {
            $output = json_decode(json_encode((array) $data));
        // ARRAY Format:
        } elseif ($format == 'array') {
            $output = json_decode(json_encode((array) $data), true);
        // XML Smart Object Format:
        } else {
            // By default, display the XML Smart Object
            $output = $data;
        }

        return $output;
    }

    /**
     * ****************************************.
     *
     * @method _call
     * @desc   Makes CURL request to SOAP URL
     *
     * @param string $xml
     *                    ****************************************
     **/
    private function _call($xml)
    {
        // Initialize curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->_curlReturnTransfer);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_curlTimeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_curlHeaders);

        // Get result and format it
        $result = curl_exec($ch);
        $start = strpos($result, '<S:Body>') + 8;
        $end = strrpos($result, '</S:Body>');

        // Check if our return data is in SOAP format.
        if (($start <= 0) || ($end <= 0)) {
            return false;
        // If it is, let's send back the data.
        } else {
            $result = substr($result, $start, $end - $start);

            // Return the data
            $data = simplexml_load_string($result);

            return $data;
        }
    }
}
