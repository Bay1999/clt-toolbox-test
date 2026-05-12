<?php

namespace App\Exceptions;

use Exception;

class InvalidAppFlowException extends Exception
{
    // This exception is left intentionally basic. 
    // It is meant to be caught and handled within the controller.
}
