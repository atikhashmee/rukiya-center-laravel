<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Checkout</title>
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    /* minimal styling */
    #card-element { padding: 12px; border: 1px solid #e2e8f0; border-radius: 6px; }
    #card-errors { color: #e53e3e; margin-top: 8px; }
    #status-message { display:none; padding: 10px; margin-top: 12px; border-radius: 6px;}
    .bg-theme-success { background: #16a34a; }
  </style>
</head>
<body>
  <h2>Pay £150.00</h2>

  <form id="payment-form">
    <label for="email">Email</label><br>
    <input id="email" name="email" required type="email" /><br><br>

    <div id="card-element"><!-- Stripe Card Element mounts here --></div>
    <div id="card-errors" role="alert"></div><br>

    <button id="submit-button" type="submit">
      <i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now
    </button>
  </form>

  <div id="status-message"></div>

  <script>
    // --- 1. STRIPE INITIALIZATION ---
    const stripe = Stripe("{{ config('services.stripe.key') ?? env('STRIPE_KEY') }}");

    const elements = stripe.elements({
      fonts: [{ cssSrc: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap' }]
    });

    const cardElement = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          fontFamily: 'Inter, sans-serif',
          '::placeholder': { color: '#A0AEC0' },
        },
        invalid: {
          color: '#EF4444',
          iconColor: '#EF4444'
        }
      }
    });
    cardElement.mount('#card-element');

    cardElement.on('change', function(event) {
      const displayError = document.getElementById('card-errors');
      displayError.textContent = event.error ? event.error.message : '';
    });

    // --- 2. FORM SUBMISSION HANDLER ---
    const form = document.getElementById('payment-form');
    const payButton = document.getElementById('submit-button');
    const statusMessage = document.getElementById('status-message');

    form.addEventListener('submit', async function(e) {
      e.preventDefault();
      payButton.disabled = true;
      payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> Processing...';
      statusMessage.style.display = 'none';

      // 1) Create PaymentMethod using card element
      const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
        billing_details: {
          email: document.getElementById('email').value,
        },
      });

      if (pmError) {
        document.getElementById('card-errors').textContent = pmError.message;
        payButton.disabled = false;
        payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
        return;
      }

      // 2) Send payment_method_id to backend to create & confirm PaymentIntent
      try {
        const response = await fetch("{{ route('payment.process') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            payment_method_id: paymentMethod.id,
            amount: 15000, // 15000 pence = £150.00 (for GBP)
            currency: 'gbp',
            description: 'Example Order #'+Math.random().toString(36).substring(7),
            metadata: { custom: 'meta' }
          }),
        });

        const result = await response.json();
        console.log("intent created");
        
        console.log(result);
        

        if (result.error) {
          document.getElementById('card-errors').textContent = result.error;
          payButton.disabled = false;
          payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
          return;
        }

        // If requires 3D Secure / further action:
        if (result.requires_action) {
          // Use Stripe.js to handle the required action
          const { error: actionError, paymentIntent } = await stripe.confirmCardPayment(result.payment_intent_client_secret);

          if (actionError) {
            document.getElementById('card-errors').textContent = actionError.message;
            payButton.disabled = false;
            payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
            return;
          }

          if (paymentIntent && paymentIntent.status === 'succeeded') {
            statusMessage.textContent = 'Payment Successful! Redirecting...';
            statusMessage.classList.add('bg-theme-success', 'text-white');
            statusMessage.style.display = 'block';
            setTimeout(() => window.location.href = "{{ route('payment.success') }}", 1000);
            return;
          }
        }

        // Immediate success (no 3D Secure)
        if (result.success) {
          statusMessage.textContent = 'Payment Successful! Redirecting...';
          statusMessage.classList.add('bg-theme-success', 'text-white');
          statusMessage.style.display = 'block';
          setTimeout(() => window.location.href = "{{ route('payment.success') }}", 1000);
          return;
        }

        // Fallback - unknown
        document.getElementById('card-errors').textContent = 'Unexpected payment state. Please contact support.';
        payButton.disabled = false;
        payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';

      } catch (err) {
        document.getElementById('card-errors').textContent = err.message || 'Payment failed';
        payButton.disabled = false;
        payButton.innerHTML = '<i class="fas fa-check-circle mr-3"></i> Pay £150.00 Now';
      }
    });
  </script>
</body>
</html>
