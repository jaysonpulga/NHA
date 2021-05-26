<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Excel_voters_age implements FromCollection, WithHeadings , WithEvents
{
	protected $data;
	
	function __construct($data) {
	   $this->data = $data;
	}

    public function collection()
    {	   
	    return collect($this->data);
    }
	
	public function registerEvents(): array
	{
		return [
			AfterSheet::class    => function(AfterSheet $event) {
				// All headers - set font size to 14
				$cellRange = 'A1:W1'; 
				$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);

				
			},
		];
	}

    public function headings(): array
    {
        return ['Vin Number','Name','Gender','DOB','Age'];
    }
	
}