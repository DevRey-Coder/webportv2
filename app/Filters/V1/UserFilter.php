<?php
namespace App\Filters\V1;

use App\Filters\ApiFilter;

class UserFilter extends ApiFilter
{
    //second
    protected $safeParams = [
        'id' => ['eq', 'gt', 'gte', 'lt', 'lte', 'ne'],
        'name' => ['eq', 'lk'],
        'email' => ['eq'],
        'role' => ['eq'],
        'phoneNumber' => ['eq'],
        'gender' => ['eq'],
        'dateOfBirth' => ['eq'],
        'address' => ['eq'],
    ];
    protected $columnMap = [
        'phoneNumber' => 'phone_number',
        'dateOfBirth' => 'date_of_birth',

    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
        'lk' => 'like',
    ];
}
