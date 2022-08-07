<?php

return[
  
    'Token_Generation_Json_Key' => 'token_hash',
    'Token_Expiry' => '30', //Days
    'timeoutInMins' => 15,

    'max_no_of_loan' => 5,
    'emi_status' => [
        'Paid' => 'Paid',
        'UnPaid' => 'UnPaid'
    ],
    'loan_status' => [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Closed' => 'Closed'
    ]
];



?>