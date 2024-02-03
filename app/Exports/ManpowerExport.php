<?php

namespace App\Exports;

use App\Models\Manpower;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ManpowerExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return Manpower::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نام', 'سمت', 'تحصیلات', 'رشته تحصیلی', 'نوع قرارداد', 'سابقه کار مرتبط', 'سوابق کاری'
        ];

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map(mixed $row): array
    {
        $typeContract = [
            'full' => 'تمام وقت',
            'part' => 'پاره وقت',
        ];
        return [
            $row->name,
            $row->position,
            $row->level_education,
            $row->study,
            $typeContract[$row->type_contract],
            $row->work_experience,
            $row->important,
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
                        ->getStyle('A1:G1')
                        ->applyFromArray($styleArray);
                },
        ];

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'مشخصات نیروی انسانی شرکت';
    }
}
