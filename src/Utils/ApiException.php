<?php

namespace R2D2\Utils;

class ApiException extends \Exception
{
    const ERROR_CODE_GENERIC = 100;
    const ERROR_CODE_INVALID_PATH = 101;
    const ERROR_CODE_CONNECTION_ERROR = 102;
    const ERROR_CODE_INVALID_RESPONSE_CODE = 103;
}
