<?php

namespace App\Exports;

use App\Models\ActiveF;
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

class ActiveFExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return ActiveF::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'سال دریافت', 'نام بانک/صندوق', 'ملبغ دریافتی', 'نوع تسهیلات', 'نرخ تسهیلات',
            'نوع وثیقه', 'تعداد اقساط بازپرداخت شده', 'تعداد اقساط باقیمانده', 'مبلغ هر قسط', 'مانده تسهیلات', 'زمان تسویه نهایی'
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
            $row->year,
            $row->name,
            $row->amount_f,
            $row->type_f,
            $row->rate,
            $row->type_collateral,
            $row->n_refunds,
            $row->n_remaining,
            $row->amount_installment,
            $row->remaining_f,
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->settlement_time)->format('Y/m/d'),
            // Jalalian::fromCarbon(Carbon::parse($row->settlement_time))->format('Y/m/d'),
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
        return 'تسهیلات فعال';
    }
}
