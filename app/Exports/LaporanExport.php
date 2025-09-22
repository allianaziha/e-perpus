<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanExport implements FromView
{
    protected $data;
    protected $tab;

    public function __construct($data, $tab)
    {
        $this->data = $data;
        $this->tab  = $tab;
    }

    public function view(): View
    {
        return view('admin.laporan.rekap', array_merge($this->data, ['tab' => $this->tab]));
    }
}

