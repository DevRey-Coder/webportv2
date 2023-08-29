<?php
namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
    //second
    protected $safeParams = [];
    protected $columnMap = [];
    protected $operatorMap = [];
    public function transform(Request $request)
    {
        $eloQuery = [];
        foreach ($this->safeParams as $param => $operators) {
            $query = $request->query($param);
            if (!isset($query)) {
                continue;
            }
            $column = $this->columnMap[$param] ?? $param;
            // $value = 
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloQuery;
    }
}
 