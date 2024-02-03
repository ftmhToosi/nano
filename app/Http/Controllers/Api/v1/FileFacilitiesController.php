<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FileFacilitiesRequest;
use App\Http\Requests\User\FileFacilitiesUpdateRequest;
use App\Models\Facilities;
use App\Models\FBalance;
use App\Models\FBills;
use App\Models\FCatalog;
use App\Models\FInsurance;
use App\Models\FKnowledge;
use App\Models\FLicense;
use App\Models\FLoans;
use App\Models\FProforma;
use App\Models\FRegistration;
use App\Models\FResume;
use App\Models\FSignatory;
use App\Models\FStatement;
use App\Models\Part7;
use App\Models\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class FileFacilitiesController extends Controller
{
    public function store(FileFacilitiesRequest $request): JsonResponse
    {
        try {
            $facilities_id = $request->facilities_id;
            $facilities = Facilities::find($facilities_id);

            $data = new Part7();
            $data->facilities_id = $facilities_id;
            $file = $request->file('file1');
            $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
            $folder = 'Facilities';
            $file->move( $folder, $filename );
            $data->file_name1 = $file->getClientOriginalName();
            $data->path1 = $folder .'/'. $filename;

            $file2 = $request->file('file2');
            $filename2 = time().Str::random(10) .'.'.$file2->getClientOriginalExtension();
            $file2->move( $folder, $filename2 );
            $data->file_name2 = $file2->getClientOriginalName();
            $data->path2 = $folder .'/'. $filename2;

            $file3 = $request->file('file3');
            $filename3 = time().Str::random(10) .'.'.$file3->getClientOriginalExtension();
            $file3->move( $folder, $filename3 );
            $data->file_name3 = $file3->getClientOriginalName();
            $data->path3 = $folder .'/'. $filename3;
            $data->save();

            $licenses = $request->licenses;
            foreach ($licenses as $license) {
                $license_item = new FLicense();
                $license_item->facilities_id = $facilities_id;
                $files = $license['file'];
                $filename = time().Str::random(10) .'.'.$files->getClientOriginalExtension();
                $files->move( $folder, $filename );
                $license_item->file_name = $files->getClientOriginalName();
                $license_item->path = $folder .'/'. $filename;
                $license_item->save();
                $facilities->f_license()->save($license_item);
            }

            $registers = $request->register_doc;
            foreach ($registers as $register){
                $register_item = new FRegistration();
                $register_item->facilities_id = $facilities_id;
                $f = $register['file'];
                $filename = time().Str::random(10) .'.'.$f->getClientOriginalExtension();
                $f->move( $folder, $filename );
                $register_item->file_name = $f->getClientOriginalName();
                $register_item->path = $folder .'/'. $filename;
                $register_item->save();
                $facilities->f_registration()->save($register_item);
            }

            $signatory = $request->signatory;
            foreach ($signatory as $item){
                $signatory_item = new FSignatory();
                $signatory_item->facilities_id = $facilities_id;
                $fil = $item['file'];
                $filename = time().Str::random(10) .'.'.$fil->getClientOriginalExtension();
                $fil->move( $folder, $filename );
                $signatory_item->file_name = $fil->getClientOriginalName();
                $signatory_item->path = $folder .'/'. $filename;
                $signatory_item->save();
                $facilities->f_signatory()->save($signatory_item);
            }

            $knowledges = $request->knowledge;
            foreach ($knowledges as $knowledge){
                $knowledge_item = new FKnowledge();
                $knowledge_item->facilities_id = $facilities_id;
                $fileN = $knowledge['file'];
                $filename = time().Str::random(10) .'.'.$fileN->getClientOriginalExtension();
                $fileN->move( $folder, $filename );
                $knowledge_item->file_name = $fileN->getClientOriginalName();
                $knowledge_item->path = $folder .'/'. $filename;
                $knowledge_item->save();
                $facilities->f_knowledge()->save($knowledge_item);
            }

            $resume = $request->resume;
            foreach ($resume as $value){
                $resume_item = new FResume();
                $resume_item->facilities_id = $facilities_id;
                $fileR = $value['file'];
                $filename = time().Str::random(10) .'.'.$fileR->getClientOriginalExtension();
                $fileR->move( $folder, $filename );
                $resume_item->file_name = $fileR->getClientOriginalName();
                $resume_item->path = $folder .'/'. $filename;
                $resume_item->save();
                $facilities->f_resume()->save($resume_item);
            }

            $loans = $request->loans;
            foreach ($loans as $loan){
                $loans_item = new FLoans();
                $loans_item->facilities_id = $facilities_id;
                $fileL = $loan['file'];
                $filename = time().Str::random(10) .'.'.$fileL->getClientOriginalExtension();
                $fileL->move( $folder, $filename );
                $loans_item->file_name = $fileL->getClientOriginalName();
                $loans_item->path = $folder .'/'. $filename;
                $loans_item->save();
                $facilities->f_loans()->save($loans_item);
            }

            $statements = $request->statements;
            foreach ($statements as $statement){
                $statement_item = new FStatement();
                $statement_item->facilities_id = $facilities_id;
                $fileS = $statement['file'];
                $filename = time().Str::random(10) .'.'.$fileS->getClientOriginalExtension();
                $fileS->move( $folder, $filename );
                $statement_item->file_name = $fileS->getClientOriginalName();
                $statement_item->path = $folder .'/'. $filename;
                $statement_item->save();
                $facilities->f_statement()->save($statement_item);
            }

            $balances = $request->balances;
            foreach ($balances as $balance){
                $balance_item = new FBalance();
                $balance_item->facilities_id = $facilities_id;
                $fileB = $balance['file'];
                $filename = time().Str::random(10) .'.'.$fileB->getClientOriginalExtension();
                $fileB->move( $folder, $filename );
                $balance_item->file_name = $fileB->getClientOriginalName();
                $balance_item->path =$folder .'/'. $filename;
                $balance_item->save();
                $facilities->f_balance()->save($balance_item);
            }

            $catalogs = $request->catalogs;
            foreach ($catalogs as $catalog){
                $catalog_item = new FCatalog();
                $catalog_item->facilities_id = $facilities_id;
                $fileC = $catalog['file'];
                $filename = time().Str::random(10) .'.'.$fileC->getClientOriginalExtension();
                $fileC->move( $folder, $filename );
                $catalog_item->file_name = $fileC->getClientOriginalName();
                $catalog_item->path = $folder .'/'. $filename;
                $catalog_item->save();
                $facilities->f_catalog()->save($catalog_item);
            }

            $insurances = $request->insurances;
            foreach ($insurances as $insurance){
                $insurance_item = new FInsurance();
                $insurance_item->facilities_id = $facilities_id;
                $fileI = $insurance['file'];
                $filename = time().Str::random(10) .'.'.$fileI->getClientOriginalExtension();
                $fileI->move( $folder, $filename );
                $insurance_item->file_name = $fileI->getClientOriginalName();
                $insurance_item->path = $folder .'/'. $filename;
                $insurance_item->save();
                $facilities->f_insurance()->save($insurance_item);
            }

            $invoices = $request->invoices;
            foreach ($invoices as $invoice){
                $invoice_item = new FProforma();
                $invoice_item->facilities_id = $facilities_id;
                $fileIn = $invoice['file'];
                $filename = time().Str::random(10) .'.'.$fileIn->getClientOriginalExtension();
                $fileIn->move( $folder, $filename );
                $invoice_item->file_name = $fileIn->getClientOriginalName();
                $invoice_item->path = $folder .'/'. $filename;
                $invoice_item->save();
                $facilities->f_proforma()->save($invoice_item);
            }

            $bills = $request->bills;
            foreach ($bills as $bill){
                $bill_item = new FBills();
                $bill_item->facilities_id = $facilities_id;
                $fileBi = $bill['file'];
                $filename = time().Str::random(10) .'.'.$fileBi->getClientOriginalExtension();
                $fileBi->move( $folder, $filename );
                $bill_item->file_name = $fileBi->getClientOriginalName();
                $bill_item->path = $folder .'/'. $filename;
                $bill_item->save();
                $facilities->f_bills()->save($bill_item);
            }

            return response()->json([
                'success' => true,
            ], 201);

        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(FileFacilitiesUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = Requests::find($id);
            if ($data){
                $facilities_id = $request->facilities_id;
                $facilities = Facilities::query()->where('request_id', '=', $data->id)->first();
                $part7 = Part7::query()->where('facilities_id', '=', $facilities->id)->first();
                $folder = 'Facilities';
                if ($request->hasFile('file1')){
                    $file = $request->file('file1');
                    $filename = time().Str::random(10) .'.'.$file->getClientOriginalExtension();
                    $folder = 'Facilities';
                    $file->move( $folder, $filename );
                    $part7->file_name1 = $file->getClientOriginalName();
                    $part7->path1 = $folder .'/'. $filename;
                }
                if ($request->hasFile('file2')){
                    $file2 = $request->file('file2');
                    $filename2 = time().Str::random(10) .'.'.$file2->getClientOriginalExtension();
                    $file2->move( $folder, $filename2 );
                    $part7->file_name2 = $file2->getClientOriginalName();
                    $part7->path2 = $folder .'/'. $filename2;
                }
                if ($request->hasFile('file3')){
                    $file3 = $request->file('file3');
                    $filename3 = time().Str::random(10) .'.'.$file3->getClientOriginalExtension();
                    $file3->move( $folder, $filename3 );
                    $part7->file_name3 = $file3->getClientOriginalName();
                    $part7->path3 = $folder .'/'. $filename3;
                }
                $part7->save();

                if ($request->licenses){
                    $licenses = $request->licenses;
                    $items = FLicense::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($licenses as $license) {
                        $license_item = new FLicense();
                        $license_item->facilities_id = $facilities_id;
                        $files = $license['file'];
                        $filename = time().Str::random(10) .'.'.$files->getClientOriginalExtension();
                        $files->move( $folder, $filename );
                        $license_item->file_name = $files->getClientOriginalName();
                        $license_item->path = $folder .'/'. $filename;
                        $license_item->save();
                        $facilities->f_license()->save($license_item);
                    }
                }
                if ($request->register_doc) {
                    $registers = $request->register_doc;
                    $items = FRegistration::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item) {
                        $item->find($item['id'])->delete();
                    }
                    foreach ($registers as $register){
                        $register_item = new FRegistration();
                        $register_item->facilities_id = $facilities_id;
                        $f = $register['file'];
                        $filename = time().Str::random(10) .'.'.$f->getClientOriginalExtension();
                        $f->move( $folder, $filename );
                        $register_item->file_name = $f->getClientOriginalName();
                        $register_item->path = $folder .'/'. $filename;
                        $register_item->save();
                        $facilities->f_registration()->save($register_item);
                    }
                }
                if ($request->signatory){
                    $signatory = $request->signatory;
                    $items = FSignatory::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($signatory as $item){
                        $signatory_item = new FSignatory();
                        $signatory_item->facilities_id = $facilities_id;
                        $fil = $item['file'];
                        $filename = time().Str::random(10) .'.'.$fil->getClientOriginalExtension();
                        $fil->move( $folder, $filename );
                        $signatory_item->file_name = $fil->getClientOriginalName();
                        $signatory_item->path = $folder .'/'. $filename;
                        $signatory_item->save();
                        $facilities->f_signatory()->save($signatory_item);
                    }
                }
                if ($request->knowledge){
                    $knowledges = $request->knowledge;
                    $items = FKnowledge::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($knowledges as $knowledge){
                        $knowledge_item = new FKnowledge();
                        $knowledge_item->facilities_id = $facilities_id;
                        $fileN = $knowledge['file'];
                        $filename = time().Str::random(10) .'.'.$fileN->getClientOriginalExtension();
                        $fileN->move( $folder, $filename );
                        $knowledge_item->file_name = $fileN->getClientOriginalName();
                        $knowledge_item->path = $folder .'/'. $filename;
                        $knowledge_item->save();
                        $facilities->f_knowledge()->save($knowledge_item);
                    }
                }
                if ($request->resume){
                    $resume = $request->resume;
                    $items = FResume::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($resume as $value){
                        $resume_item = new FResume();
                        $resume_item->facilities_id = $facilities_id;
                        $fileR = $value['file'];
                        $filename = time().Str::random(10) .'.'.$fileR->getClientOriginalExtension();
                        $fileR->move( $folder, $filename );
                        $resume_item->file_name = $fileR->getClientOriginalName();
                        $resume_item->path = $folder .'/'. $filename;
                        $resume_item->save();
                        $facilities->f_resume()->save($resume_item);
                    }
                }
                if ($request->loans){
                    $loans = $request->loans;
                    $items = FLoans::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($loans as $loan){
                        $loans_item = new FLoans();
                        $loans_item->facilities_id = $facilities_id;
                        $fileL = $loan['file'];
                        $filename = time().Str::random(10) .'.'.$fileL->getClientOriginalExtension();
                        $fileL->move( $folder, $filename );
                        $loans_item->file_name = $fileL->getClientOriginalName();
                        $loans_item->path = $folder .'/'. $filename;
                        $loans_item->save();
                        $facilities->f_loans()->save($loans_item);
                    }
                }
                if ($request->statements){
                    $statements = $request->statements;
                    $items = FStatement::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($statements as $statement){
                        $statement_item = new FStatement();
                        $statement_item->facilities_id = $facilities_id;
                        $fileS = $statement['file'];
                        $filename = time().Str::random(10) .'.'.$fileS->getClientOriginalExtension();
                        $fileS->move( $folder, $filename );
                        $statement_item->file_name = $fileS->getClientOriginalName();
                        $statement_item->path = $folder .'/'. $filename;
                        $statement_item->save();
                        $facilities->f_statement()->save($statement_item);
                    }
                }
                if ($request->balances){
                    $balances = $request->balances;
                    $items = FBalance::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($balances as $balance){
                        $balance_item = new FBalance();
                        $balance_item->facilities_id = $facilities_id;
                        $fileB = $balance['file'];
                        $filename = time().Str::random(10) .'.'.$fileB->getClientOriginalExtension();
                        $fileB->move( $folder, $filename );
                        $balance_item->file_name = $fileB->getClientOriginalName();
                        $balance_item->path =$folder .'/'. $filename;
                        $balance_item->save();
                        $facilities->f_balance()->save($balance_item);
                    }
                }
                if ($request->catalogs){
                    $catalogs = $request->catalogs;
                    $items = FCatalog::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($catalogs as $catalog){
                        $catalog_item = new FCatalog();
                        $catalog_item->facilities_id = $facilities_id;
                        $fileC = $catalog['file'];
                        $filename = time().Str::random(10) .'.'.$fileC->getClientOriginalExtension();
                        $fileC->move( $folder, $filename );
                        $catalog_item->file_name = $fileC->getClientOriginalName();
                        $catalog_item->path = $folder .'/'. $filename;
                        $catalog_item->save();
                        $facilities->f_catalog()->save($catalog_item);
                    }
                }
                if ($request->insurances){
                    $insurances = $request->insurances;
                    $items = FInsurance::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($insurances as $insurance){
                        $insurance_item = new FInsurance();
                        $insurance_item->facilities_id = $facilities_id;
                        $fileI = $insurance['file'];
                        $filename = time().Str::random(10) .'.'.$fileI->getClientOriginalExtension();
                        $fileI->move( $folder, $filename );
                        $insurance_item->file_name = $fileI->getClientOriginalName();
                        $insurance_item->path = $folder .'/'. $filename;
                        $insurance_item->save();
                        $facilities->f_insurance()->save($insurance_item);
                    }
                }
                if ($request->invoices){
                    $invoices = $request->invoices;
                    $items = FProforma::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($invoices as $invoice){
                        $invoice_item = new FProforma();
                        $invoice_item->facilities_id = $facilities_id;
                        $fileIn = $invoice['file'];
                        $filename = time().Str::random(10) .'.'.$fileIn->getClientOriginalExtension();
                        $fileIn->move( $folder, $filename );
                        $invoice_item->file_name = $fileIn->getClientOriginalName();
                        $invoice_item->path = $folder .'/'. $filename;
                        $invoice_item->save();
                        $facilities->f_proforma()->save($invoice_item);
                    }
                }
                if ($request->bills){
                    $bills = $request->bills;
                    $items = FBills::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($bills as $bill){
                        $bill_item = new FBills();
                        $bill_item->facilities_id = $facilities_id;
                        $fileBi = $bill['file'];
                        $filename = time().Str::random(10) .'.'.$fileBi->getClientOriginalExtension();
                        $fileBi->move( $folder, $filename );
                        $bill_item->file_name = $fileBi->getClientOriginalName();
                        $bill_item->path = $folder .'/'. $filename;
                        $bill_item->save();
                        $facilities->f_bills()->save($bill_item);
                    }
                }
                return response()->json([
                    'success' => true,
                ], 202);
            }else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

}
