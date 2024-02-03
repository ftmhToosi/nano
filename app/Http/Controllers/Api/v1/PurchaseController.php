<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;


//use SoapFault;

class PurchaseController extends Controller
{
    public function purchase()
    {
        try {
            $invoice = new Invoice();
            $invoice->amount(100000);

//            $user = Auth::user();
            $user = User::find(9);

            $paymentId = md5(uniqid());
            $transaction = $user->transactions()->create([
//                'book_id' => 1,
                'paid' => $invoice->getAmount(),
                'invoice_details' => $invoice,
                'payment_id' => $paymentId
            ]);

            $callbackUrl = route('purchase.result', ['payment_id' => $paymentId]);
            $payment = Payment::callbackUrl($callbackUrl);
            $payment->config('description', 'پرداخت درخواست ضمانتنامه ' );

            $payment->purchase($invoice, function($driver, $transactionId) use ($transaction){
                $transaction->transaction_id = $transactionId;
                $transaction->save();
            });

            return $payment->pay()->render();

        }catch (\Exception|PurchaseFailedException $e) {
//            dd($e);
            $transaction->transaction_result = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
            $transaction->status = Transaction::STATUS_FAILED;
            $transaction->save();

            return redirect('/checkout/result')->withMessage('خطا در اتصال به درگاه بانک');
        }
    }

    public function result(Request $request)
    {
        if ($request->missing('payment_id')) {
            //...
        }

        $transaction = Transaction::where('payment_id', $request->payment_id)->first();
        if (empty($transaction)) {
            // ...
        }

        if ($transaction->user_id <> Auth::id()) {
            // ...
        }

//        if ($book->id <> $transaction->book_id) {
//            // ...
//        }

        if ($transaction->status <> Transaction::STATUS_PENDING) {
            // ...
        }

        try {
            $receipt = Payment::amount($transaction->paid)
                ->transactionId($transaction->transaction_id)
                ->verify();

            $transaction->transaction_result = $receipt;
            $transaction->status = Transaction::STATUS_SUCCESS;
            $transaction->save();

            print 'پرداخت با موفقیت انجام شد';
//            $user = Auth::user();
//            $user->purchasedBooks()->create(['book_id' => $book->id]);

//            return view('purchase_result')->with([
//                'status' => 1,
//                'reference_id' => $receipt->getReferenceId(),
//                'book' => $book
//            ]);

        } catch (\Exception|InvalidPaymentException $e) {
            if ($e->getCode() < 0) {
                $transaction->status = Transaction::STATUS_FAILED;
                $transaction->transaction_result = [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ];

                $transaction->save();
            }

            return view('purchase_result')->with([
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }
    }

    public function index()
    {
        $user = User::find(9);
        $transaction = $user->transactions->firstWhere('status', 2);

        dd($transaction->toArray());

    }
}
