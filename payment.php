<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Payment placeholder page.
// Integrate with your payment gateway later (Razorpay/Stripe/etc.).

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: Integrate payment processor.
    // Validate inputs and create a payment intent.
    // On success, mark appointment/payment status and send emails.

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pay Online - Diagnostic Centre</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-1"><i class="fa-solid fa-credit-card me-2 text-warning"></i>Pay Online</h2>



                    <form method="POST" action="payment.php">
                        <div class="mb-3">
                            <label class="form-label">Patient Name</label>
                            <input class="form-control" name="patient_name" id="patient_name" required placeholder="Enter patient name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Appointment ID (optional)</label>
                            <input class="form-control" name="appointment_id" id="appointment_id" placeholder="e.g., 12">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input class="form-control" name="amount" id="amount" required type="number" min="1" step="1" placeholder="e.g., 500">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="method" id="method" required>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                                <option value="netbanking">Net Banking</option>
                            </select>
                        </div>

                        <button type="button" id="payBtn" class="btn btn-primary w-100 rounded-pill">
                            <i class="fa-solid fa-lock me-2"></i>🔒 Proceed to Pay
                        </button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payBtn').addEventListener('click', function () {
  const name = document.getElementById('patient_name').value.trim();
  const amount = parseFloat(document.getElementById('amount').value);
  const apptId = document.getElementById('appointment_id').value.trim();
  const method = document.getElementById('method').value;

  if (!name) { alert('Please enter patient name.'); return; }
  if (!amount || amount <= 0) { alert('Please enter a valid amount.'); return; }

  const fd = new FormData();
  fd.append('patient_name', name);
  fd.append('amount', amount);
  fd.append('appointment_id', apptId);
  fd.append('method', method);

  fetch('create_order.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.error) { alert(data.error); return; }
      const options = {
        key: data.key_id,
        amount: data.amount,
        currency: 'INR',
        name: 'Burdwan Scan Centre',
        description: 'Diagnostic Payment',
        order_id: data.order_id,
        prefill: { name: data.name },
        theme: { color: '#2563eb' },
        handler: function (response) {
          const cb = new FormData();
          cb.append('razorpay_payment_id', response.razorpay_payment_id);
          cb.append('razorpay_order_id', response.razorpay_order_id);
          cb.append('razorpay_signature', response.razorpay_signature);
          fetch('payment_callback.php', { method: 'POST', body: cb })
            .then(r => r.text())
            .then(html => { document.open(); document.write(html); document.close(); });
        }
      };
      const rzp = new Razorpay(options);
      rzp.open();
    })
    .catch(() => alert('Something went wrong. Please try again.'));
});
</script>
</body>
</html>


