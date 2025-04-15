@extends('layouts.advertiser')

@section('title', 'MarketPlace')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Container holding filters and table */
        #marketplace-container {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-top: 20px;
        }

        /* Filters section */
        #marketplace-filters {
            width: 300px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 100px;
            max-height: 90vh;
            overflow-y: auto;
        }

        #marketplace-filters h1 {
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .filter-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .filter-group input,
        .filter-group select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        #applyFilters {
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        #applyFilters:hover {
            background-color: #0056b3;
        }

        /* DataTable section */
        #myTable {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
        }

        #myTable_wrapper {
            flex: 1;
            overflow-x: auto;
        }

        table.dataTable thead th {
            background-color: #343a40;
            color: white;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.dataTable tbody tr:hover {
            background-color: #e9ecef;
        }

        .add-to-cart {
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
        }

        .add-to-cart:hover {
            background-color: #218838;
        }

        @media (max-width: 992px) {
            #marketplace-container {
                flex-direction: column;
            }

            #marketplace-filters {
                width: 100%;
                position: relative;
                top: unset;
                max-height: unset;
            }

            #myTable_wrapper {
                width: 100%;
            }
        }
    </style>

@endsection


@section('content')
    <div id="marketplace-container">
            <div id="marketplace-filters">
                <label for="Filters"><h1>Filters:</h1></label>
                <div name="da" id="da" class="filter-group">
                    <label for="da">DA Filter:</label>
                    <input type="number" id="daFilterMin" placeholder="Min DA">
                    <input type="number" id="daFilterMax" placeholder="Max DA">
                </div>
                <div name="categories" id="categories" class="filter-group">
                    <label for="categories">Categories:</label>
                    <select id="categoryFilter" name="category_filter[]" class="categoryFilter" multiple>
                        <option value="">All Categories</option>
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
                    </select>
                </div>
                <div class="filter-group" id="country" >
                    <label for="country">Country:</label>
                    <select class="countryFilter" name="country_filter[]" id="countryFilter" multiple>
                        <option value="">All Countries</option>
                        <option value="United States">United States</option>
                        <option value="India">India</option>
                        <option value="United Kingdom">United Kingdom</option>
                    </select>
                </div>
                <div class="filter-group" id="language">
                    <label for="language">Language:</label>
                    <select name="language_filter[]" id="languageFilter" class="languageFilter" multiple>
                        <option value="English">English</option>
                        <option value="Czech">Czech</option>
                        <option value="Dutch">Dutch</option>
                        <option value="Gujarati">Gujarati</option>
                    </select>
                </div>
                <div class="filter-group" id="price">
                    <label for="price">Price:</label>
                    <input type="number" id="priceFilterMin" placeholder="Min Price">
                    <input type="number" id="priceFilterMax" placeholder="Max Price">
                </div>
                <div name="ahref" id="ahref" class="filter-group">
                    <label for="ahref">Ahref:</label>
                    <input type="number" id="ahrefFilterMin" placeholder="Min Ahref">
                    <input type="number" id="ahrefFilterMax" placeholder="Max Ahref">
                </div>
                <div class="filter-group" id="semrush">
                    <label for="semrush">Semrush:</label>
                    <input type="number" id="semrushFilterMin" placeholder="Min Semrush">
                    <input type="number" id="semrushFilterMax" placeholder="Max Semrush">
                </div>
                <div class="filter-group" id="domainrating">
                    <label for="domainrate">Domain Rating:</label>
                    <input type="number" id="minDr" placeholder="Min DR">
                    <input type="number" id="maxDr" placeholder="Max DR">
                </div>
                <div class="filter-group" id="authorityscore">
                    <label for="authscore">Authority score:</label>
                    <input type="number" id="min_authority_filter" placeholder="Min Authority">
                    <input type="number" id="max_authority_filter" placeholder="Max Authority">
                </div>
                <div class="filter-group" id="tat">
                    <label for="tat">Tat:</label>
                    <select name="tat_filter[]" id="tatFilter" class="tatFilter">
                        <option value="">Select Tat</option>
                        <option value="1 day">1 day</option>
                        <option value="2 days">2 days</option>
                        <option value="3 days">3 days</option>
                        <option value="4 days">4 days</option>
                        <option value="5 days">5 days</option>
                        <option value="6 days">6 days</option>
                        <option value="7 days">7 days</option>
                        <option value="8 days">8 days</option>
                        <option value="9 days">9 days</option>
                        <option value="10 days">10 days</option>
                        <option value="11 days">11 days</option>
                        <option value="12 days">12 days</option>
                        <option value="13 days">13 days</option>
                        <option value="14 days">14 days</option>
                        <option value="15 days">15 days</option>
                        <option value="30 days">30 days</option>
                        <option value="60 days">60 days</option>
                    </select>
                </div>
                <div class="filter-group" id="linktype">
                    <label for="linktype">Link Type:</label>
                    <input type="radio" name="link_type_filter" id="dofollow" value="dofollow">
                    <label for="dofollow" >Do Follow</label>
                    <input type="radio" name="link_type_filter" id="nofollow" value="nofollow">
                    <label for="nofollow">No follow</label>
                </div>
                <!-- <input type="date" id="dateFilter" placeholder="Created After">     -->
                <button id="applyFilters">Apply Filters</button>
            </div>
        <div id="marketplace-table">
            <table id="myTable">
                <thead>
                    <tr>
                        <th>Created At</th>
                        <th>Host URL</th>
                        <th class="da">DA</th>
                        <th>Ahref</th>
                        <th>Semrush</th>
                        <th>Tat</th>
                        <th>Backlink Count & Type</th>
                        <th>Guest Post Price</th>
                        <th>Link Insertion Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Load Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Load jQuery Validation -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

