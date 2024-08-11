@extends('layouts.app')

@section('title', 'Products | Prepcenter')

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
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Products Record</h5>
                    <div class="add-btn">
                        <a href="#" class="btn btn-primary me-2">Import Products</a>
                        {{-- <a href="#" class="btn btn-primary me-2">Export Products</a> --}}
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th data-ordering="false">No</th>
                                <th class="w-100">Item Name</th>
                                <th>MSKU</th>
                                <th>ASIN/ITEM.ID</th>
                                <th>FNSKU</th>
                                <th>PACK</th>
                                <th>QTY</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $counter = 1;
                            @endphp
                           @foreach($products as $product)
                                <tr>
                                    <td class="py-1">{{ $counter }}</td>
                                    <td class="py-1 truncate" data-toggle="tooltip" title="{{ $product->item }}">
                                        <a href="{{ route('products.edit', $product->id) }}">
                                            <small>{{ Str::limit($product->item, 60, '...') }}</small>
                                        </a>
                                    </td>
                                    <td class="py-1 fw-bold">{{ $product->msku }}</td>
                                    <td class="py-1">{{ $product->asin }}</td>
                                    <td class="py-1">{{ $product->fnsku }}</td>
                                    <td class="py-1">{{ $product->pack }}</td>
                                    <td class="py-1">{{ $product->dailyInputDetails->first()->total_qty ?? 0 }}</td>
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
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'ProductsRecord'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'ProductsRecord'
                }
            ]
        });
    });
</script>
@endsection