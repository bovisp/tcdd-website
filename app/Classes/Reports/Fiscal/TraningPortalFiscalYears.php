<?php

namespace App\Classes\Reports\Fiscal;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingPortalFiscalYears
{
    protected $connection = 'mysql2';

    protected $table = 'mdl_logstore_standard_log';

    public function get()
    {
        return $this->getYearsBetween(
            $this->firstRecordTime(),
            $this->lastRecordTime()
        );
    }

    public function firstRecordTime()
    {
        return DB::connection($this->connection)
            ->table($this->table)
            ->first()
            ->timecreated;
    }

    public function lastRecordTime()
    {
        return DB::connection($this->connection)
            ->table($this->table)
            ->orderBy('id', 'desc')
            ->first()
            ->timecreated;
    }

    protected static function getYearsBetween($start, $end, $fullPeriod = false)
    {
        $years = [];
        
        $current = (new Carbon($start))->startOfYear()->timestamp;

        while ($current <= $end) {
            $tempDate = $current;

            $year = new Carbon($tempDate);

            $years[] = (int) $year->format('Y');

            $current = strtotime("+1 year", $current);
        }

        if ($fullPeriod) {
            $years[] = (int) (new Carbon($end))->format('Y');
        }

        return $years;
    }
}