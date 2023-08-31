<?php
namespace App\Filters\V1;

use App\Filters\ApiFilter;

class BrandFilter extends ApiFilter
{
    //second
    protected array $safeParams = [
        'id' => ['eq', 'gt', 'gte', 'lt', 'lte', 'ne'],
        'name' => ['eq', 'lk'],
        'email' => ['eq'],
        'role' => ['eq'],
        'phoneNumber' => ['eq'],
        'gender' => ['eq'],
        'dateOfBirth' => ['eq'],
        'address' => ['eq'],
    ];
    protected array $columnMap = [
        'phoneNumber' => 'phone_number',
        'dateOfBirth' => 'date_of_birth',

    ];
    protected array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
        'lk' => 'like',
    ];
}
