@extends('layouts.advertiser')

@section('title', 'Cart')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1 {
        font-size: 28px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 25px;
        text-align: center;
    }

    #cartTable {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 100%;
        border-collapse: collapse;
    }

    #cartTable thead {
        background-color: #3498db;
        color: white;
    }

    #cartTable th, #cartTable td {
        padding: 15px 20px;
        text-align: center;
        vertical-align: middle;
    }

    #cartTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    #cartTable tbody tr:hover {
        background-color: #eaf2f8;
    }

    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .btn-order {
        background-color: #27ae60;
        color: white;
        margin-right: 8px;
    }

    .btn-order:hover {
        background-color: #219150;
    }

    .btn-remove {
        background-color: #e74c3c;
        color: white;
    }

    .btn-remove:hover {
        background-color: #c0392b;
    }

    .cart-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }
</style>
@endsection

@section('content')
    <div id="cart-wrapper" class="cart-wrapper">
        <h1>Welcome to the Cart</h1>
        <table id="cartTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Created AT</th>
                    <th>Host URL</th>
                    <th>DA</th>
                    <th>TAT</th>
                    <th>Semrush</th>
                    <th>Guest Post Price</th>
                    <th>LinkInsertion Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{$item->created_at}}</td>
                        <td><a href="{{$item->host_url}}">{{$item->host_url}}</a></td>
                        <td>{{$item->da}}</td>
                        <td>{{$item->tat}}</td>
                        <td>{{$item->semrush}}</td>
                        <td>{{$item->guest_post_price}}</td>
                        <td>{{$item->linkinsertion_price}}</td>
                        <td><button>Order</button>
                            <button>Remove</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                $("#cartTable").DataTable({
                    searching: true,
                    paging: true,
                    ordering: true,
                    order: [[2, "desc"]],
                    pagelength: 25,
                    lengthMenu: [25, 50, 100],
                });
            })
        </script>
@endsection