<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Delegate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RazorpayWebhookController extends Controller
{
    /**
     * Handle Razorpay Webhooks
     *
     * Events we care about:
     * - payment.captured
     * - payment.failed
     */
    public function handle(Request $request)
    {
        /**
         * 1. Verify signature (ABSOLUTELY REQUIRED)
         */
        $signature = $request->header('X-Razorpay-Signature');
        $payload   = $request->getContent();
        $secret    = config('razorpay.webhook_secret');

        if (!$this->verifySignature($payload, $signature, $secret)) {
            Log::warning('Razorpay webhook signature mismatch');
            return response()->json(['message' => 'Invalid signature'], Response::HTTP_FORBIDDEN);
        }

        $event = $request->input('event');

        match ($event) {
            'payment.captured' => $this->handlePaymentCaptured($request),
            'payment.failed'   => $this->handlePaymentFailed($request),
            default            => null,
        };

        return response()->json(['status' => 'ok'], Response::HTTP_OK);
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentCaptured(Request $request): void
    {
        $data = $request->input('payload.payment.entity');

        DB::transaction(function () use ($data) {

            /**
             * Find payment via gateway_order_id
             */
            $payment = Payment::where('gateway_order_id', $data['order_id'])->lockForUpdate()->first();

            if (!$payment) {
                Log::error('Payment not found for captured webhook', $data);
                return;
            }

            /**
             * Idempotency: ignore duplicate webhook calls
             */
            if ($payment->status === 'success') {
                return;
            }

            /**
             * Update payment
             */
            $payment->update([
                'status'             => 'success',
                'gateway_payment_id' => $data['id'],
            ]);

            /**
             * Update delegate & generate IDs
             */
            $delegate = Delegate::lockForUpdate()->find($payment->delegate_id);

            if (!$delegate || $delegate->status === 'confirmed') {
                return;
            }

            $delegate->update([
                'status'        => 'confirmed',
                'uuid'          => $this->generateUuid(),
                'reference_id'  => $this->generateReferenceId($delegate),
                'form_no'       => $this->generateFormNo($delegate),
            ]);

            /**
             * TODO (later):
             * - Dispatch confirmation email / SMS / WhatsApp
             */
        });
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailed(Request $request): void
    {
        $data = $request->input('payload.payment.entity');

        DB::transaction(function () use ($data) {

            $payment = Payment::where('gateway_order_id', $data['order_id'])->lockForUpdate()->first();

            if (!$payment) {
                return;
            }

            if ($payment->status === 'failed') {
                return;
            }

            $payment->update([
                'status'         => 'failed',
                'failure_reason' => $data['error_description'] ?? 'Payment failed',
            ]);

            /**
             * Roll delegate back to draft so user can retry
             */
            $delegate = Delegate::lockForUpdate()->find($payment->delegate_id);

            if ($delegate && $delegate->status === 'payment_pending') {
                $delegate->update([
                    'status' => 'draft',
                ]);
            }
        });
    }

    /**
     * Verify Razorpay signature
     */
    private function verifySignature(string $payload, string $signature, string $secret): bool
    {
        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $signature);
    }

    /**
     * ID generators
     * (kept private + deterministic)
     */
    private function generateUuid(): string
    {
        return (string) \Illuminate\Support\Str::uuid();
    }

    private function generateReferenceId(Delegate $delegate): string
    {
        return now()->format('ymHis')
            . strtoupper(substr($delegate->first_name, 0, 4))
            . now()->format('Y');
    }

    private function generateFormNo(Delegate $delegate): string
    {
        $prefix = match ($delegate->category) {
            'Delegate'         => 'DL',
            'Student'          => 'SD',
            'Senior Citizen'   => 'SN',
            'Film Fraternity'  => 'FF',
        };

        $count = Delegate::where('category', $delegate->category)
            ->where('status', 'confirmed')
            ->count() + 1;

        return '17BIFFES' . $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
