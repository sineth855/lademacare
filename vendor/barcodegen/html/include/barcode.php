<?php
// We could be more dynamic and open each file to find its name
// But that would hinder the performance
$supportedBarcodes = array(
    // 1D
    'barcodegen/html/BCGcodabar.php' => 'Codabar',
    'barcodegen/html/BCGcode11.php' => 'Code 11',
    'barcodegen/html/BCGcode39.php' => 'Code 39',
    'barcodegen/html/BCGcode39extended.php' => 'Code 39 Extended',
    'barcodegen/html/BCGcode93.php' => 'Code 93',
    'barcodegen/html/BCGcode128.php' => 'Code 128',
    'barcodegen/html/BCGean8.php' => 'EAN-8',
    'barcodegen/html/BCGean13.php' => 'EAN-13',
    'barcodegen/html/BCGgs1128.php' => 'GS1-128 (EAN-128)',
    'barcodegen/html/BCGisbn.php' => 'ISBN',
    'barcodegen/html/BCGi25.php' => 'Interleaved 2 of 5',
    'barcodegen/html/BCGs25.php' => 'Standard 2 of 5',
    'barcodegen/html/BCGmsi.php' => 'MSI Plessey',
    'barcodegen/html/BCGupca.php' => 'UPC-A',
    'barcodegen/html/BCGupce.php' => 'UPC-E',
    'barcodegen/html/BCGupcext2.php' => 'UPC Extenstion 2 Digits',
    'barcodegen/html/BCGupcext5.php' => 'UPC Extenstion 5 Digits',
    'barcodegen/html/BCGpostnet.php' => 'Postnet',
    'barcodegen/html/BCGintelligentmail.php' => 'Intelligent Mail',
    'barcodegen/html/BCGothercode.php' => 'Other Barcode',

    // Databar
    'barcodegen/html/BCGdatabarexpanded.php' => 'Databar Expanded',
    'barcodegen/html/BCGdatabarlimited.php' => 'Databar Limited',
    'barcodegen/html/BCGdatabaromni.php' => 'Databar Omni',

    // 2D
    'barcodegen/html/BCGaztec.php' => 'Aztec',
    'barcodegen/html/BCGdatamatrix.php' => 'DataMatrix',
    'barcodegen/html/BCGmaxicode.php' => 'MaxiCode',
    'barcodegen/html/BCGpdf417.php' => 'PDF417',
    'barcodegen/html/BCGqrcode.php' => 'QRCode'
);
?>