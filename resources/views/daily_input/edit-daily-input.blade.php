@extends('layouts.app')

@section('title', 'Edit Daily Input | Prepcenter')

@section('content')

<div class="container-fluid">

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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1">Daily Input Detail ( {{ $daily_input->id }} | {{ $daily_input->user->name ?? 'N/A' }} )</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6" style="border-right: 1px solid #ddd; padding-right: 20px;">
                            <table class="table  table-striped align-middle" style="width:100%">
                                <tr>
                                    <td>Employee ID</td>
                                    <td>:<span class="ms-2">{{ $daily_input->id }}</span></td>
                                </tr>
                                <tr>
                                    <td>Employee Name</td>
                                    <td>:<span class="ms-2">{{ $daily_input->user->name ?? 'N/A' }}</span></td>
                                </tr>
                                @php
                                use Carbon\Carbon;
                                @endphp
                                <tr>
                                    <td>Start Time</td>
                                    <td>:<span class="ms-2">{{ \Carbon\Carbon::parse($daily_input->start_time)->format('H:i') }}</span>
                                        <a href="{{ route('daily-input.edit', $daily_input->id) }}">(<i class="ri-pencil-fill align-bottom"></i> Edit)</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>End Time</td>
                                    <td>:<span class="ms-2">{{ \Carbon\Carbon::parse($daily_input->end_time)->format('H:i') }}</span>
                                        <a href="{{ route('daily-input.edit', $daily_input->id) }}">(<i class="ri-pencil-fill align-bottom"></i> Edit)</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Rate / Hour</td>
                                    <td>:<span class="ms-2">${{ $daily_input->rate }}</span></td>
                                </tr>
                                @php
                                    $quantity = 0;
                                    $totalTimeInSeconds = $daily_input->total_time_in_sec;
                                    $hours = intdiv($totalTimeInSeconds, 3600); // Total hours
                                    $minutes = intdiv($totalTimeInSeconds % 3600, 60); // Remaining minutes
                                @endphp
                                <tr>
                                    <td>Total Working Time</td>
                                    <td>:<span class="ms-2">{{ $hours }} H {{ $minutes }} m</span></td>
                                </tr>
                                <tr>
                                    <td>Total Paid</td>
                                    <td>:<span class="ms-2">${{ $daily_input->total_paid }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            @php
                                $qtys = 0;
                            @endphp
                            @foreach($daily_input_details as $detail)
                                @php
                                    $qtys += $detail->qty;
                                @endphp
                            @endforeach
                            @if(Auth::user()->role == 1)
                                @if($daily_input_details->isNotEmpty())
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table  table-striped align-middle" style="width:100%">
                                                <tr>
                                                    <td>Total QTY</td>
                                                    <td><span class="ms-2">{{ $qtys }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Packing Cost Per Item</td>
                                                    <td><span class="ms-2">${{ number_format($daily_input->total_packing_cost, 2) }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Item/Hour</td>
                                                    <td><span class="ms-2">{{ number_format($daily_input->total_item_hour, 2) }}</span></td>
                                                </tr>
                                            </table>

                                            {{-- <div class="content">
                                                <p class="fw-bold me-5">Total QTY: <span class="ms-3">{{ $qtys }}</span></p>
                                                <p class="fw-bold me-5">Total packing Cost per Item: <span class="ms-3">${{ number_format($daily_input->total_packing_cost, 2) }}</span></p>
                                                <p class="fw-bold me-5">Total Item / Hour: <span class="ms-3">{{ number_format($daily_input->total_item_hour, 2) }}</span></p>
                                            </div> --}}
                                        </div>
                                    </div>
                                @endif
                            @endif
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
                                <input type="text" id="item" name="item" class="form-control" placeholder="Item Name">
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
                                        <a data-id="{{ $detail->id }}" data-pack="{{ $detail->pack }}" data-qty="{{ $detail->qty }}" data-name="{{ $detail->product->item }}" data-fnsku="{{ $detail->fnsku }}" data-bs-toggle="modal" data-bs-target="#editmodal" class="d-flex btn btn-primary edit-item-btn me-1"><i class="ri-pencil-fill align-bottom me-2"></i> Edit</a>
                                        <form method="POST" action="{{ route('daily.input.detail.delete', $detail->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="d-flex btn btn-danger remove-item-btn">
                                                <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(Auth::user()->role == 1)
                        @if($daily_input_details->isNotEmpty())
                            <div class="row mt-4">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="content me-4">
                                        <p class="fw-bold me-5">Total QTY: <span class="ms-3">{{ $quantity }}</span></p>
                                        <p class="fw-bold me-5">Total packing Cost per Item: <span class="ms-3">${{ number_format($daily_input->total_packing_cost, 2) }}</span></p>
                                        <p class="fw-bold me-5">Total Item / Hour: <span class="ms-3">{{ number_format($daily_input->total_item_hour, 2) }}</span></p>
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
                <h4 class="card-title mb-0">Update QTY Daily Input FNSKU/GTIN = (<span id="fnsku"></span>)</h4>
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

        $('#success-alert').each(function() {
            setTimeout(() => $(this).fadeOut('slow'), 2000); 
        });

        // Set a timeout for the error alert
        $('#error-alert').each(function() {
            setTimeout(() => $(this).fadeOut('slow'), 2000); 
        });

        // Populate modal fields with data attributes
        $('.edit-item-btn').on('click', function() {
            var pack = $(this).data('pack');
            var qty = $(this).data('qty');
            var name = $(this).data('name');
            var fnsku = $(this).data('fnsku');
            var detail_id = $(this).data('id');

            $('#edit_pack').val(pack);
            $('#edit_qty').val(qty);
            $('#name').text(name);
            $('#fnsku').text(fnsku);
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
        $('#editForm').on('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent form submission on Enter key press

                    // Check if FNSKU and QTY fields have values
                    var fnsku = $('#fnsku-input').val().trim();
                    var qty = $('input[name="qty"]').val().trim();

                    if (fnsku && qty) {
                        $('#editForm').submit(); // Submit the form
                    } else {
                        alert('Please fill in both FNSKU and QTY fields.');
                    }
                }
            });
    });


</script>
@endsection