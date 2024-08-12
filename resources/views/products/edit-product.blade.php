@extends('layouts.app')

@section('title', 'Edit Product | Prepcenter')

@section('content')

<div class="container-fluid">
                        
    <!-- start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Products</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Basic Elements</li>
                    </ol>
                </div>

            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Product</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form id="productEdit">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <label for="">ITEM NAME</label>
                                <input type="text" name="item" class="form-control" value="{{ $product->item }}" placeholder="Item / Title Product">
                                @error('item')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">MSKU</label>
                                <input type="text" name="msku" value="{{ $product->msku }}" class="form-control" placeholder="MSKU">
                                @error('msku')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">ASIN / ITEM ID</label>
                                <input type="text" name="asin" value="{{ $product->asin }}" class="form-control" placeholder="ASIN">
                                @error('asin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">FNSKU / GTIN</label>
                                <input type="text" name="fnsku" value="{{ $product->fnsku }}" class="form-control" placeholder="FNSKU" readonly>
                                @error('fnsku')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">PACK</label>
                                <input type="number" name="pack" value="{{ $product->pack }}" class="form-control" placeholder="Pack">
                                @error('pack')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex justify-content-end mt-3">
                                    {{-- <button type="button" class="btn btn-danger" id="resetButton">RESET</button> --}}
                                    <button type="submit" class="btn btn-primary ms-2">SAVE</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Products Detail Record</h4>
                </div>
                <div class="card-body">
                    <table id="example3" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th data-ordering="false">Working Date</th>
                                <th>Employee Name</th>
                                <th>Pack</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- end page title -->

</div>

@endsection

@section('script')
<script>
     $(document).ready(function() {
        $('#example3').DataTable();
        // $('#resetButton').click(function() {
        //     $('#productEdit')[0].reset(); // [0] is used to access the DOM element
        // });

        $('#productEdit').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create FormData object
            let formData = new FormData(this);
            let id = $('input[name="id"]').val();
            // Send AJAX request
            $.ajax({
                url: "{{ route('products.update', 'id') }}".replace('id', id),
                type: 'POST',
                data: formData,
                contentType: false, // Tell jQuery not to set content type
                processData: false, // Tell jQuery not to process data
                success: function(response) {
                    if (response.success) {
                        window.location.href = "{{ route('products.index') }}"; // Redirect to the URL
                    } else {
                        alert('An error occurred.');
                    }
                    console.log(data.success);
                },
                error: function(xhr) {
                    // Handle error response
                    let errors = xhr.responseJSON.errors;
                    
                }
            });
        });
    });
</script>
@endsection