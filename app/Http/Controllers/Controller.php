<?php

namespace App\Http\Controllers;

use App\Traits\HasHelper;
use App\Traits\HasLogger;
use App\Traits\HasOTP;

abstract class Controller
{
    use HasHelper, HasOTP, HasLogger;
}
