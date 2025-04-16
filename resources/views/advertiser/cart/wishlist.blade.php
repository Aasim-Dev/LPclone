@extends('layouts.advertiser')

@section('title', 'Cart')

@section('styles')
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        .cart-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .site-box {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
        }

        .status-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-green {
            background-color: #28a745;
        }

        .status-orange {
            background-color: #fd7e14;
        }

        .backlink-options label {
            margin-right: 15px;
        }

        .order-btn {
            background-color: #ff6f3c;
            color: #fff;
            border-radius: 5px;
            padding: 8px 16px;
            font-weight: 600;
            border: none;
        }

        .add-backlink-btn {
            border: 1px dashed #ff6f3c;
            color: #ff6f3c;
            background-color: transparent;
            border-radius: 5px;
            padding: 6px 12px;
            margin-top: 10px;
        }

        .cart-total {
            font-size: 20px;
            font-weight: 600;
            color: #ff6f3c;
        }
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Cart</h2>

    @foreach($cartItems as $item)
    <div class="cart-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0">{{ $item->host_url }}</h5>
                <small class="text-muted">Minimum Word Count: 500 | Completion Ratio: 100%</small>
            </div>
            <span class="cart-total">${{ $item->guest_post_price }}</span>
        </div>
        <div class="mt-3 d-flex justify-content-end">
            <button class="btn btn-sm btn-danger me-2" id="remove" data-id="{{$item->id}}" data-website_id="{{$item->website_id}}">Remove</button>
            <button class="move-to-cart-btn">Move to Cart</button>
        </div>
    </div>
    @endforeach

    <div class="text-end mt-4">
        <p class="cart-total">Order Total: ${{ $cartItems->sum('guest_post_price') }}</p>
        <button class="btn btn-primary">Go to Order Summary →</button>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Load Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Load jQuery Validation -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <!-- Load DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#cartTable").DataTable({
                    searching: true,
                    paging: true,
                    ordering: true,
                    order: [[2, "desc"]],
                    pagelength: 25,
                    lengthMenu: [25, 50, 100],
                });
                $('#provide').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('#provideModal').modal('show');
                    }
                });
                $(document).on('click', '#remove', function() {
                    var cartId = $(this).data('id');
                    var websiteId = $(this).data('website_id');
                    $.ajax({
                        url: "{{route('cart.destroy')}}",
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: cartId,
                            website_id: websiteId
                        },
                        success: function(response) {
                            location.reload();
                            toastr.success('Item removed from cart');
                        },
                        error: function(xhr){
                            toastr.error('Error occurred');
                        }
                    });
                });
            })
        </script>
@endsection
