<?php

namespace App\Exports;

use App\Models\Place;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllFacilityExport implements WithMultipleSheets
{
    /**
     * EmployeeExport constructor
     */
    public function __construct(int $facility_id)
    {
        $this->facility_id = $facility_id;
    }

    public function sheets(): array
    {
        $sheets = [];


        $sheets[] = new OneFacilityExport($this->facility_id);
        $sheets[] = new PlaceExport($this->facility_id);
        $sheets[] = new ShareholderExport($this->facility_id);
        $sheets[] = new ResidenceExport($this->facility_id);
        $sheets[] = new ManpowerExport($this->facility_id);
        $sheets[] = new BoardExport($this->facility_id);
        $sheets[] = new EducationalExport($this->facility_id);
        $sheets[] = new ProductExport($this->facility_id);
        $sheets[] = new BankExport($this->facility_id);
        $sheets[] = new ActiveFExport($this->facility_id);
        $sheets[] = new ActiveWExport($this->facility_id);
        $sheets[] = new BenefitExport($this->facility_id);
        $sheets[] = new AssetExport($this->facility_id);
        $sheets[] = new DebtsExport($this->facility_id);
        $sheets[] = new ApprovalsExport($this->facility_id);
        $sheets[] = new ContractExport($this->facility_id);
        $sheets[] = new PledgeExport($this->facility_id);
        $sheets[] = new EstateExport($this->facility_id);
        $sheets[] = new FinishExport($this->facility_id);

        return $sheets;
    }
}
