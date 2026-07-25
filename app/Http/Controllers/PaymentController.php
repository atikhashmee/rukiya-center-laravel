<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Theme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $item = null;
        $type = null;

        if ($request->query('booking')) {
            $item = Booking::with('service')->find($request->query('booking'));
            $type = 'booking';
        } elseif ($request->query('order')) {
            $item = Order::with('items')->find($request->query('order'));
            $type = 'order';
        }

        if (!$item) {
            abort(404);
        }

        return view(Theme::resolveViewName('checkout'), compact('item', 'type'));
    }

    public function processPayment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentMethodId = $request->input('payment_method_id');
            $orderId = $request->input('order_id');
            $orderType = $request->input('order_type');

            $model = $orderType === 'App\Models\Order'
                ? Order::find($orderId)
                : Booking::find($orderId);

            if (!$model) {
                throw new Exception('Something went wrong, ref = order not found');
            }

            $amount = round(($model->total ?? $model->service_price) * 100);
            $currency = 'gbp';
            $description = $orderType === 'App\Models\Order'
                ? sprintf('Payment for Order #%s', $model->order_number)
                : sprintf('Payment for Booking #%s', $model->booking_id);

            $intent = PaymentIntent::create([
                'amount' => (int) $amount,
                'currency' => $currency,
                'payment_method' => $paymentMethodId,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'description' => $description,
                'metadata' => $request->input('metadata', []),
            ]);

            Log::info('Stripe PaymentIntent created: ' . $intent->id);

            $payment = Payment::create([
                'customer_id' => auth()->id() ?? null,
                'payment_intent_id' => $intent->id,
                'payment_method_id' => $paymentMethodId,
                'amount' => (int) $amount,
                'currency' => $intent->currency ?? 'gbp',
                'status' => $intent->status,
                'description' => $intent->description ?? null,
                'order_id' => $model->id,
                'order_type' => $orderType,
                'metadata' => $intent->metadata ?? null,
                'response_payload' => $intent->toArray(),
            ]);

            if ($intent->status === 'requires_action' && isset($intent->next_action) && $intent->next_action->type === 'use_stripe_sdk') {
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $intent->client_secret,
                ]);
            }

            if ($intent->status === 'succeeded') {
                $payment->update(['status' => 'succeeded', 'response_payload' => $intent->toArray()]);

                if ($orderType === 'App\Models\Order') {
                    $model->update(['payment_status' => 'paid', 'status' => 'processing']);
                } else {
                    $model->update(['payment_status' => 'paid', 'booking_status' => 'confirmed']);
                }

                return response()->json(['success' => true, 'data' => $payment]);
            }

            return response()->json([
                'error' => 'Unhandled PaymentIntent status: ' . $intent->status,
                'status' => $intent->status,
                'data' => $payment,
            ], 400);
        } catch (\Exception $e) {
            Log::error('Stripe charge error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret') ?? env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $type = $event->type;
        $object = $event->data->object;

        switch ($type) {
            case 'payment_intent.succeeded':
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
            default:
                Log::info('Unhandled Stripe webhook: ' . $type);
        }

        return response()->json(['received' => true]);
    }

    public function paymentSuccess()
    {
        return view(Theme::resolveViewName('payment-result-page'), ['status' => 'success']);
    }

    public function paymentFailed()
    {
        return view(Theme::resolveViewName('payment-result-page'), ['status' => 'failed']);
    }
}
