@extends('layouts.app')

@section('title', 'System Setting | Prepcenter')

@section('styles')
    <style>
        #example4_filter {
            display: flex;
            justify-content: start;
        }
        #example4_filter label input {
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

    <div class="row">
        <div class="col-xxl-6">
            <h5 class="mb-3">System Setting</h5>
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success d-none" id="success-alertj">
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" id="success-alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" id="error-alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav nav-justified gap-2 mb-3" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-bs-toggle="tab" href="#animation-home" role="tab">
                                Week Started Day
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#animation-profile" role="tab">
                                Departments
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#products-merge" role="tab">
                                Products Merge
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane active" id="animation-home" role="tabpanel">
                            <div class="row mb-3">
                                <form id="search_form" action="{{ route('system.setting.add') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <div class="col-md-6 p-3">
                                        <label for="employee-select">
                                            Select Started Day of Week:
                                        </label>
                                        <select class="form-select" id="day" name="day">
                                            <option value="" selected disabled>Select Day</option>
                                            <option value="1" {{ isset($setting) && $setting->week_started_day == 1 ? 'selected' : '' }}>Monday</option>
                                            <option value="2" {{ isset($setting) && $setting->week_started_day == 2 ? 'selected' : '' }}>Tuesday</option>
                                            <option value="3" {{ isset($setting) && $setting->week_started_day == 3 ? 'selected' : '' }}>Wednesday</option>
                                            <option value="4" {{ isset($setting) && $setting->week_started_day == 4 ? 'selected' : '' }}>Thursday</option>
                                            <option value="5" {{ isset($setting) && $setting->week_started_day == 5 ? 'selected' : '' }}>Friday</option>
                                            <option value="6" {{ isset($setting) && $setting->week_started_day == 6 ? 'selected' : '' }}>Saturday</option>
                                            <option value="7" {{ isset($setting) && $setting->week_started_day == 7 ? 'selected' : '' }}>Sunday</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex pt-4 px-3">
                                        <button type="submit" class="btn btn-primary me-2">Save</button>
                                    </div>
                                </form>        
                            </div>
                        </div>
                        <div class="tab-pane" id="animation-profile" role="tabpanel">
                            <div class="row mb-3">
                                <form action="{{ route('department.add') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <div class="col-md-6 p-3">
                                        <label for="employee-select">
                                            Department:
                                        </label>
                                        <input type="text" name="department" class="form-control">
                                    </div>
                                    <div class="col-md-3 d-flex pt-4 px-3">
                                        <button type="submit" class="btn btn-primary me-2">Save</button>
                                    </div>
                                </form>        
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th data-ordering="false">#</th>
                                                <th>Departments</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($departments as $department)
                                            <tr>
                                                <td>{{ $department->id }}</td>
                                                <td>{{ $department->dep_name }}</td>
                                                <td>
                                                    <a data-id="{{ $department->id }}" data-dep="{{ $department->dep_name }}" data-bs-toggle="modal" data-bs-target="#editmodal" class="btn btn-success edit-item-btn"><i class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="products-merge" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">Products Record</h5>
                                            <div class="add-btn d-flex align-items-center">
                                                <div class="me-2">
                                                    <form id="filterForm" action="{{ route('system.setting') }}" method="GET">
                                                        <input type="checkbox" id="temporaryProductFilter" name="temporary" class="me-2" 
                                                        onchange="document.getElementById('filterForm').submit()"  {{ request('temporary') ? 'checked' : '' }}> Temporary Products
                                                    </form>
                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-primary d-none" id="merge-btn">Products Merge</a>
                                                    {{-- <a href="{{ route('import.products') }}" class="btn btn-primary me-2">Import Products</a>
                                                    <a href="{{ route('import.table') }}" class="btn btn-primary me-2">Import</a>
                                                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="example4" class="table table-striped align-middle" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th data-ordering="false" style="width:3%"><small>#</small></th>
                                                        <th data-ordering="false" style="width:3%"><small>No</small></th>
                                                        <th class="w-100" style="width:58%"><small>Item Name</small></th>
                                                        <th style="width:10%"><small>MSKU/SKU</small></th>
                                                        <th style="width:10%"><small>ASIN/ITEM.ID</small></th>
                                                        <th style="width:10%"><small>FNSKU/GTIN</small></th>
                                                        <th style="width:3%"><small>PACK</small></th>
                                                        <th style="width:3%"><small>QTY</small></th>
                                                        {{-- <th>Action</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $counter = 1;
                                                    @endphp
                                                    @foreach($products as $product)
                                                        <tr>
                                                            <td class="py-1">
                                                                <input type="checkbox" class="form-check-input" name="select_products" id="product{{ $product->id }}" value="{{ $product->id }}" onclick="checkBox({{ $product->id }})">
                                                            </td>
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
                                </div>       
                            </div>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div>
        </div>
        <!--end col-->
    </div>
    
</div>


<div id="editmodal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0">Edit Department Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('department.add') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <input type="hidden" id="edit_id" name="edit_id">
                            <label for="">Department</label>
                            <input type="text" id="edit_dep" name="edit_dep" class="form-control">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@section('script')
<script>
    function checkBox(id){
        var checkedCount = $('input[name="select_products"]:checked').length;
        
        // Show or hide the button based on the count
        if (checkedCount === 2) {
            $('#merge-btn').removeClass('d-none');
        } else {
            $('#merge-btn').addClass('d-none');
        }
    }

    $(document).ready(function() {
        $('.edit-item-btn').on('click', function() {
            var id = $(this).data('id');
            var dep = $(this).data('dep');

            $('#edit_id').val(id);
            $('#edit_dep').val(dep);
        });

        $('#success-alert').each(function() {
            setTimeout(() => $(this).fadeOut('slow'), 2000); // 3000 milliseconds = 3 seconds
        });

        // Set a timeout for the error alert
        $('#error-alert').each(function() {
            setTimeout(() => $(this).fadeOut('slow'), 2000); // 3000 milliseconds = 3 seconds
        });


        $('#example4').DataTable({
            "ordering": false,
            pageLength: 100,
        });

        

        // $('input[name="select_products"]').on('change', function() {
        //     var checkedCount = $('input[name="select_products"]:checked').length;
            
        //     // Show or hide the button based on the count
        //     if (checkedCount === 2) {
        //         $('#merge-btn').removeClass('d-none');
        //     } else {
        //         $('#merge-btn').addClass('d-none');
        //     }
        // });

        $('#merge-btn').on('click', function() {
            var checkedValues = $('input[name="select_products"]:checked').map(function() {
                return $(this).val();
            }).get();
            // console.log(checkedValues);

            $.ajax({
                url: '{{ route("temp.products.merge") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    select_products: checkedValues
                },
                success: function(response) {
                    // alert('Products merged successfully!');
                    var successAlert = $('#success-alertj');
                    $('#success-alertj').removeClass('d-none');
                    successAlert.text('Products merged successfully!');
                    successAlert.fadeIn('slow');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    alert('An error occurred while merging products.');
                }
            });
        });
    })
</script>
@endsection
