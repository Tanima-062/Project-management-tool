<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\SummeryReportSheet;
use App\Exports\DetailReportSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportsExport implements WithMultipleSheets
{

    use Exportable;

    private $start_date;
    private $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new SummeryReportSheet;
        $sheets[1] = new DetailReportSheet($this->start_date, $this->end_date);

        return $sheets;
    }
}
