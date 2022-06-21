<?php

$payloads = [
    '490500002e7cf90905007d000c000f00',
    '4a0500002e7cf90905007d000e001100'
];

/**
 * Payload analysis 
 * 
 * 490500002e7cf90905007d000c000f00
 * 4905  0000  2e7cf909  0500  7d00  0c00  0f00
 * 1353  ----  167345198 5     125   12    15
 * 
 * 4a0500002e7cf90905007d000e001100
 * 4a05  0000  2e7cf909  0500  7d00  0e00  1100
 * 1354  ----  167345198 5     125   14    17
 * 
 * Steps needed
 *  Split string to obtain parts
 *  Convert from LE > BE
 *  Conversion from Hex > Dec
 */



/**
 * getSensorDataJSON
 *
 * Returns a JSON format of the sensor data
 * @param  string $payload
 * @return string
 */ 
function getSensorDataJSON( string $payload ){
    // Create array to contain our values.
    $parts = [];

    // Extract our hex to data parts.
    $parts['message_id']    = substr( $payload, 0, 4 );
    $parts['sensor_id']     = substr( $payload, 8, 8 );
    $parts['sensor_type']   = substr( $payload, 16, 4 );
    $parts['data_type_id']  = substr( $payload, 20, 4 );
    $parts['value1']        = substr( $payload, 24, 4 );
    $parts['value2']        = substr( $payload, 28, 4 );

    // loop through each converting from LE > BE first, then returning the decimal conversion of the hex value.
    foreach( $parts as $key => $value ){
        $parts[ $key ] = hexdec( littleToBigEndian( $value ) );
    }

    return json_encode( $parts );
}



/**
 * littleToBigEndian
 *
 *  Converts LE > BE hexdecimal
 * @param  mixed $hex
 * @return string
 */
function littleToBigEndian( string $hex ) {

    // Break our hex into 2 bit chunks
    $hexChars = str_split( $hex, 2 );

    // Reverse the order
    $hexCharsReversed = array_reverse( $hexChars );

    // Join our characters back
    return implode( '', $hexCharsReversed );
}

// Go through each payload and var_dump our results;
foreach( $payloads as $payload ){
    var_dump( getSensorDataJSON( $payload ) );
}
