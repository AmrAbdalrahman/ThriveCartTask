<?php

namespace App\Enums;

enum HttpStatusCodeEnum: int
{
    case OK = 200;
    case CREATED = 201;
    case UNAUTHORIZED = 401;
    case NOT_FOUND = 404;
    case FORBIDDEN = 403;
    case BAD_REQUEST = 400;
    case UNPROCESSABLE_ENTITY = 422;
    case CONFLICT = 409;
    case INTERNAL_SERVER_ERROR = 500;
    case PERMANENT_REDIRECT = 308;
}
