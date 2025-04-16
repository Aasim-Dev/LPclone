@extends('layouts.advertiser')

@section('title', 'Cart')

@section('styles')
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <style>
        .error{
            color: red;
        }
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
        @if($item->guest_post_price > 0 && $item->linkinsertion_price > 0)
        <div class="mb-3 backlink-options">
            <label>
                <input type="radio" id="provide_{{$item->id}}" class="provide" name="backlink_{{ $item->id }}" checked>
                Provide Content
            </label>
            <label>
                <input type="radio" name="backlink_{{ $item->id }}">
                Hire Content Writer
            </label>
            <label>
                <input type="radio" name="backlink_{{ $item->id }}">
                Link Insertion
            </label>
        </div>
        @elseif($item->guest_post_price > 0)
        <div class="mb-3 backlink-options">
            <label>
                <input type="radio" id="provide_{{$item->id}}" class="provide" name="backlink_{{ $item->id }}" checked>
                Provide Content
            </label>
            <label>
                <input type="radio" name="backlink_{{ $item->id }}">
                Hire Content Writer
            </label>
        </div>
        @elseif($item->linkinsertion_price > 0)
        <div class="mb-3 backlink-options">
            <label>
                <input type="radio" name="backlink_{{ $item->id }}">
                Link Insertion
            </label>
        </div>
        @endif
        <div class="modal fade" id="provideModal_{{$item->id}}" tabindex="-1" aria-labelledby="provideModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="provideModalLabel">Backlink 1:Provide Content |<span> ${{ $item->guest_post_price, $item->linkinsertion_price }}</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="provideContentForm_{{$item->id}}" method="" action="" >
                            <div class="mb-3" >
                                <label for="language" class="form-label">Language</label>
                                <input type="text" class="form-control" id="language" placeholder="English" readonly>
                                <label for="attachments" class="form-label">Attachments <span>Note: Docs supported Only</span></label>
                                <input type="file" class="form-control" name="file" id="attachments_{{$item->id}}" placeholder="Upload your File">
                                <label for="content" class="form-label">Special Instruction</label>
                                <textarea class="form-control" id="content_{{$item->id}}" name="content" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Content</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <button class="add-backlink-btn">+ Add Backlink</button>
        <div class="mt-3 d-flex justify-content-end">
            <button class="btn btn-sm btn-danger me-2" id="remove" data-id="{{$item->id}}" data-website_id="{{$item->website_id}}">Remove</button>
            <button class="order-btn">Order</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

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
                $.validator.addMethod("containsMinNumber", function(value, element) {
                    const numbers = value.match(/\d+/g); // extract all numbers
                    if (!numbers) return false; // no numbers at all
                    return numbers.some(n => parseInt(n, 10) <= 10); // at least one number >= 10
                }, "We can Ban your account on sharing personal information.");
                $("#cartTable").DataTable({
                    searching: true,
                    paging: true,
                    ordering: true,
                    order: [[2, "desc"]],
                    pagelength: 25,
                    lengthMenu: [25, 50, 100],
                });
                $(document).on('change', `input[name^="backlink_"]`, function () {
                    const itemId = $(this).attr('name').split('_')[1];
                    const selected = $(`input[name="backlink_${itemId}"]:checked`).attr('id');

                    if (selected === `provide_${itemId}`) {
                        $(`#provideModal_${itemId}`).modal('show');
                    } else {
                        $(`#provideModal_${itemId}`).modal('hide');
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
                $('form[id^="provideContentForm_"]').each(function () {
                    const formId = $(this).attr('id');
                    const itemId = formId.split('_')[1];
                    $(this)[0].reset();
                    $(this).validate({
                        rules: {
                            file: {
                                required: true,
                                extension: "docx|doc",
                            },
                            content: {
                                required: true,
                                maxlength: 255,
                                minlength: 10,
                                containsMinNumber: true,
                            },
                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
@endsection