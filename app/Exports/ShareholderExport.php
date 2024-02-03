<?php

namespace App\Exports;

use App\Models\Shareholder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ShareholderExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return Shareholder::query()->with(['facilities','facilities.part2'])->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نام سهامدار', 'نوع شخصیت', 'شماره شناسنامه/شماره ثبت', 'شماره ملي/شناسه ي ملي', 'تعداد سهام',
            'درصد سهام', 'ارزش سهام', 'رشته و مدرک تحصیلی', 'تعداد کل سهام', 'درصد کل سهام', 'ارزش کل سهام'
        ];

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map(mixed $row): array
    {
        $type = [
            'genuine' => 'حقیقی',
            'legal' => 'حقوقی',
        ];
        return [
            $row->name,
            $type[$row->type],
            $row->n_certificate,
            $row->n_national,
            $row->count,
            $row->percent,
            $row->cost,
            $row->education,
            $row->facilities->part2->sum_count,
            $row->facilities->part2->sum_percent,
            $row->facilities->part2->sum_cost,
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
                        ->getStyle('A1:K1')
                        ->applyFromArray($styleArray);
                },
        ];

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ترکیب سهامداران';
    }
}
