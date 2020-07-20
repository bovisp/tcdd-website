<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TrainingPortalViews implements FromCollection
{
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->result;
    }
}
