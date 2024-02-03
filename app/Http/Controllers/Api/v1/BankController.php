<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BankRequest;
use App\Http\Requests\User\BankUpdateRequest;
use App\Models\ActiveF;
use App\Models\ActiveW;
use App\Models\Asset;
use App\Models\Bank;
use App\Models\Benefit;
use App\Models\Facilities;
use App\Models\Requests;
use Illuminate\Http\JsonResponse;

class BankController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = Bank::with('facilities')->get();
            return response()->json(
                $data,
                200
            );
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function store(BankRequest $request): JsonResponse
    {
        try {
            $facilities_id = $request->facilities_id;
            $facilities = Facilities::find($facilities_id);

            $banks = $request->banks;
            foreach ($banks as $bank){
                $bank_item = new Bank();
                $bank_item->facilities_id = $facilities_id;
                $bank_item->name = $bank['name'];
                $bank_item->branch = $bank['branch'];
                $bank_item->account_number = $bank['account_number'];
                $bank_item->save();
                $facilities->bank()->save($bank_item);
            }

            $active_facilities = $request->active_facilities;
            foreach ($active_facilities as $active_facility)
            {
                $active_item = new ActiveF();
                $active_item->facilities_id = $facilities_id;
                $active_item->year = $active_facility['year'];
                $active_item->name = $active_facility['name'];
                $active_item->amount_f = $active_facility['amount_f'];
                $active_item->type_f = $active_facility['type_f'];
                $active_item->rate = $active_facility['rate'];
                $active_item->type_collateral = $active_facility['type_collateral'];
                $active_item->n_refunds = $active_facility['n_refunds'];
                $active_item->n_remaining = $active_facility['n_remaining'];
                $active_item->amount_installment = $active_facility['amount_installment'];
                $active_item->remaining_f = $active_facility['remaining_f'];
                $active_item->settlement_time = $active_facility['settlement_time'];
                $active_item->save();
                $facilities->active_f()->save($active_item);
            }

            $active_warranty = $request->active_warranty;
            foreach ($active_warranty as $value){
                $active_i = new ActiveW();
                $active_i->facilities_id = $facilities_id;
                $active_i->name = $value['name'];
                $active_i->amount = $value['amount'];
                $active_i->subject = $value['subject'];
                $active_i->institution = $value['institution'];
                $active_i->type_w = $value['type_w'];
                $active_i->type_collateral = $value['type_collateral'];
                $active_i->deposit_amount = $value['deposit_amount'];
                $active_i->received = $value['received'];
                $active_i->due_date = $value['due_date'];
                $active_i->save();
                $facilities->active_w()->save($active_i);
            }

            $benefits = $request->benefits;
            foreach ($benefits as $benefit){
                $benefit_item = new Benefit();
                $benefit_item->facilities_id = $facilities_id;
                $benefit_item->account = $benefit['account'];
                $benefit_item->last_balance_a = $benefit['last_balance_a'];
                $benefit_item->last_balance_d = $benefit['last_balance_d'];
                $benefit_item->last_year_a = $benefit['last_year_a'];
                $benefit_item->last_year_d = $benefit['last_year_d'];
                $benefit_item->two_years_a = $benefit['two_years_a'];
                $benefit_item->two_years_d = $benefit['two_years_d'];
                $benefit_item->three_years_a = $benefit['three_years_a'];
                $benefit_item->three_years_d = $benefit['three_years_d'];
                $benefit_item->save();
                $facilities->benefit()->save($benefit_item);
            }

            $assets = $request->assets;
            foreach ($assets as $asset){
                $asset_item = new Asset();
                $asset_item->facilities_id = $facilities_id;
                $asset_item->account = $asset['account'];
                $asset_item->last_balance_a = $asset['last_balance_a'];
                $asset_item->last_balance_d = $asset['last_balance_d'];
                $asset_item->last_year_a = $asset['last_year_a'];
                $asset_item->last_year_d = $asset['last_year_d'];
                $asset_item->two_years_a = $asset['two_years_a'];
                $asset_item->two_years_d = $asset['two_years_d'];
                $asset_item->three_years_a = $asset['three_years_a'];
                $asset_item->three_years_d = $asset['three_years_d'];
                $asset_item->is_asset = $asset['is_asset'];
                $asset_item->save();
                $facilities->asset()->save($asset_item);
            }



            return response()->json([
                'success' => true,
            ], 201);

        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(BankUpdateRequest $request, $id)
    {
        try {
            $data = Requests::find($id);
            if ($data){
                $facilities = Facilities::query()->where('request_id', '=', $data->id)->first();
                if ($request->banks){
                    $banks = $request->banks;
                    $items = Bank::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($banks as $bank){
                        $bank_item = new Bank();
                        $bank_item->facilities_id = $facilities->id;
                        $bank_item->name = $bank['name'];
                        $bank_item->branch = $bank['branch'];
                        $bank_item->account_number = $bank['account_number'];
                        $bank_item->save();
                        $facilities->bank()->save($bank_item);
                    }
                }
                if ($request->active_facilities){
                    $active_facilities = $request->active_facilities;
                    $items = ActiveF::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($active_facilities as $active_facility)
                    {
                        $active_item = new ActiveF();
                        $active_item->facilities_id = $facilities->id;
                        $active_item->year = $active_facility['year'];
                        $active_item->name = $active_facility['name'];
                        $active_item->amount_f = $active_facility['amount_f'];
                        $active_item->type_f = $active_facility['type_f'];
                        $active_item->rate = $active_facility['rate'];
                        $active_item->type_collateral = $active_facility['type_collateral'];
                        $active_item->n_refunds = $active_facility['n_refunds'];
                        $active_item->n_remaining = $active_facility['n_remaining'];
                        $active_item->amount_installment = $active_facility['amount_installment'];
                        $active_item->remaining_f = $active_facility['remaining_f'];
                        $active_item->settlement_time = $active_facility['settlement_time'];
                        $active_item->save();
                        $facilities->active_f()->save($active_item);
                    }
                }
                if ($request->active_warranty){
                    $active_warranty = $request->active_warranty;
                    $items = ActiveW::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($active_warranty as $value){
                        $active_i = new ActiveW();
                        $active_i->facilities_id = $facilities->id;
                        $active_i->name = $value['name'];
                        $active_i->amount = $value['amount'];
                        $active_i->subject = $value['subject'];
                        $active_i->institution = $value['institution'];
                        $active_i->type_w = $value['type_w'];
                        $active_i->type_collateral = $value['type_collateral'];
                        $active_i->deposit_amount = $value['deposit_amount'];
                        $active_i->received = $value['received'];
                        $active_i->due_date = $value['due_date'];
                        $active_i->save();
                        $facilities->active_w()->save($active_i);
                    }
                }
                if ($request->benefits){
                    $benefits = $request->benefits;
                    $items = Benefit::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($benefits as $benefit){
                        $benefit_item = new Benefit();
                        $benefit_item->facilities_id = $facilities->id;
                        $benefit_item->account = $benefit['account'];
                        $benefit_item->last_balance_a = $benefit['last_balance_a'];
                        $benefit_item->last_balance_d = $benefit['last_balance_d'];
                        $benefit_item->last_year_a = $benefit['last_year_a'];
                        $benefit_item->last_year_d = $benefit['last_year_d'];
                        $benefit_item->two_years_a = $benefit['two_years_a'];
                        $benefit_item->two_years_d = $benefit['two_years_d'];
                        $benefit_item->three_years_a = $benefit['three_years_a'];
                        $benefit_item->three_years_d = $benefit['three_years_d'];
                        $benefit_item->save();
                        $facilities->benefit()->save($benefit_item);
                    }
                }
                if ($request->assets){
                    $assets = $request->assets;
                    $items = Asset::query()->where('facilities_id', '=', $facilities->id)->get();
                    foreach ($items as $item){
                        $item->find($item['id'])->delete();
                    }
                    foreach ($assets as $asset){
                        $asset_item = new Asset();
                        $asset_item->facilities_id = $facilities->id;
                        $asset_item->account = $asset['account'];
                        $asset_item->last_balance_a = $asset['last_balance_a'];
                        $asset_item->last_balance_d = $asset['last_balance_d'];
                        $asset_item->last_year_a = $asset['last_year_a'];
                        $asset_item->last_year_d = $asset['last_year_d'];
                        $asset_item->two_years_a = $asset['two_years_a'];
                        $asset_item->two_years_d = $asset['two_years_d'];
                        $asset_item->three_years_a = $asset['three_years_a'];
                        $asset_item->three_years_d = $asset['three_years_d'];
                        $asset_item->is_asset = $asset['is_asset'];
                        $asset_item->save();
                        $facilities->asset()->save($asset_item);
                    }
                }
                
                return response()->json([
                    'success' => true,
                ], 202);
            } else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = Bank::find($id);
            if ($data) {
                $data->delete();
                return response()->json([
                    'success' => true,
                ], 204);
            } else
                return response()->json([], 404);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = Bank::with('facilities')->find($id);
            if ($data){
                return response()->json(
                    $data,
                    200
                );
            }else
                return response()->json([], 404);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
