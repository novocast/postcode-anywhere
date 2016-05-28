<?php

return [

    'params' => [
        'key'       => env('PCA_KEY', 'AA11-AA11-AA11-AA11'), //The key to use to authenticate to the service.
    ],

    'url' => 'https://services.postcodeanywhere.co.uk/', // API base url
       
    'services' => [
        'find' => [
            // CapturePlus Interactive Find (v2.10) - http://www.pcapredict.com/support/webservice/captureplus/interactive/find/2.1/
            'CapturePlus' => 'CapturePlus/Interactive/Find/v2.10/'
        ],

        'retrieve' => [
            // CapturePlus Interactive Retrieve (v2.10) - http://www.pcapredict.com/support/webservice/captureplus/interactive/retrieve/2.1/
            'CapturePlus' => 'CapturePlus/Interactive/Retrieve/v2.10/',
            // Extras Web Ip2Country (v1.10) - http://www.pcapredict.com/Support/WebService/Extras/Web/Ip2Country/1.1/
            'Ip2Country' => 'Extras/Web/Ip2Country/v1.10/'
        ],

        'validate' => [
            // PhoneNumberValidation Interactive Validate (v2.10) - http://www.pcapredict.com/support/webservice/phonenumbervalidation/interactive/validate/2.1/
            'PhoneNumberValidation' => 'PhoneNumberValidation/Interactive/Validate/v2.10/',
        ]
    ],

    /*
     * End points
     */
    'endpoint' => [
        'csv'           => 'csv.ws',
        'dataset'       => 'dataset.ws',
        'htmltable'     => 'htmltable.ws',
        'image'         => 'image.ws',
        'json'          => 'json.ws',
        'jsonp'         => 'json2.ws',
        'json3'         => 'json3.ws',
        'json extra'    => 'json3ex.ws',
        'tsv'           => 'tsv.ws',
        'recordset'     => 'recordset.ws',
        'pdf'           => 'pdf.ws',
        'psv'           => 'psv.ws',
        'wsdlnew'       => 'wsdlnew.ws',
        'xml'           => 'xmle.ws',
        'xmla'          => 'xmla.ws',
    ]
];
