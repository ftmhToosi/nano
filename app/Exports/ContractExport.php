<?php

namespace App\Exports;

use App\Models\Contract;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContractExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return Contract::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'موضوع قراراد', 'نام کارفرما', 'مبلغ', 'تاریخ شروع', 'تاریخ پایان', 'درصد پیشرفت'
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
            $row->subject,
            $row->name,
            $row->amount,
            // Jalalian::fromCarbon(Carbon::parse($row->start))->format('Y/m/d'),
            // Jalalian::fromCarbon(Carbon::parse($row->end))->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->start)->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->end)->format('Y/m/d'),
            $row->progress,
        ];
    }

    public function columnFormats(): array
    {
        return [
//            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
//            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
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
                        ->getStyle('A1:F1')
                        ->applyFromArray($styleArray);
                },
        ];

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'فهرست قراردهای شاخص';
    }
}
