@extends('layouts.advertiser')

@section('title', 'Cart')

@section('styles')
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <input type="radio" id="provide_{{$item->id}}" class="provide" name="backlink_{{ $item->id }}">
                Provide Content
            </label>
            <label>
                <input type="radio" id="hire_{{$item->id}}" name="backlink_{{ $item->id }}">
                Hire Content Writer
            </label>
            <label>
                <input type="radio" id="linkinsertion_{{ $item->id }}" name="backlink_{{ $item->id }}">
                Link Insertion
            </label>
        </div>
        @elseif($item->guest_post_price > 0)
        <div class="mb-3 backlink-options">
            <label>
                <input type="radio" id="provide_{{$item->id}}" class="provide" name="backlink_{{ $item->id }}">
                Provide Content
            </label>
            <label>
                <input type="radio" id="hire_{{$item->id}}" name="backlink_{{ $item->id }}">
                Hire Content Writer
            </label>
        </div>
        @elseif($item->linkinsertion_price > 0)
        <div class="mb-3 backlink-options">
            <label>
                <input type="radio" id="linkinsertion_{{ $item->id }}" name="backlink_{{ $item->id }}">
                Link Insertion
            </label>
        </div>
        @endif
        <!-- Modal for the Provide Content -->
        <div class="modal fade" id="provideModal_{{$item->id}}" tabindex="-1" aria-labelledby="provideModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="provideModalLabel">Backlink 1:Provide Content |<span> ${{ $item->guest_post_price, $item->linkinsertion_price }}</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="provideContentForm_{{$item->id}}" data-website-id="{{ $item->website_id }}" enctype="multipart/form-data">
                        
                            <div class="mb-3" >
                                <input type="hidden" name="website_id" value="{{$item->website_id}}" id="website_id">
                                <label for="language" class="form-label">Language</label>
                                <input type="text" class="form-control" id="language" placeholder="English" value="{{ $item->language ?? 'English' }}" readonly>
                                <label for="attachments" class="form-label">Attachments <span>Note: Docs supported Only</span></label>
                                <input type="file" class="form-control" name="attachment" id="attachments_{{$item->id}}" placeholder="Upload your File">
                                    <p class="mt-2" style="color:green">{{$item->attachment}}</p>
                                <label for="content" class="form-label">Special Instruction</label>
                                <textarea class="form-control" id="content_{{$item->id}}" name="content" rows="4">{{ $item->special_instruction ?? '' }}</textarea>
                            </div>
                            <button type="submit" id="submit" class="btn btn-primary">Submit Content</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for the Hire Content Writer -->
        <div class="modal fade" id="hireContentModal_{{$item->id}}" tabindex="-1" aria-labelledby="hireContentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hireContentModalLabel">Hire Content Writer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="hireContentForm_{{$item->id}}" data-website-id="{{$item->website_id}}">
                            <div class="mb-3">
                                <label for="language" class="form-label" >Language</label>
                                <input type="text" class="form-control" id="language" placeholder="English" value="{{$item->language ?? English}}" readonly><br>
                                <label for="titlesuggestion" class="form-label">Title Suggestion*</label>
                                <input type="text" class="form-control" id="title_suggestion_{{$item->id}}" name="title_suggestion" value="{{$item->title_suggestion ?? ''}}" placeholder="Enter title suggestion"><br>
                                <label for="keywords" class="form-label">Keywords*</label>
                                <input type="text" class="form-control" id="keywords_{{$item->id}}" name="keywords" value="{{$item->keywords ?? ''}}" placeholder="Enter keywords: Seperated by Comma"><br>
                                <label for="anchortext" class="form-label">Anchor Text*</label>
                                <input type="text" class="form-control" id="anchor_text_{{$item->id}}" name="anchor_text"  value="{{$item->anchor_text ?? ''}}" placeholder="Enter anchor text"><br>
                                <label for="country">Country*</label>
                                <select name="country" id="country_{{$item->id}}" class="form-control">
                                    <option value="{{$item->country ?? ''}}">{{$item->country ?? ''}}</option>
                                    <option value="India">India</option>
                                    <option value="USA">USA</option>
                                    <option value="UK">UK</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Dutch">Dutch</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Russia">Russia</option>
                                </select><br>
                                <label for="wordcount">Word Count*</label>
                                <select class="form-control" id="wordcount_{{$item->id}}" name="wordcount">
                                    <option value="{{$item->word_count ?? ''}}">{{$item->word_count ?? ''}}</option>
                                    <option value="500 words">500 words</option>
                                    <option value="1000 words">1000 words</option>
                                    <option value="1500 words">1500 words</option>
                                    <option value="2000 words">2000 words</option>
                                    <option value="2500 words">2500 words</option>
                                </select><br>
                                <label for="category" class="form-label">Category*</label>
                                <select class="form-control category" id="category_{{$item->id}}" name="category">
                                    <option value="{{$item->category ?? ''}}">{{$item->category ?? ''}}</option>
                                    <option value="Health & Fitness">Health & Fitness</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Agriculture">Agriculture</option>
                                    <option value="Arts & Entertainment">Arts & Entertainment</option>
                                    <option value="Beauty">Beauty</option>
                                    <option value="Blogging">Blogging</option>
                                    <option value="Buisness">Buisness</option>
                                    <option value="Career & Employment">Career & Employment</option>
                                    <option value="Ecommerce">Ecommerce</option>
                                    <option value="Web Development">Web Development</option>
                                </select><br>
                                <label for="reference" class="form-label">Reference Link*</label>
                                <input type="text" class="form-control" id="reference_{{$item->id}}" name="reference" value="{{$item->reference_link ?? ''}}" placeholder="Enter reference link"><br>
                                <label for="landingpage" class="form-label">Landing Page URL*</label>
                                <input type="text" class="form-control" id="target_url_{{$item->id}}" name="target_url" value="{{$item->target_url ?? ''}}" placeholder="Enter landing page URL"><br>
                                <label for="briefnote">Breif Note</label>
                                <textarea class="form-control" name="special_note" id="special_note_{{$item->id}}" placeholder="Enter Notes" >{{$item->special_note ?? ''}}</textarea>
                            </div>
                            <button type="submit" id="submitHire" class="btn btn-primary">Hire Writer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- LinkInsertion Modal -->
        <div class="modal fade" id="linkInsertionModal_{{$item->id}}" tabindex="-1" aria-labelledby="linkInsertionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="linkInsertionModalLabel">
                            Backlink 2: Link Insertion |
                            <span>${{ $item->linkinsertion_price }}</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="linkInsertionForm_{{$item->id}}" data-website-id="{{ $item->website_id }}">
                            <input type="hidden" name="website_id" value="{{ $item->website_id }}">
                            
                            <div class="mb-3">
                                <label for="existing_post_url_{{$item->id}}" class="form-label">Existing Post URL</label>
                                <input type="url" class="form-control" id="existing_post_url_{{$item->id}}" name="existing_post_url" value="{{$item->existing_post_url}}" placeholder="https://example.com/post-url">
                                    
                                <label for="landing_url_{{$item->id}}" class="form-label mt-3">Landing Page URL</label>
                                <input type="url" class="form-control" id="target_url_{{$item->id}}" name="target_url" value="{{$item->target_url}}" placeholder="https://your-site.com">

                                <label for="anchor_text_{{$item->id}}" class="form-label mt-3">Anchor Text</label>
                                <input type="text" class="form-control" id="anchor_text_{{$item->id}}" name="anchor_text" value="{{$item->anchor_text}}" placeholder="Anchor Text">

                                <label for="language_{{$item->id}}" class="form-label mt-3">Language</label>
                                <input type="text" class="form-control" id="language_{{$item->id}}" name="language" value="{{ $item->language ?? 'English' }}" readonly>

                                <label for="note_{{$item->id}}" class="form-label mt-3">Special Note</label>
                                <textarea class="form-control" id="note_{{$item->id}}" name="note" rows="3" value="{{$item->special_note}}" placeholder="Any special instructions...">{{ $item->special_note ?? '' }}</textarea>
                            </div>

                            <button type="submit" id="submitLinkInsertion" class="btn btn-primary">Submit Link Insertion</button>
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
        <p class="cart-total">Order Total: ${{ $cartItems->sum('guest_post_price')+$cartItems->sum('linkinsertion_price') }}</p>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.validator.addMethod("containsMinNumber", function(value, element) {
                    const numbers = value.match(/\d+/g); // extract all numbers
                    if (!numbers) return false; // no numbers at all
                    return numbers.some(n => parseInt(n, 10) <= 10); // at least one number >= 10
                }, "We can Ban your account on sharing personal information.");
                // $("#cartTable").DataTable({
                //     searching: true,
                //     paging: true,
                //     ordering: true,
                //     order: [[2, "desc"]],
                //     pagelength: 25,
                //     lengthMenu: [25, 50, 100],
                // });
                
                $(document).on('change', `input[name^="backlink_"]`, function() {
                    const itemId = $(this).attr('name').split('_')[1];
                    const selected = $(`input[name="backlink_${itemId}"]:checked`).attr('id');

                    if (selected === `provide_${itemId}`) {
                        $(`#provideModal_${itemId}`).modal('show');
                    }else if(selected === `hire_${itemId}`) {
                        $(`#hireContentModal_${itemId}`).modal('show');
                    }else if(selected === `linkinsertion_${itemId}`){
                        $(`#linkInsertionModal_${itemId}`).modal('show');
                    } else{
                        $(`#provideModal_${itemId}`).modal('hide');
                        $(`#hireContentModal_${itemId}`).modal('hide');
                        $(`#linkInsertionModal_${itemId}`).modal('hide');
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
                $('form[id^="provideContentForm_"]').each(function() {
                    const formId = $(this).attr('id');
                    const itemId = formId.split('_')[1];
                    
                    $(this)[0].reset();
                    $(this).validate({
                        rules: {
                            file: {
                                required: true,
                                accept: "docx, doc",
                            },
                            content: {
                                //required: true,
                                maxlength: 255,
                                //minlength: 10,
                                //containsMinNumber: true,
                            },
                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                });

                $(document).on('submit', 'form[id^="provideContentForm_"]', function(e) {
                    e.preventDefault();
                    const form = this;
                    const formId = $(form).attr('id');
                    const itemId = formId.split('_')[1];
                    const websiteId = $(form).data('website-id');  // make sure this is present in HTML
                    const specialInstruction = 'ABCDE';
                    const fileInput = $('#attachments_' + itemId)[0].files[0];

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('type', 'provide_content');
                    formData.append('language', 'English');
                    formData.append('special_instruction', specialInstruction);
                    formData.append('website_id', websiteId);
                    if (fileInput) {
                        formData.append('attachment', fileInput);
                    }

                    $.ajax({
                        url: "{{ route('cart.content') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            alert('Added to cart successfully!');
                            $('#provideModal_' + itemId).modal('hide');
                        },
                        error: function (err) {
                            alert('Error adding to cart');
                            console.log(err);
                        }
                    });
                });

                $(document).on('submit', 'form[id^="linkInsertionForm_"]', function(e){
                    e.preventDefault();
                    const form = this;
                    const formId = $(form).attr('id');
                    const itemId = formId.split('_')[1];
                    const websiteId = $(form).data('website-id');

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('website_id', websiteId);
                    formData.append('type', 'link_insertion');
                    
                    $.ajax({
                        url: "{{route('cart.link')}}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            alert('Added to cart successfully!');
                            $('#linkInsertionModal_' + itemId).modal('hide');
                        },
                        error: function (err) {
                            alert('Error adding to cart');
                            console.log(err);
                        }
                    });
                });

                $(document).on('submit', 'form[id^="hireContentForm_"]', function(e){
                    e.preventDefault();
                    console.log('Form submission triggered');
                    const form = this;
                    const formId = $(form).attr('id');
                    const itemId = formId.split('_')[1];
                    const websiteId = $(form).data('website-id');

                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('website_id', websiteId);
                    formData.append('type', 'guest_post');

                    $.ajax({
                        url: "{{route('cart.hire')}}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res){
                            alert('Added to cart successfully!');
                            $('#hireContentModal_' + itemId).modal('hide');
                        },
                        error: function(err){
                            alert('Error adding to cart');
                            console.log(err);
                        },
                    });
                });
            });
        </script>
@endsection