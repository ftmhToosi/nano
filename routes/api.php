<?php

use App\Exports\AllFacilityExport;
use App\Exports\ExpertExport;
use App\Exports\FacilityExport;
use App\Exports\UserExport;
use App\Exports\WarrantyExport;
use App\Http\Controllers\Api\Admin\AssessmentController;
use App\Http\Controllers\Api\Admin\CheckDocController;
use App\Http\Controllers\Api\Admin\CommitteeController;
use App\Http\Controllers\Api\Admin\CountController;
use App\Http\Controllers\Api\Admin\CreditController;
use App\Http\Controllers\Api\Admin\ExpertAssignmentController;
use App\Http\Controllers\Api\Admin\ExpertController;
use App\Http\Controllers\Api\Admin\ReportController;
use App\Http\Controllers\Api\Admin\TicketAdminController;
use App\Http\Controllers\Api\Admin\TicketExpertController;
use App\Http\Controllers\Api\Admin\UsersController;
use App\Http\Controllers\Api\v1\ApprovalsController;
use App\Http\Controllers\Api\v1\BankController;
use App\Http\Controllers\Api\v1\FileFacilitiesController;
use App\Http\Controllers\Api\v1\FinishController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\ReceiptController;
use App\Http\Controllers\Api\v1\ShareholderController;
use App\Http\Controllers\Api\v1\ProfileGenuineController;
use App\Http\Controllers\Api\v1\ProfileLegalController;
use App\Http\Controllers\Api\v1\RegisterController;
use App\Http\Controllers\Api\v1\RequestsController;
use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->name('Api/v1')->group(function (){
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [RegisterController::class, 'login']);
    Route::post('/verify', [RegisterController::class, 'verify']);
    Route::post('/confirm_verify', [RegisterController::class, 'confirmation']);
    Route::post('/forget_pass', [RegisterController::class, 'forget_pass']);
});


