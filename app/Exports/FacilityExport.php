<?php

namespace App\Exports;

use App\Models\Facilities;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class FacilityExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents
{
    /**
     * EmployeeExport constructor
     */
    public function __construct(string $title = null)
    {
        $this->title = $title;
    }

    public function query()
    {
        if ($this->title)
            return Facilities::query()->with(['request', 'request.user', 'introduction'])->where('title', '=', $this->title);
        else
            return Facilities::query()->with(['request', 'request.user', 'introduction']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه','عنوان','نوع تسهیلات','درخواست دهنده','تاییدیه دانش بنیان؟','نوع دانش بنیان','وضعیت درخواست'
        ];

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $typeFacilities = [
            'leasing' => 'لیزینگ',
            'saturation' => 'اشباع',
            'fund' => 'سرمایه در گردش',
            'prototyping' => 'نمونه سازی',
            'industrial' => 'تولید صنعتی',
            'pre_industrial' => 'قبل از تولید صنعتی',
        ];
        $isKnowledge = $row->introduction->is_knowledge ?? '';
        $area = $row->introduction->area ?? '';
        return [
            $row->request->shenaseh,
            $row->title,
            $typeFacilities[$row->type_f],
            $row->request->user->name . ' ' . $row->request->user->family,
            $isKnowledge ? 'بله' : 'خیر',
            $area,
            $row->request->is_finished ? 'تمام شده' : 'در حال بررسی' ?? '',
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
}
