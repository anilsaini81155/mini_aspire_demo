<?php

namespace App\Contracts;

interface AuthCheck{

    public function checkTokenAuthenticity($rqstData);
}

?>