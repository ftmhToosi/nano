<?php

namespace App\Exports;

use App\Models\Board;
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

class BoardExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return Board::query()->with('facilities')->where('facilities_id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نام', 'نوع شخصیت', 'سمت', 'شماره ملی', 'تاریخ تولد', 'سطح تحصیلات', 'رشته تحصیلی'
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
            $row->position,
            $row->n_national,
            // Jalalian::fromCarbon(Carbon::parse($row->birth_date))->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->birth_date)->format('Y/m/d'),
            $row->education,
            $row->study,
        ];
    }

    public function columnFormats(): array
    {
        return [
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
        return 'ترکیب اعضای هیئت مدیره';
    }
}
