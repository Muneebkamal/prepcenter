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
                        <a href="#" class="btn btn-primary me-2">Export Products</a>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th data-ordering="false">No</th>
                                <th>Item Name</th>
                                <th>MSKU</th>
                                <th>ASIN/ITEM.ID</th>
                                <th>FNSKU</th>
                                <th>PACK</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($products as $product)
                           <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->item }}</td>
                            <td>{{ $product->msku }}</td>
                            <td>{{ $product->asin }}</td>
                            <td>{{ $product->fnsku }}</td>
                            <td>{{ $product->pack }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success edit-item-btn"><i class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
                                </div>
                                {{-- <div class="dropdown d-inline-block">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-fill align-middle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                        <li>
                                            <a href="{{ route('products.edit', $product->id) }}" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                        </li>
                                    </ul>
                                </div> --}}
                            </td>
                           </tr>
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
        $('#example').DataTable();
    });
</script>
@endsection