<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | 17th BIFFes 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #443570; --bg-light: #f8fafc; }
        body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; padding: 40px 0; }
        .payment-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: auto; padding: 40px; border: 1px solid #e2e8f0; }
        .header-banner { background: var(--primary); color: white; padding: 20px; border-radius: 15px 15px 0 0; text-align: center; margin: -40px -40px 30px -40px; }
        .btn-pay { background: var(--primary); color: white; padding: 15px; border-radius: 10px; font-weight: 700; width: 100%; border: none; font-size: 1.1rem; }
        .fee-badge { font-size: 2rem; font-weight: 800; color: var(--primary); }
    </style>
</head>
<body>

<div class="container">
    <div class="payment-card">
        <div class="header-banner">
            <h2 class="h4 mb-0">Registration Payment</h2>
        </div>

        <div class="text-center mb-4">
            <p class="text-muted mb-1">Registration Fee for <strong>{{ $delegate->category }}</strong></p>
            <div class="fee-badge">₹{{ $payment->amount / 100 }}</div>
        </div>

        <div class="details-box bg-light p-3 rounded-3 mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span>Name:</span>
                <span class="fw-bold">{{ $delegate->first_name }} {{ $delegate->last_name }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Email:</span>
                <span class="fw-bold">{{ $delegate->email }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Reference ID:</span>
                <span class="text-muted small">#{{ $delegate->id }}</span>
            </div>
        </div>

        <button id="rzp-button1" class="btn-pay">PAY NOW WITH RAZORPAY</button>
        
        <div class="mt-3 text-center">
            <small class="text-muted">Secure encryption provided by Razorpay</small>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button1').onclick = function(e){
    // STEP 1: Create Razorpay Order on Server
    fetch("{{ route('delegate.payment.order', $delegate) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        // STEP 2: Configure Razorpay Checkout Modal
        var options = {
            "key": data.key,
            "amount": data.amount,
            "currency": data.currency,
            "name": "17th BIFFes 2026",
            "description": "Delegate Fee - {{ $delegate->category }}",
            "order_id": data.order_id,
            "handler": function (response){
                // STEP 3: Send verification signature back to server
                verifyPayment(response);
            },
            "prefill": {
                "name": "{{ $delegate->first_name }} {{ $delegate->last_name }}",
                "email": "{{ $delegate->email }}",
                "contact": "{{ $delegate->phone }}"
            },
            "theme": { "color": "#443570" }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    })
    .catch(error => alert('Error initiating payment. Please try again.'));
    
    e.preventDefault();
}

function verifyPayment(response) {
    fetch("{{ route('delegate.payment.verify', $delegate) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(response)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.href = data.redirect;
        } else {
            alert('Verification Failed: ' + data.message);
        }
    });
}
</script>

</body>
</html>