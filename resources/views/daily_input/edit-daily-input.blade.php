@extends('layouts.app')

@section('title', 'Edit Daily Input | Prepcenter')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p class="fw-bold m-0">Employee ID: <span class="ms-3">{{ $daily_input->id }}</span></p>
                    <p class="fw-bold m-0">Employee Name: <span class="ms-3">{{ $daily_input->user->name ?? 'N/A'}}</span></p>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content">
                                <p>Start Time: <span class="ms-3">{{ $daily_input->start_time }}</span></p>
                                <p>End Time: <span class="ms-3">{{ $daily_input->end_time }}</span></p>
                                <p>Rate / Hour: <span class="ms-3">{{ $daily_input->rate }}</span></p>
                                @php
                                    $quantity = 0;
                                    $totalTimeInSeconds = $daily_input->total_time_in_sec;
                                    $hours = intdiv($totalTimeInSeconds, 3600); // Total hours
                                    $minutes = intdiv($totalTimeInSeconds % 3600, 60); // Remaining minutes
                                @endphp
                                <p>Total Working Time:  <span class="ms-3">{{ $hours }} H {{ $minutes }} m</span></p>
                                <p>Total Paid: <span class="ms-3">{{ $daily_input->total_paid }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">+ Add Daily Input Detail Record Below :</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form id="editForm">
                        @csrf

                        <div class="row">
                            <input type="hidden" name="daily_input_id" value="{{ $daily_input->id }}">
                            <div class="col-md-6 mt-2">
                                <input class="form-control" type="text" placeholder="FNSKU/GTIN" id="fnsku-input" name="fnsku" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <input type="text" id="item" name="item" class="form-control" placeholder="Item Name" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <input type="number" name="qty" class="form-control" placeholder="QTY">
                            </div>
                            <div class="col-md-6 mt-2">
                                <input type="number" id="pack" name="pack" class="form-control" placeholder="Pack">
                            </div>
                            
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-danger" id="resetButton">RESET</button>
                                    <button type="submit" class="btn btn-primary ms-2">+ Add Record</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daily Input Detail Record</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th data-ordering="false">No</th>
                                <th>FNSKU/GTIN</th>
                                <th>Product Item Name</th>
                                <th>Pack</th>
                                <th>QTY</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily_input_details as $detail)
                            <tr>
                                <td>{{ $detail->id }}</td>
                                <td>{{ $detail->fnsku }}</td>
                                <td>{{ $detail->product->item }}</td>
                                <td>{{ $detail->pack }}</td>
                                <td>{{ $detail->qty }}</td>
                                @php
                                    $quantity += $detail->qty;
                                @endphp
                                <td>
                                    <div class="d-flex">
                                        <a data-id="{{ $detail->id }}" data-pack="{{ $detail->pack }}" data-qty="{{ $detail->qty }}" data-name="{{ $detail->item }}" data-bs-toggle="modal" data-bs-target="#editmodal" class="btn btn-success edit-item-btn me-1"><i class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
                                        <form method="POST" action="{{ route('daily.input.detail.delete', $detail->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger remove-item-btn">
                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                    {{-- <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li>
                                                <a data-id="{{ $detail->id }}" data-pack="{{ $detail->pack }}" data-qty="{{ $detail->qty }}" data-name="{{ $detail->item }}" data-bs-toggle="modal" data-bs-target="#editmodal" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('daily.input.detail.delete', $detail->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item remove-item-btn">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(Auth::user()->role == 2)
                        @if($daily_input_details->isNotEmpty())
                            <div class="row mt-4">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="content me-4">
                                        <p class="fw-bold me-5">Total QTY: <span class="ms-3">{{ $quantity }}</span></p>
                                        <p class="fw-bold me-5">Total packing Cost per Item: <span class="ms-3">{{ number_format($daily_input->total_packing_cost, 4) }}</span></p>
                                        <p class="fw-bold me-5">Total Item / Hour: <span class="ms-3">{{ number_format($daily_input->total_item_hour, 4) }}</span></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
    
    <!-- end page title -->

</div>



<div id="editmodal" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden">
            <div class="modal-header p-3">
                <h4 class="card-title mb-0">Update QTY Daily Input FNSKU = ()</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="detailEdit">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <p class="fw-bold">
                                Title : <span id="name" class="ms-3"></span>
                            </p>
                        </div>
                        <div class="col-md-12 mt-2">
                            <input type="hidden" id="detail_id" name="detail_id">
                            <label for="">QTY</label>
                            <input type="number" id="edit_qty" name="edit_qty" class="form-control" required>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Pack</label>
                            <input type="number" id="edit_pack" name="edit_pack" class="form-control" required>
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
    $(document).ready(function() {

        // Populate modal fields with data attributes
        $('.edit-item-btn').on('click', function() {
            var pack = $(this).data('pack');
            var qty = $(this).data('qty');
            var name = $(this).data('name');
            var detail_id = $(this).data('id');

            $('#edit_pack').val(pack);
            $('#edit_qty').val(qty);
            $('#name').text(name);
            $('#detail_id').val(detail_id);
        });

        // Reset form fields
        $('#resetButton').click(function() {
            $('#editForm')[0].reset(); // [0] is used to access the DOM element
        });

        // Handle form submission for new data
        $('#editForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create FormData object
            let formData = new FormData(this);

            // Send AJAX request
            $.ajax({
                url: "{{ route('daily.input.detail') }}", 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false, 
                success: function(response) {
                    if (response.success) {
                        var id = response.id;
                        var url = "{{ route('daily-input.show', ':id') }}".replace(':id', id);
                        window.location.href = url;
                    } else {
                        alert('An error occurred.');
                    }
                    console.log(response.success);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    console.error(errors);
                }
            });
        });

        // Handle form submission for editing data
        $('#detailEdit').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            let formData = new FormData(this);

            // Define the URL for the AJAX request
            let url = "{{ route('daily.input.detail.edit', ':detail_id') }}".replace(':detail_id', $('#detail_id').val());

            // Send AJAX request
            $.ajax({
                url: url, // URL for the request
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false, 
                success: function(response) {
                    if (response.success) {
                        location.reload();
                        // alert('Update successful');
                        // Optionally, redirect or update the UI here
                        // var id = response.id;
                        // var url = "{{ route('daily-input.show', ':id') }}".replace(':id', id);
                        // window.location.href = url;
                    } else {
                        alert('An error occurred.');
                    }
                    console.log(response.success);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    console.error(errors);
                }
            });
        });


        var routeUrl = '{{ route('daily.input.fnsku') }}';
        $('#fnsku-input').on('change', function() {
            var fnskuValue = $(this).val();
            
            // Validate the input value if needed
            if (fnskuValue.trim() === '') {
                return; // or handle empty input case
            }

            $.ajax({
                url: routeUrl,
                type: 'POST',
                data: {
                    fnsku: fnskuValue,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data);
                        $('#item').val(response.data.item);
                        $('#pack').val(response.data.pack);
                    } 
                    // else {
                    //     $('#product-name').text('Product not found.');
                    // }
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error('AJAX Error: ', status, error);
                }
            });
        });
    });

</script>
@endsection