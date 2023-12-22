<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class DoctorPatientsNotMovedException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = 'Невозможно удалить, переместите пациентов врача на другого специалиста!';
}
