@props(['row'])
<button type="button" class="btn btn-warning btn-xs " data-bs-toggle="modal" data-bs-target="#accept_payment_modal">
     Pay Now
</button>

<!-- The Modal -->
<div class="modal fade" id="accept_payment_modal">
    <div class="modal-dialog">
        <form id="acceptpayment_form" action="{{ route('invoice.accept_payments') }}" method="post" data-module="AcceptPayment">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Collect Payment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="alert alert-danger ml-2">
                    Amount Due-₹{{ $row->due_amount }}
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group mb-3 mt-3">
                        <label for="email" class="form-label">Amount To Pay:</label>
                        <input type="number" required class="form-control" placeholder="Enter amount" name="amount">
                        <input type="hidden" required  name="invoice_id" value="{{$row->id}}" >
                    </div>
                    <div class="form-group mb-3 mt-3">
                        <label for="payment_mode" class="form-label">Payment Mode:</label><br>
                        <select class="form-select mb-4" name="payment_mode">
                            <option value="Cash" selected>Cash</oashption>
                            <option value="Bank Account">Bank Account</option>
                            <option value="Paypal">Paypal</option>
                            <option value="Card">Credit/Debit Card</option>
                            <option value="UPI Transfer">UPI Transfer</option>
                        </select>
                    </div>


                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button id="acceptpayment_btn" type="submit" class="btn btn-info">Submit</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </form>
    </div>
</div>
