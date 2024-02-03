<?php

namespace App\Exports;

use App\Models\Facilities;
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

class OneFacilityExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents, WithTitle
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
        return Facilities::query()->with(['request', 'request.user', 'introduction'])->where('id', '=', $this->facility_id);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه','عنوان','نوع تسهیلات','درخواست دهنده','تاریخچه و معرفی شرکت', 'شرح مختصر فعالیت ها',
            'تاییدیه دانش بنیان؟', 'تاریخ تاییده', 'تاریخ انقضا تاییده', 'نوع دانش بنیان','اتمام شده'
        ];

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map(mixed $row): array
    {
        $typeFacilities = [
            'leasing' => 'لیزینگ',
            'saturation' => 'اشباع',
            'fund' => 'سرمایه در گردش',
            'prototyping' => 'نمونه سازی',
            'industrial' => 'تولید صنعتی',
            'pre_industrial' => 'قبل از تولید صنعتی',
        ];
        return [
            $row->request->shenaseh,
            $row->title,
            $typeFacilities[$row->type_f],
            $row->request->user->name . ' ' . $row->request->user->family,
            $row->introduction->history,
            $row->introduction->activity,
            $row->introduction->is_knowledge ? 'بله' : 'خیر',
            // Jalalian::fromCarbon(Carbon::parse($row->introduction->confirmation))->format('Y/m/d'),
            // Jalalian::fromCarbon(Carbon::parse($row->introduction->expiration))->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->introduction->confirmation)->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $row->introduction->expiration)->format('Y/m/d'),
            $row->introduction->area,
            $row->request->is_finished,
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
        return 'معرفی';
    }
}
