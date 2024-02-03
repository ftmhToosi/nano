<?php

namespace App\Exports;

use App\Models\Approvals;
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

class ApprovalsExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return Approvals::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شرح مجوز تائیدیه', 'مرجع صادرکننده', 'تاریخ صدور', 'مدت اعتبار', 'توضیحات'
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
            $row->license,
            $row->reference,
            // Jalalian::fromCarbon(Carbon::parse($row->date))->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->date)->format('Y/m/d'),
            $row->validity,
            $row->description,
        ];
    }

    public function columnFormats(): array
    {
        return [
//            'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
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
                        ->getStyle('A1:E1')
                        ->applyFromArray($styleArray);
                },
        ];

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'وضعیت مجوزهای تائیدیه ها';
    }
}
