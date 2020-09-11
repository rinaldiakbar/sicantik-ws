<?php
namespace Firebase\JWT;

class ExpiredException extends \UnexpectedValueException
{
    protected $code = 401;
}