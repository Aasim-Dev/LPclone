@extends('layouts.advertiser')

@section('title', 'MarketPlace')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Base styling */
        body {
            background-color: #1e1e2f;
            color: #f0f0f0;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        /* Headings */
        h1 {
            color: #ffffff;
            font-size: 24px;
            margin-bottom: 15px;
        }

        /* Filters Container */
        #marketplace-filters {
            background-color: #2c2c3b;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Each filter group */
        .filter-group {
            margin-bottom: 15px;
        }

        /* Filter labels */
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        /* Inputs and selects */
        input[type="number"],
        select {
            background-color: #1e1e2f;
            color: #f0f0f0;
            border: 1px solid #444;
            padding: 8px 12px;
            border-radius: 6px;
            width: 100%;
            max-width: 300px;
        }

        select[multiple] {
            height: auto;
        }

        /* Apply Filters Button */
        #applyFilters {
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        #applyFilters:hover {
            background-color: #2563eb;
        }

        /* DataTable Styling */
        table#myTable {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2c3b;
            color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
        }

        /* Table headers */
        #myTable thead {
            background-color: #3f3f4f;
        }

        #myTable thead th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        /* Table rows */
        #myTable tbody td {
            padding: 10px;
            border-top: 1px solid #444;
        }

        /* Add to Cart button */
        .add-to-cart {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: #059669;
        }

        /* Select2 overrides */
        .select2-container--default .select2-selection--multiple {
            background-color: #1e1e2f;
            border: 1px solid #444;
            color: #f0f0f0;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3b82f6;
            border: none;
            color: white;
        }

        .select2-container--default .select2-results__option {
            background-color: #2c2c3b;
            color: #f0f0f0;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }

        /* DataTables Pagination and Search */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #fff !important;
            background-color: #3f3f4f !important;
            border: none !important;
            border-radius: 6px;
            padding: 6px 12px;
            margin: 2px;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: #1e1e2f;
            color: white;
            border: 1px solid #444;
            padding: 6px 10px;
            border-radius: 6px;
        }

    </style>
@endsection


@section('content')
    <div>
         <h1> This is the MarketPlace</h1>
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
    </div>

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
                        data: null,
                        render: function(data, type, row) {
                            return '<button class="add-to-cart" data-id="' + row.id + '">Add to cart</button>';
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

            $(document).on('click', '.add-to-cart', function(){
                let id = $(this).data('id');
                $.ajax({
                    url: '/advertiser/marketplace/add-to-cart',
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Item added to cart!');
                    },
                    error: function(xhr, status, error) {
                        alert('Error adding item to cart.');
                    }
                });
            });
        });
    </script>
@endsection