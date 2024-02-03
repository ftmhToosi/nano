<?php

namespace App\Exports;

use App\Models\ActiveW;
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

class ActiveWExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return ActiveW::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نام بانک/صندوق', 'مبلغ دریافتی', 'موضوع قرارداد', 'نهاد دریافت کننده', 'نوع',
            'نوع وثیقه', 'میزان ودعیه', 'تاریخ اخذ', 'تاریخ سررسید نهایی'
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
            $row->amount,
            $row->subject,
            $row->institution,
            $row->type_w,
            $row->type_collateral,
            $row->deposit_amount,
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->received)->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->due_date)->format('Y/m/d'),
            // Jalalian::fromCarbon(Carbon::parse($row->received))->format('Y/m/d'),
            // Jalalian::fromCarbon(Carbon::parse($row->due_date))->format('Y/m/d'),
        ];
    }

    public function columnFormats(): array
    {
        return [
//            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD,
//            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
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
                        ->getStyle('A1:I1')
                        ->applyFromArray($styleArray);
                },
        ];

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ضمانتانمه فعال';
    }
}
