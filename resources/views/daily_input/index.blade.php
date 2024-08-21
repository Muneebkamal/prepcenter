@extends('layouts.app')

@section('title', 'Daily Inputs | Prepcenter')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style>
    .applyBtn {
        --vz-btn-bg: var(--vz-success);
        --vz-btn-border-color: var(--vz-success);
        --vz-btn-hover-bg: var(--vz-success-text-emphasis);
        --vz-btn-hover-border-color: var(--vz-success-text-emphasis);
        --vz-btn-focus-shadow-rgb: var(--vz-success-rgb);
        --vz-btn-active-bg: var(--vz-success-text-emphasis);
        --vz-btn-active-border-color: var(--vz-success-text-emphasis);
    }
</style>
@endsection

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
    
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <form id="search_form" action="{{ route('daily-input.index') }}" method="GET" class="d-flex align-items-center">
                @csrf
                    <div class="me-2">
                        <label for="date-input">
                            Select Date Range:
                        </label>
                        <div id="reportrange" class="reportrange p-2" style="background-color: white; border: var(--vz-border-width) solid var(--vz-input-border-custom); border-radius: var(--vz-border-radius);">
                            <span></span>
                            <b class="caret"></b>
                        </div>
                        <input type="hidden" id="date_range" name="date_range" />  
                    </div>
                    <div class="mt-4 d-flex">
                        <button type="submit" class="btn btn-primary me-2">Search</button>
                        <button type="button" class="btn btn-danger" id="resetButton">Clear</button>
                    </div>
            </form>
            <div class="add-btn mt-4">
                <a href="{{ route('daily-input.create') }}" class="btn btn-primary">Add Daily Input</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 p-0">
            <div class="card">
                {{-- <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Daily Inputs</h5>
                    <div class="add-btn">
                        <a href="{{ route('daily-input.create') }}" class="btn btn-primary">Add Daily Input</a>
                    </div>
                </div> --}}
                <div class="card-body">
                    @php
                        use Carbon\Carbon;
                    @endphp
                    <table id="daily-input" class="table table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th><small>Working Date</small></th>
                                <th><small>Employee Name</small></th>
                                <th><small>Start Time</small></th>
                                <th><small>End Time</small></th>
                                <th><small>Rate/Hour</small></th>
                                <th><small>Total Time</small></th>
                                <th><small>Total Paid</small></th>
                                <th><small>QTY</small></th>
                                <th><small>PC/Item</small></th>
                                <th><small>Item/Hour</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily_inputs as $daily_input)
                            <tr>
                                <td>
                                    <small>{{ Carbon::parse($daily_input->date)->format('D, M j, Y') }}</small>
                                </td>
                                <td>
                                    <small>{{ $daily_input->user->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small>{{ $daily_input->start_time }}</small>
                                </td>
                                <td>
                                    <small>{{ $daily_input->end_time }}</small>
                                </td>
                                <td>
                                    <small>${{ $daily_input->rate }}</small>
                                </td>
                                @php
                                    $totalTimeInSeconds = $daily_input->total_time_in_sec;
                                    $hours = intdiv($totalTimeInSeconds, 3600); // Total hours
                                    $minutes = intdiv($totalTimeInSeconds % 3600, 60); // Remaining minutes
                                @endphp
                                <td>
                                    <small>{{ $hours }} H {{ $minutes }} m</small>
                                </td>
                                <td>
                                    <small>${{ $daily_input->total_paid }}</small>
                                </td>
                                <td>
                                    <small>{{ $daily_input->total_qty ?? 1}}</small>
                                </td>
                                <td>
                                    <small>{{ number_format($daily_input->total_packing_cost, 3) }}</small>
                                </td>
                                <td>
                                    <small>{{ number_format($daily_input->total_item_hour, 2) }}</small>
                                </td>
                                <td class="d-flex">
                                    <a href="{{ route('daily-input.show', $daily_input->id) }}" class="btn btn-primary p-1 m-0 me-1">
                                        <i class="ri-eye-fill align-bottom me-1"></i> View
                                    </a>
                                    @if(Auth()->user()->role == 1)
                                        <form method="POST" action="{{ route('daily-input.destroy', $daily_input->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="d-flex btn btn-danger remove-item-btn  p-1 m-0">
                                                <i class="ri-delete-bin-fill align-bottom me-1"></i> Delete
                                            </button>
                                        </form>
                                    @endif
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
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('#daily-input').DataTable({
            "ordering": false
        });

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

        $('#success-alert').each(function() {
            setTimeout(() => $(this).fadeOut('slow'), 2000); // 3000 milliseconds = 3 seconds
        });

        // Set a timeout for the error alert
        $('#error-alert').each(function() {
            setTimeout(() => $(this).fadeOut('slow'), 2000); // 3000 milliseconds = 3 seconds
        });
    });
    $(function() {
        var start = {!! json_encode( $startDate) !!};
        var end = {!! json_encode( $endDate) !!};
        var start = moment(start, 'YYYY-MM-DD');
        var end = moment(end, 'YYYY-MM-DD');
        var dynamicStartDayNumber = {!! json_encode( $weekStart) !!};

        var today = moment();
        var startOfWeek = today.clone().startOf('week').add(dynamicStartDayNumber, 'days');
        if (startOfWeek.isAfter(today)) {
            startOfWeek.subtract(7, 'days');
        }
        var endOfWeek = startOfWeek.clone().add(6, 'days');
        var startOfLastWeek = startOfWeek.clone().subtract(7, 'days');
        var endOfLastWeek = endOfWeek.clone().subtract(7, 'days');

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $("#date_range").val(start.format('YYYY-M-D') + '_' + end.format('YYYY-M-D'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'This Week': [startOfWeek, endOfWeek],
                'Last Week': [startOfLastWeek, endOfLastWeek],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 3 Months': [moment().subtract(2, 'month').startOf('month'), moment().endOf('month')],
                'Last 6 Months': [moment().subtract(5, 'month').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
            }
        }, cb);

        cb(start, end);
    });

</script>
@endsection