<?php
namespace App\Services;

use Illuminate\Http\Request;

class UserQuery
{
    //second
    protected $safeParams = [
        'name' => ['eq'],
        'email' => ['eq'],
        'role' => ['eq'],
        'phone_number' => ['eq'],
        'gender' => ['eq'],
        'date_of_birth' => ['eq'],
        'address' => ['eq'],
    ];
    protected $columnMap = [];
    protected $operatorMap = [
        'eq' => '=',
    ];
    public function transform(Request $request)
    {
        $eloQuery = [];
        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);
            if (!isset($query)) {
                continue;
            }
            $column = $this->columnMap[$param] ?? $param;
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloQuery;
    }
}
