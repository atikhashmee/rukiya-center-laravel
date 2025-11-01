<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    // Show checkout page
    public function checkout(Request $request)
    {
        $bookingId = $request->query('booking');
        $booking = Booking::find($bookingId);
        if (! $booking) {
            abort(404, 'Booking not found.');
        }

        return view('Themes.checkout', [
            'booking' => $booking,
        ]);
    }

    // Main payment processing endpoint: receives payment_method_id from frontend
    public function processPayment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentMethodId = $request->input('payment_method_id');
            $bookingId = $request->input('booking_id');

            $booking = Booking::where('id', $bookingId)->first();
            if (! $booking) {
                throw new Exception('Something went wrong , ref = booking not found', 1);
            }
            $amount = round(($booking->service_price * 100));
            $currency = 'gbp'; // for now, later we will make it dynamic
            $description = sprintf('Payment for Order #%s', $booking->booking_id);

            // Create PaymentIntent and try to confirm with provided payment method
            $intent = PaymentIntent::create([
                'amount' => (int) $amount,
                'currency' => $currency,
                'payment_method' => $paymentMethodId,
                // 'confirmation_method' => 'manual',
                'confirm' => true, // attempt to confirm immediately
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'description' => $description,
                'metadata' => $request->input('metadata', []),
            ]);

            Log::info('Stripe PaymentIntent created: '.$intent->id);

            // Save a Payment record (initial)
            $payment = Payment::create([
                'customer_id' => auth()->id() ?? null,
                'payment_intent_id' => $intent->id,
                'payment_method_id' => $paymentMethodId,
                'amount' => (int) $amount,
                'currency' => $intent->currency ?? 'gbp',
                'status' => $intent->status,
                'description' => $intent->description ?? null,
                'order_id' => $booking->id,
                'order_type' => get_class($booking),
                'metadata' => $intent->metadata ?? null,
                'response_payload' => $intent->toArray(),
            ]);

            // If further action is required (3D Secure), tell frontend to handle it
            if ($intent->status === 'requires_action' && isset($intent->next_action) && $intent->next_action->type === 'use_stripe_sdk') {
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $intent->client_secret,
                ]);
            }

            // If succeeded, return success
            if ($intent->status === 'succeeded') {
                // Update DB status (ensure)
                $payment->update(['status' => 'succeeded', 'response_payload' => $intent->toArray()]);

                return response()->json(['success' => true, 'data' => $payment]);
            }

            // Otherwise, return the status
            return response()->json([
                'error' => 'Unhandled PaymentIntent status: '.$intent->status,
                'status' => $intent->status,
                'data' => $payment,
            ], 400);

        } catch (\Exception $e) {
            Log::error('Stripe charge error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Webhook for Stripe events
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret') ?? env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle event types
        $type = $event->type;
        $object = $event->data->object;

        switch ($type) {
            case 'payment_intent.succeeded':
                // Update DB record
                Payment::where('payment_intent_id', $object->id)->update([
                    'status' => 'succeeded',
                    'response_payload' => $object->toArray(),
                ]);
                break;

            case 'payment_intent.payment_failed':
                Payment::where('payment_intent_id', $object->id)->update([
                    'status' => 'failed',
                    'response_payload' => $object->toArray(),
                ]);
                break;

            case 'payment_intent.processing':
                Payment::where('payment_intent_id', $object->id)->update([
                    'status' => 'processing',
                    'response_payload' => $object->toArray(),
                ]);
                break;

                // Add other event types as needed

            default:
                Log::info('Unhandled Stripe webhook: '.$type);
        }

        return response()->json(['received' => true]);
    }

    public function paymentSuccess()
    {
        return view('Themes.payment-result-page', ['status' => 'success']);
    }

    public function paymentFailed()
    {
        return view('Themes.payment-result-page', ['status' => 'failed']);
    }
}
