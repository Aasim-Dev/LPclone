@extends('layouts.advertiser')

@section('title', 'MarketPlace')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 20px;
    }

    

    h1 {
        text-align: center;
        color: #ffffff;
        margin-bottom: 30px;
    }

    table#myTable {
        width: 100%;
        border-collapse: collapse;
        background-color: #1e1e1e;
        color: #ddd;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        border-radius: 10px;
        overflow: hidden;
    }

    table#myTable thead {
        background-color: #2c2c2c;
    }

    table#myTable thead th {
        padding: 12px 15px;
        color: #ffffff;
        font-weight: bold;
        border-bottom: 1px solid #333;
    }

    table#myTable tbody td {
        padding: 10px 15px;
        border-bottom: 1px solid #2a2a2a;
    }

    table#myTable tbody tr:hover {
        background-color: #333;
    }

    .add-to-cart {
        background-color: #03dac6;
        border: none;
        padding: 6px 12px;
        color: #000;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
    }

    .add-to-cart:hover {
        background-color: #00c4b4;
    }

    /* DataTables override styles for dark theme */
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        background-color: #2c2c2c;
        border: 1px solid #444;
        color: #fff;
        padding: 5px;
        border-radius: 5px;
    }

    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        color: #ccc;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background-color: #2c2c2c;
        color: #fff !important;
        border: 1px solid #444;
        border-radius: 3px;
        margin: 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #03dac6 !important;
        color: #000 !important;
    }

    .dataTables_wrapper .dataTables_processing {
        background: #2c2c2c;
        color: #03dac6;
        border-radius: 5px;
        padding: 10px;
    }
</style>
@endsection


@section('content')
    <div>
         <h1> This is the MarketPlace</h1> 
         <select id="daFilterMin">
            <option value="">Min DA</option>
            <option value="10">DA 10+</option>
            <option value="20">DA 20+</option>
            <option value="30">DA 30+</option>
        </select>

        <select id="daFilterMax">
            <option value="">Max DA</option>
            <option value="10">DA 10+</option>
            <option value="20">DA 20+</option>
            <option value="30">DA 30+</option>
        </select>

        <select id="categoryFilter">
            <option value="">All Categories</option>
            <option value="category1">Category 1</option>
            <option value="category2">Category 2</option>
        </select>

        <input type="number" id="priceFilterMin" placeholder="Min Price">
        <input type="number" id="priceFilterMax" placeholder="Max Price">

        <input type="date" id="dateFilter" placeholder="Created After">

            
            <button id="applyFilters">Apply Filters</button>
        </div>
    </div>

    <table id="myTable">
        <thead>
            <tr>
                <th>Created At</th>
                <th>Host URL</th>
                <th>DA</th>
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
    <script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('marketplace.data') }}",
                    data: function(d) {
                        d.min_da_filter = $('#daFilterMin').val();  
                        d.max_da_filter = $('#daFilterMax').val();  
                        d.category_filter = $('#categoryFilter').val();  
                        d.min_price_filter = $('#priceFilterMin').val();  
                        d.max_price_filter = $('#priceFilterMax').val();  
                        d.date_filter = $('#dateFilter').val();  
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
                searching: true,
                ordering: true,
                order: [0, 'desc'],
                pageLength: 25,
                lengthMenu: [25, 50, 100],
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
            });


            $('#applyFilters').on('click', function() {
                var table = $('#myTable').DataTable();
                table.ajax.reload();  // Trigger a reload with the updated filters
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