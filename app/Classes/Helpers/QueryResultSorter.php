<?php

namespace App\Classes\Helpers;

class QueryResultSorter
{
    public static function sortBy($array, $paramCriteria)
    {
        $makeComparer = function ($criteria) {
            $comparer = function ($first, $second) use ($criteria) {
                foreach ($criteria as $key => $orderType) {
                    // normalize sort direction
                    $orderType = strtolower($orderType);
                    if ($first[$key] < $second[$key]) {
                        return $orderType === "asc" ? -1 : 1;
                    } else if ($first[$key] > $second[$key]) {
                        return $orderType === "asc" ? 1 : -1;
                    }
                }
                // all elements were equal
                return 0;
            };
            return $comparer;
        };

        $comparer = $makeComparer($paramCriteria);

        usort($array, $comparer);
        
        return $array;
    }
}