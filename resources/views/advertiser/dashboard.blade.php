@extends('layouts.advertiser')

@section('title', 'Dashboard')

@section('styles')
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100vw;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background: #ffffff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            animation: fadeInScale 0.3s ease-in-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 1rem;
            margin-bottom: 0.3rem;
            font-weight: 600;
            color: #444;
        }

        input[type="text"],
        select {
            padding: 0.6rem 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        button#submit {
            margin-top: 1.5rem;
            padding: 0.75rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button#submit:hover {
            background-color: #0056b3;
        }
    </style>

@endsection

@section('content')
    <div class="overlay">
        <div class="modal">
            <h2>Add Funds</h2>
            <form id="fundForm" action="{{ route('wallet.addFunds') }}" method="POST">
                <input type="hidden" name="user_id" value="1">
                @csrf
                <label>Amount (USD):</label>
                <select name="amount" id="amount">
                    <option value="null">select</option>
                    <option value="10">$10</option>
                    <option value="50">$50</option>
                    <option value="100">$100</option>
                    <option value="200">$200</option>
                    <option value="300">$300</option>
                    <option value="400">$400</option>
                    <option value="500">$500</option>
                    <option value="1000">$1000</option>
                </select>

                <label>Payment Method:</label>
                <select id="paymentMethod" name="paymentMethod" required>
                    <option value="razorpay">Razorpay</option>
                    <option value="paypal">PayPal</option>
                </select>

                <div id="payButtons" style="margin-top: 20px;">
                    <button type="button" id="rzp-button" class="btn btn-primary">Add Funds</button>
                    <div id="paypal-button-container" style="margin-top: 10px;"></div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.add-funds', function(){
                $('.overlay').show();
            });
            $(".overlay").click(function(e){
                if(e.target.classList.contains("overlay")){
                    $(".overlay").fadeOut();
                }
            });
            $('#rzp-button').on('click', function () {
                let method = $('#paymentMethod').val();
                let amount = $('#amount').val();

                if (amount === "null" || amount === "") {
                    alert("Please select a valid amount.");
                    return;
                }

                if (method === 'paypal') {
                    let form = $('<form>', {
                        method: 'POST',
                        action: '{{ route('wallet.paypal') }}'
                    });

                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    }));

                    form.append($('<input>', {
                        type: 'hidden',
                        name: 'amount',
                        value: amount
                    }));

                    $('body').append(form);
                    form.submit();
                } else {
                    alert('Razorpay not integrated yet!');
                }
            });
        });
    </script>
@endsection