<!-- Load DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            updateCartCount();
            $('#myTable').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('marketplace.data') }}",
                    data: function(d) {
                        d.min_da_filter = $('#daFilterMin').val();  
                        d.max_da_filter = $('#daFilterMax').val();  
                        d.category_filter = $('#categoryFilter').val();  
                        d.min_price_filter = $('#priceFilterMin').val();  
                        d.max_price_filter = $('#priceFilterMax').val();
                        d.country_filter = $('#countryFilter').val();
                        d.min_ahref_filter = $('#ahrefFilterMin').val();
                        d.max_ahref_filter = $('#ahrefFilterMax').val();
                        d.min_semrush_filter = $("#semrushFilterMin").val();
                        d.max_semrush_filter = $("#semrushFilterMax").val();
                        d.tat_filter = $('#tatFilter').val(); 
                        d.language_filter = $('#languageFilter').val();
                        d.min_dr = $('#minDr').val();
                        d.max_dr = $('#maxDr').val();
                        d.min_authority_filter = $('#min_authority_filter').val();
                        d.max_authority_filter = $('#max_authority_filter').val();
                        d.link_type_filter = $('input[name="link_type_filter"]:checked').val();
                        //d.date_filter = $('#dateFilter').val();  
                    }
                },
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'host_url', name: 'host_url' },
                    { data: 'da', name: 'da' },
                    { data: 'ahref', name: 'ahref' },
                    { data: 'semrush', name: 'semrush' },
                    { data: 'tat', name: 'tat' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `${row.backlink_count} ${row.backlink_type}`;
                        }
                    },
                    {
                        data: 'guest_post_price',
                        name: 'guest_post_price',
                        render: function (data, type, row) {
                            return data && data > 0 ? data : '-';
                        }
                    },
                    {
                        data: 'linkinsertion_price',
                        name: 'linkinsertion_price',
                        render: function (data, type, row) {
                            return data && data > 0 ? data : '-';
                        }
                    },
                    {
                        data: 'website_id',
                        render: function(data, type, row) {
                            return `<button class="add-to-cart"
                                        data-id="${row.website_id}"
                                        data-host_url="${row.host_url}"
                                        data-da="${row.da}"
                                        data-tat="${row.tat}"
                                        data-semrush="${row.semrush}"
                                        data-guest_post_price="${row.guest_post_price}"
                                        data-linkinsertion_price="${row.linkinsertion_price}">
                                        Add to Cart
                                    </button>`;
                        }
                    }
                ],
               
                pageLength: 25,
                lengthMenu: [25, 50, 100],
            });

            $(".categoryFilter, .countryFilter, .languageFilter").select2({
                placeholder: function() {
                    // Set dynamic placeholder based on element class
                    if($(this).hasClass("categoryFilter")) return "Select categories";
                    if($(this).hasClass("countryFilter")) return "Select country";
                    if($(this).hasClass("languageFilter")) return "Select Language";
                },
                allowClear: true,
                closeOnSelect: false
            });


            $(".categoryFilter").on("change", function() {
                table.ajax.reload(); 
            });

            $('#myTable_filter input').unbind().bind('keyup', function(e) {
                if (e.key === 'Enter') {
                    $('#myTable').DataTable().search(this.value).draw();
                }
            });
            $('#applyFilters').on('click', function() {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
            });

            $.ajax({
                url: "{{route('get.cart')}}",
                type: "GET",
                success: function(response){
                    const cartItems = response.cart.map(id => id.toString());
                    $('.add-to-cart').each(function () {
                        const websiteId = $(this).data('id').toString();

                        if (cartItems.includes(websiteId)) {
                            $(this).text("Remove from Cart").css("background-color", "#e74c3c");
                        }
                    });

                    updateCartCount();
                },
                
            });

            function updateCartCount() {
                $.ajax({
                    url: "{{route('cart.count')}}",
                    type: "GET",
                    success: function(response){
                        $('#cart-count').text(response.count);
                    },
                });
            }

            // Add item to cart in localStorage
            

            $(document).on('click', '.add-to-cart', function () {
                let websiteId = $(this).data('id');
                let hostUrl = $(this).data('host_url');
                let da = $(this).data('da');
                let tat = $(this).data('tat');
                let semrush = $(this).data('semrush');
                let guestPostPrice = $(this).data('guest_post_price');
                let linkInsertionPrice = $(this).data('linkinsertion_price');
                let button = $(this);

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: "POST",
                    data: {
                        website_id: websiteId,
                        host_url: hostUrl,
                        da: da,
                        tat: tat,
                        semrush: semrush,
                        guest_post_price: guestPostPrice,
                        linkinsertion_price: linkInsertionPrice,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.status === 'cart') {
                            button.text("Remove from Cart").css("background-color", "#e74c3c");
                        } else if (response.status === 'removed') {
                            button.text("Add to Cart").css("background-color", "#2ecc71");
                        }
                        updateCartCount();
                        alert(response.message);
                        
                    },
                    error: function (xhr) {
                        console.log(xhr.responseJSON);
                        alert("Something went wrong!");
                    }
                });
            });

            // $(document).on('click', '.add-to-cart', function () {
            //     const websiteId = $(this).data('website_id');

            //     const payload = {
            //         website_id: websiteId,
            //         action: 'remove'
            //     };

            //     fetch('https://lp-latest.elsnerdev.com/api/cart/store', {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'Accept': 'application/json',
            //             'Authorization': 'Bearer PKvUIEnrIMSViaD3BbJ1qJleBMMRY1' // replace with your actual token
            //         },
            //         body: JSON.stringify(payload)
            //     })
            //     .then(response => {
            //         if (!response.ok) throw new Error('Network response was not ok');
            //         return response.json();
            //     })
            //     .then(result => {
            //         alert('Item added to cart!');
            //         console.log('Cart response:', result);
            //         $(this).text('Remove from cart').removeClass('add-to-cart').addClass('remove-from-cart');
            //         addToLocalCart(websiteId);
            //     })
            //     .catch(err => {
            //         alert('Failed to add to cart.');
            //         console.error(err);
            //     });
            // });
        });
    </script>
@endsection