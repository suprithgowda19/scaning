<?php

namespace App\Http\Controllers;

use App\Models\Delegate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Symfony\Component\HttpFoundation\Response;

class DelegatePaymentController extends Controller
{
    /**
     * STEP 1
     * Initiate payment (DB only, no gateway call)
     */
    public function initiate(Request $request, Delegate $delegate)
    {
        // Guard: only draft delegates
        if ($delegate->status !== 'draft') {
            abort(Response::HTTP_CONFLICT, 'Payment already initiated or completed.');
        }

        // Guard: prevent multiple active attempts
        $hasActive = $delegate->payments()
            ->whereIn('status', ['initiated', 'success'])
            ->exists();

        if ($hasActive) {
            abort(Response::HTTP_CONFLICT, 'Active payment already exists.');
        }

        // Fee calculation (single source of truth)
        $amount = match ($delegate->category) {
            'Delegate' => 60000,
            'Student', 'Senior Citizen', 'Film Fraternity' => 40000,
            default => null,
        };

        if (!$amount) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid delegate category.');
        }

        DB::transaction(function () use ($delegate, $amount) {
            Payment::create([
                'delegate_id' => $delegate->id,
                'amount'      => $amount,
                'currency'    => 'INR',
                'status'      => 'initiated',
                'gateway'     => 'razorpay',
            ]);

            $delegate->update([
                'status' => 'payment_pending',
            ]);
        });

        // Redirect to payment page (Blade)
        return redirect()->route('delegate.payment.page', $delegate);
    }

    /**
     * STEP 2
     * Show Razorpay payment page
     */
    public function paymentPage(Delegate $delegate)
    {
        abort_if($delegate->status !== 'payment_pending', Response::HTTP_CONFLICT);

        $payment = $delegate->payments()
            ->where('status', 'initiated')
            ->latest()
            ->firstOrFail();

        return view('delegate.payment', [
            'delegate' => $delegate,
            'payment'  => $payment,
            'rzpKey'   => config('services.razorpay.key_id'),
        ]);
    }

    /**
     * STEP 3
     * Create Razorpay Order (called via JS)
     */
    public function createOrder(Request $request, Delegate $delegate)
    {
        abort_if($delegate->status !== 'payment_pending', Response::HTTP_CONFLICT);

        $payment = $delegate->payments()
            ->where('status', 'initiated')
            ->latest()
            ->first();

        if (!$payment) {
            abort(Response::HTTP_NOT_FOUND, 'No initiated payment found.');
        }

        // Idempotency: reuse existing order
        if ($payment->gateway_order_id) {
            return response()->json([
                'key'        => config('services.razorpay.key_id'),
                'order_id'   => $payment->gateway_order_id,
                'amount'     => $payment->amount,
                'currency'   => $payment->currency,
            ]);
        }

        try {
            $api = new Api(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            );

            $order = $api->order->create([
                'amount'          => $payment->amount, // paise
                'currency'        => $payment->currency,
                'receipt'         => 'delegate_' . $payment->id,
                'payment_capture' => 1,
            ]);
        } catch (\Throwable $e) {
            abort(Response::HTTP_BAD_GATEWAY, 'Razorpay order creation failed.');
        }

        $payment->update([
            'gateway_order_id' => $order['id'],
        ]);

        return response()->json([
            'key'      => config('services.razorpay.key_id'),
            'order_id' => $order['id'],
            'amount'   => $payment->amount,
            'currency' => $payment->currency,
        ]);
    }
}
