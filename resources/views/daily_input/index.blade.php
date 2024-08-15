@extends('layouts.app')

@section('title', 'Daily Inputs | Prepcenter')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<div class="container-fluid">

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
    
    <div class="row mb-3">
        <form id="search_form" action="{{ route('daily-input.index') }}" method="GET" class="d-flex align-items-center">
            @csrf
            <div class="col-md-3 p-3">
                <label for="date-input">
                    Select Date Range:
                </label>
                <input type="text" name="daterange" id="daterange" class="form-control" value="{{ request('daterange') }}" placeholder="MM/DD/YYYY - MM/DD/YYYY">
            </div>
            <div class="col-md-3 d-flex pt-4 px-3">
                <button type="submit" class="btn btn-primary me-2">Search</button>
                <button type="button" class="btn btn-danger" id="resetButton">Clear</button>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Daily Inputs</h5>
                    <div class="add-btn">
                        <a href="{{ route('daily-input.create') }}" class="btn btn-primary">Add Daily Input</a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        use Carbon\Carbon;
                    @endphp
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Working Date</th>
                                <th>Employee Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Rate / Hour</th>
                                <th>Total Time</th>
                                <th>Total Paid</th>
                                <th>QTY</th>
                                <th>PC / Item</th>
                                <th>Item /Hour</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily_inputs as $daily_input)
                            <tr>
                                <td>{{ Carbon::parse($daily_input->date)->format('D, F j, Y') }}</td>
                                <td>{{ $daily_input->user->name ?? 'N/A' }}</td>
                                <td>{{ $daily_input->start_time }}</td>
                                <td>{{ $daily_input->end_time }}</td>
                                <td>${{ $daily_input->rate }}</td>
                                @php
                                    $totalTimeInSeconds = $daily_input->total_time_in_sec;
                                    $hours = intdiv($totalTimeInSeconds, 3600); // Total hours
                                    $minutes = intdiv($totalTimeInSeconds % 3600, 60); // Remaining minutes
                                @endphp
                                <td>{{ $hours }} H {{ $minutes }} m</td>
                                <td>${{ $daily_input->total_paid }}</td>
                                <td>{{ $daily_input->total_qty ?? 0}}</td>
                                <td>{{ number_format($daily_input->total_packing_cost, 3) }}</td>
                                <td>{{ number_format($daily_input->total_item_hour, 2) }}</td>
                                <td>
                                    <a href="{{ route('daily-input.show', $daily_input->id) }}" class="btn btn-primary p-1 m-0">
                                        <i class="ri-eye-fill align-bottom me-2"></i> View
                                    </a>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'right',
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    }, function(start, end) {
        $('input[name="daterange"]').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));

        $('input[name="start_date"]').remove();
        $('input[name="end_date"]').remove();

        $('<input>').attr({
            type: 'hidden',
            name: 'start_date',
            value: start.format('YYYY-MM-DD')
        }).appendTo('#search_form');

        $('<input>').attr({
            type: 'hidden',
            name: 'end_date',
            value: end.format('YYYY-MM-DD')
        }).appendTo('#search_form');
    });

    $('#resetButton').click(function() {
        $('#daterange').val('');
        $('input[name="start_date"]').remove();
        $('input[name="end_date"]').remove();
        window.location.href = "{{ route('daily-input.index') }}";
    });

    $('#scroll-horizontal').DataTable({
        "ordering": false // Disables sorting for all columns
    });
});

</script>
@endsection