<?php

namespace App\Classes\Reports;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Classes\Reports\Fiscal\TrainingPortalFiscalYears;

class FormattedReportDates
{
    protected $datesArray = [];

    protected $quartersLookup = [
        [ 'calendar' => 1, 'fiscal' => 3, 'add' => 3 ],
        [ 'calendar' => 2, 'fiscal' => 4, 'add' => 4 ],
        [ 'calendar' => 3, 'fiscal' => 1, 'add' => 1 ],
        [ 'calendar' => 4, 'fiscal' => 2, 'add' => 2 ]
    ];

    protected $fiscalArr, $quartersArr, $type;

    public function __construct()
    {
        if (!request('fiscal') && !request('quarters')) {
            $this->fiscalArr = cache()->get('tp-fiscalyears');

            $this->quartersArr = [];

            $this->type = cache()->get('metric-type');
        } else {
            $this->fiscalArr = request('fiscal');

            $this->quartersArr = request('quarters');

            $this->type = request('type');
        }
    }

    public function getTimestampArray($hasFiscal, $hasQuarters)
    {
        if ($hasFiscal && $hasQuarters) {
            return $this->calculate($this->fiscalArr, $this->quartersArr);
        } elseif ($hasFiscal && !$hasQuarters) {
            return $this->calculate($this->fiscalArr, [1, 2, 3, 4]);
        } elseif (!$hasFiscal && $hasQuarters) {
            $class = 'App\\Classes\\Reports\\Fiscal\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $this->type))) . 'FiscalYears';
            $years = (new $class)->get();

            return $this->calculate($years, $this->quartersArr);
        } else {
            return [];
        }
    }

    protected function calculate($years, $quarters)
    {
        foreach ($years as $year) {
            foreach($quarters as $quarter) {
                $start = Carbon::create($year)
                    ->addQuarter(
                        head(Arr::where($this->quartersLookup, function ($value, $key) use ($quarter) {
                            return $value['fiscal'] === $quarter;
                        }))['add']
                    );

                $end = Carbon::create($year)
                    ->addQuarter(
                        head(Arr::where($this->quartersLookup, function ($value, $key) use ($quarter) {
                            return $value['fiscal'] === $quarter;
                        }))['add'] + 1
                    )->subSecond();

                $this->datesArray[] = [
                    'year' => $year,
                    'quarter' => $quarter,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'timestampStart' => $start->timestamp,
                    'timestampEnd' => $end->timestamp
                ];
            }
        }

        return $this->datesArray;
    }
}