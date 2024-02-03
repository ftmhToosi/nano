<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class ExpertExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithEvents
{
    /**
     * EmployeeExport constructor
     */
    public function __construct(string $phone = null, string $national_code = null)
    {
        $this->phone = $phone;
        $this->national_code = $national_code;
    }

    public function query()
    {
        if ($this->phone)
            return User::query()->with('profilegenuine')->where('type', '=', 'expert')
                ->where('phone', '=', $this->phone);
        if ($this->national_code)
            return User::query()->with('profilegenuine')->where('type', '=', 'expert')
                ->where('national_code', '=', $this->national_code);
        else
            return User::query()->with('profilegenuine')->where('type', '=', 'expert');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'نام', 'کدملی', 'شماره موبایل', 'ایمیل', 'نام پدر', 'شماره شناسنامه','تاریخ تولد', 'محل صدور', 'سری و سریال شناسنامه', 'ملیت',
            'جنسیت', 'وضعیت تاهل','وضعیت اقامت', 'نحصیلات', 'رشته', 'شغل'
        ];

    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($expert): array
    {
        $gender = [
            'male' => 'مرد',
            'female' => 'زن',
        ];
        $marital = [
            'married' => 'متاهل',
            'single' => 'مجرد'
        ];
        $residential = [
            'resident' => 'مقیم',
            'non_resident' => 'غیرمقیم',
        ];
        return [
            $expert->name . ' ' . $expert->family,
            $expert->national_code,
            $expert->phone,
            $expert->email,
            $expert->profilegenuine->father_name,
            $expert->profilegenuine->number_certificate,
            // Jalalian::fromCarbon(Carbon::parse($expert->profilegenuine->birth_day))->format('Y/m/d'),
            CalendarUtils::createCarbonFromFormat('Y-m-d', $expert->profilegenuine->birth_day)->format('Y/m/d'),
            $expert->profilegenuine->place_issue,
            $expert->profilegenuine->series_certificate,
            $expert->profilegenuine->nationality,
            $gender[$expert->profilegenuine->gender],
            $marital[$expert->profilegenuine->marital],
            $residential[$expert->profilegenuine->residential],
            $expert->profilegenuine->education,
            $expert->profilegenuine->study,
            $expert->profilegenuine->job,
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
                        ->getStyle('A1:P1')
                        ->applyFromArray($styleArray);
                },
        ];

    }
}
