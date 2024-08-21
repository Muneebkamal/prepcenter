@extends('layouts.app')

@section('title', 'Products | Prepcenter')

@section('styles')
<style>
    #example1_filter {
        display: flex;
        justify-content: center;
    }
    #example1_filter label input {
        width: 100%;
    }
    .truncate {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection

@section('content')

<div class="container-fluid">
                        
    <!-- start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Products Information</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Basic Elements</li>
                    </ol>
                </div>

            </div>
        </div>
    </div> --}}

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Products Record</h5>
                    <div class="add-btn d-flex align-items-center">
                        <div class="me-2">
                            <form id="filterForm" action="{{ route('products.index') }}" method="GET">
                                <input type="checkbox" id="temporaryProductFilter" name="temporary" class="me-2" 
                                onchange="document.getElementById('filterForm').submit()"  {{ request('temporary') ? 'checked' : '' }}> Temporary Products
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('import.products') }}" class="btn btn-primary me-2">Import Products</a>
                            <a href="{{ route('import.table') }}" class="btn btn-primary me-2">Import</a>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th data-ordering="false" style="width:4%"><small>No</small></th>
                                <th class="w-100" style="width:58%"><small>Item Name</small></th>
                                <th style="width:10%"><small>MSKU/SKU</small></th>
                                <th style="width:10%"><small>ASIN/ITEM.ID</small></th>
                                <th style="width:10%"><small>FNSKU/GTIN</small></th>
                                <th style="width:4%"><small>PACK</small></th>
                                <th style="width:4%"><small>QTY</small></th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $counter = 1;
                            @endphp
                           @foreach($products as $product)
                                <tr>
                                    <td class="py-1"><small>{{ $counter }}</small></td>
                                    <td class="py-1 truncate" data-toggle="tooltip" title="{{ $product->item }}">
                                        <a href="{{ route('products.edit', $product->id) }}">
                                            {{-- <small>{{ Str::limit($product->item, 60, '...') }}</small> --}}
                                            <small>{{ $product->item }}</small>
                                        </a>
                                    </td>
                                    <td class="py-1 fw-bold"><small>{{ $product->msku }}</small></td>
                                    <td class="py-1"><small>{{ $product->asin }}</small></td>
                                    <td class="py-1"><small>{{ $product->fnsku }}</small></td>
                                    <td class="py-1">
                                        {{-- @if($product->pack <= 0 || $product->pack == '')
                                            1
                                        @else --}}
                                        <small>{{ $product->pack }}</small>
                                        {{-- @endif --}}
                                    </td>
                                    <td class="py-1"><small>{{ $product->dailyInputDetails->first()->total_qty ?? 1 }}</small></td>
                                    {{-- <td class="py-1">
                                        <a href="{{ route('products.edit', $product->id) }}" class="edit-item-btn text-muted"><i class="ri-pencil-fill align-bottom me-2"></i></a>
                                    </td> --}}
                                </tr>
                                @php
                                $counter++;
                                @endphp
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
    
    <!-- end page title -->

</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        $('#example1').DataTable({
            "ordering": false,
            dom: 'lBfrtip',
            pageLength: 100,
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: function() {
                        let now = new Date();
                        let month = ('0' + (now.getMonth() + 1)).slice(-2);
                        let day = ('0' + now.getDate()).slice(-2);
                        let year = now.getFullYear();
                        let dateStr = month + '-' + day + '-' + year;

                        let isChecked = $('#temporaryProductFilter').is(':checked');
                        return isChecked ? 'Products_Record_Temporary_Products_' + dateStr : 'Products_Record_' + dateStr;
                    },
                    filename: function() {
                        let now = new Date();
                        let month = ('0' + (now.getMonth() + 1)).slice(-2);
                        let day = ('0' + now.getDate()).slice(-2);
                        let year = now.getFullYear();
                        let dateStr = month + '-' + day + '-' + year;

                        let isChecked = $('#temporaryProductFilter').is(':checked');
                        return isChecked ? 'Products_Record_Temporary_Products_' + dateStr : 'Products_Record_' + dateStr;
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Products',
                    title: function() {
                        let now = new Date();
                        let month = ('0' + (now.getMonth() + 1)).slice(-2);
                        let day = ('0' + now.getDate()).slice(-2);
                        let year = now.getFullYear();
                        let dateStr = month + '-' + day + '-' + year;

                        let isChecked = $('#temporaryProductFilter').is(':checked');
                        return isChecked ? 'Products_Record_Temporary_Products_' + dateStr : 'Products_Record_' + dateStr;
                    },
                    filename: function() {
                        let now = new Date();
                        let month = ('0' + (now.getMonth() + 1)).slice(-2);
                        let day = ('0' + now.getDate()).slice(-2);
                        let year = now.getFullYear();
                        let dateStr = month + '-' + day + '-' + year;

                        let isChecked = $('#temporaryProductFilter').is(':checked');
                        return isChecked ? 'Products_Record_Temporary_Products_' + dateStr : 'Products_Record_' + dateStr;
                    }
                }
            ]
        });
    });
</script>
@endsection