Route::middleware('auth:api')->prefix('v1')->name('Api/v1')->group(function (){
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::post('change_pass', [RegisterController::class, 'change_pass']);
    Route::apiResource('profile_genuine', ProfileGenuineController::class);
    Route::apiResource('profile_legal', ProfileLegalController::class);
    Route::get('is_profile_genuine', [ProfileGenuineController::class, 'is_profile_genuine']);
    Route::get('is_profile_legal', [ProfileLegalController::class, 'is_profile_legal']);
    Route::get('get_expert/{id}', [ExpertAssignmentController::class, 'get_expert']); // ids request
    Route::get('get_check_doc/{id}',[CheckDocController::class, 'get_check_doc']);
    Route::get('get_unread_notification', [CheckDocController::class, 'get_unread_notif']);
    Route::get('get_all_notification', [CheckDocController::class, 'get_all_notif']);
    Route::get('get_assessment/{id}', [AssessmentController::class, 'get_assessment']);
    Route::get('get_report/{id}', [ReportController::class, 'get_report']); //and for expert
    Route::get('get_committee/{id}', [CommitteeController::class, 'get_committee']);
    Route::get('get_credit/{id}', [CreditController::class, 'get_credit']);
    //
    Route::apiResource('ticket', TicketController::class)->except('destroy'); // update for reply
    Route::get('close_ticket/{id}', [TicketController::class, 'close_ticket']);
    Route::apiResource('receipt', ReceiptController::class);
    Route::get('get_with_user', [ReceiptController::class, 'get_with_user']);
    Route::apiResource('request', RequestsController::class);
    Route::put('update_user/{id}', [UsersController::class, 'update']);
    Route::get('show_user/{id}', [UsersController::class, 'show']);
    Route::post('request_delete', [RequestsController::class, 'request_delete']);
    Route::get('get_last_state', [RequestsController::class, 'get_last_state']);
    Route::get('get_current_request_user', [RequestsController::class, 'get_current_request_user']);
    Route::get('get_all_status/{id}', [RequestsController::class, 'get_all_status']);
    Route::apiResource('shareholder', ShareholderController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('bank', BankController::class);
    Route::apiResource('approvals', ApprovalsController::class);
    Route::apiResource('fileFacilities', FileFacilitiesController::class);
    Route::apiResource('finish', FinishController::class);
});

Route::middleware('auth:api')->prefix('admin')->name('Api/Admin')->group(function (){
    Route::apiResource('expert', ExpertController::class);
    Route::post('expert_assignment', [ExpertAssignmentController::class, 'expert_assignment']);
    Route::post('change_expert', [ExpertAssignmentController::class, 'change_expert']);
    Route::get('get_referred', [ExpertAssignmentController::class, 'get_referred']); //
    Route::get('get_failed', [CheckDocController::class, 'get_failed']);
    Route::apiResource('check_document', CheckDocController::class);
    Route::apiResource('start_assessment', AssessmentController::class);
    Route::apiResource('evaluation_report', ReportController::class);
    Route::apiResource('committee', CommitteeController::class);
    Route::apiResource('credit', CreditController::class);
    //ticket
    Route::apiResource('ticket_expert', TicketExpertController::class);
    Route::put('update_message/{id}', [TicketExpertController::class, 'update_message']);
    Route::post('reply_ticket/{id}', [TicketExpertController::class, 'reply']);
    Route::apiResource('ticket_admin', TicketAdminController::class)->except('destroy', 'show', 'store');
    Route::get('archive_ticket', [TicketAdminController::class, 'archive']);
    Route::get('archive_ticket_expert', [TicketExpertController::class, 'archive']);
    Route::get('reopen_ticket/{id}', [TicketAdminController::class, 'reopen']);
    Route::get('opened_ticket', [TicketAdminController::class, 'opened']);
    Route::get('opened_ticket_expert', [TicketExpertController::class, 'opened']);
    Route::get('unresolved_ticket', [TicketAdminController::class, 'unresolved']);
    Route::get('unresolved_ticket_expert', [TicketExpertController::class, 'unresolved']);
    //end
    Route::get('view_all_request', [RequestsController::class, 'view_request']);
//    Route::get('get_warranty/{id}', [RequestsController::class, 'get_warranty']);
    Route::get('get_request_with_expert/{id}', [ExpertAssignmentController::class, 'get_request_with_expert']);
    Route::get('get_request_without_expert', [RequestsController::class, 'get_request_without_expert']);
    Route::get('get_current_requests/{id}', [ExpertAssignmentController::class, 'get_current_requests']);
    Route::get('count_users', [CountController::class, 'count_users']);
    Route::get('count_experts', [CountController::class, 'count_experts']);
    Route::get('count_requests', [CountController::class, 'count_requests']);
    Route::apiResource('users', UsersController::class)->except('store'); // get = get all users
    Route::post('test', [ExpertController::class, 'test']);
    Route::get('test2', [ExpertController::class, 'test2']);
    Route::get('get_genuine', [UsersController::class, 'get_genuine']);
    Route::get('get_legal', [UsersController::class, 'get_legal']);
    Route::get('get_count_projects', [ExpertAssignmentController::class, 'get_count_projects']);
    Route::get('get_request_delete', [RequestsController::class, 'get_request_delete']);
    //
    Route::post('confirm_report_admin/{id}', [ReportController::class, 'confirm_report_admin']);
    Route::get('get_report_for_admin/{id}', [ReportController::class, 'get_report_for_admin']);
    Route::post('confirm_committee_admin/{id}', [CommitteeController::class, 'confirm_committee_admin']);
    Route::get('get_committee_for_admin/{id}', [CommitteeController::class, 'get_committee_for_admin']);
});


Route::get('/usersExcel', function (){
    $type = request('type');
    $phone = request('phone');
    $national_code = request('national_code');

    return Excel::download(
        new UserExport($type, $phone, $national_code), 'users ' . jdate()->format('Y-m-d') . '.xlsx');
});

Route::get('/expertExcel', function (){
    $phone = request('phone');
    $national_code = request('national_code');

    return Excel::download(
        new ExpertExport($phone, $national_code), 'experts ' . jdate()->format('Y-m-d') . '.xlsx');
});

Route::get('/warrantyExcel', function (){
    $title = request('title');

    return Excel::download(
        new WarrantyExport($title), 'warranties ' . jdate()->format('Y-m-d') . '.xlsx');
});

Route::get('/facilityExcel', function (){
    $title = request('title');

    return Excel::download(
        new FacilityExport($title), 'facilities ' . jdate()->format('Y-m-d') . '.xlsx');
});

Route::get('/allFacilityExcel', function (){
    $facility_id = request('facility_id');

    return Excel::download(
        new AllFacilityExport($facility_id), 'allFacilities ' . jdate()->format('Y-m-d') . '.xlsx');
});
