<?php

namespace App\Exports;

use App\Models\Residence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ResidenceExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
{
    /**
     * EmployeeExport constructor
     */
    public function __construct(int $facility_id)
    {
        $this->facility_id = $facility_id;
    }

    public function query()
    {
        return Residence::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نام', 'سمت', 'نشانی محل سکونت',
        ];

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map(mixed $row): array
    {
        return [
            $row->name,
            $row->position,
            $row->address,
        ];
    }

    public function columnFormats(): array
    {
        return [
//            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function registerEvents(): array
    {
        $styleArray = [
            'font' => [
                'bold' => true
            ],
        ];

        return [
            AfterSheet::class =>

                function (AfterSheet $event) use ($styleArray) {
                    $event->sheet
                        ->getStyle('A1:C1')
                        ->applyFromArray($styleArray);
                },
        ];

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'آدرس محل سکونت اعضای هیئت مدیره';
    }
}
