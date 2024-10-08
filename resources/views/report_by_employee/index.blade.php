@extends('layouts.app')

@section('title', 'Report By Employee | Prepcenter')

@section('styles')
<style>
    .dataTables_wrapper .dataTables_scrollBody {
        overflow-y: hidden !important;
        overflow-x: auto;
        height: auto;
    }

    #scroll-horizontal {
        height: auto;
    }
</style>
@endsection

@section('content')

<div class="container-fluid">
                        
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Report By Employee</h4>

                {{-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Basic Elements</li>
                    </ol>
                </div> --}}

            </div>
        </div>
    </div>

    <div class="row mb-3">
        <form id="search_form" action="{{ route('employee.search') }}" method="POST" class="d-flex align-items-center">
            @csrf
            <div class="col-md-3 p-3">
                <label for="filter-select">
                    Filter By:
                </label>
                <select class="form-select" id="filter-select" name="filter_by">
                    <option value="today" {{ request('filter_by', 'today') === 'today' ? 'selected' : '' }} >Today</option>
                    <option value="custom" {{ request('filter_by') === 'custom' ? 'selected' : '' }} >Custom Date</option>
                    <option value="this_week" {{ request('filter_by') === 'this_week' ? 'selected' : '' }} >This Week</option>
                    <option value="last_week" {{ request('filter_by') === 'last_week' ? 'selected' : '' }} >Last Week</option>
                    <option value="last_month" {{ request('filter_by') === 'last_month' ? 'selected' : '' }} >Last Month</option>
                    <option value="last_year" {{ request('filter_by') === 'last_year' ? 'selected' : '' }} >Last Year</option>
                </select>
            </div>
            <div class="col-md-3 p-3 d-none" id="date-div">
                <label for="date-input">
                    Select Date:
                </label>
                <input type="date" class="form-control" id="date-input" name="filter_date">
            </div>
            <div class="col-md-3 p-3">
                <label for="employee-select">
                    Select Employee:
                </label>
                <select class="form-select" id="employee-select" name="employee_id">
                    <option value="all" {{ request('employee_id') === 'all' ? 'selected' : '' }} >All Employees</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}"  {{ request('employee_id') == $employee->id ? 'selected' : '' }} >{{ $employee->name }}</option>
                    @endforeach
                    <!-- Add employee options here -->
                </select>
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
                    <h5 class="card-title mb-0">Please Select Date Range and Employee First.</h5>
                    {{-- <div class="add-btn">
                        <a href="{{ route('daily-input.create') }}" class="btn btn-primary">Add Daily Input</a>
                    </div> --}}
                </div>
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th data-ordering="false">Working Date</th>
                                <th>Employee Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Rate / Hour</th>
                                <th>Total Time</th>
                                <th>Total Paid</th>
                                <th>QTY</th>
                                <th>PC / Item</th>
                                <th>Item /Hour</th>
                            </tr>
                        </thead>
                            @php
                                use Carbon\Carbon;

                                $totalHours = 0;
                                $totalMinutes = 0;
                                $totalPaid = 0;
                                $totalQty = 0;
                                $totalPackingCost = 0;
                                $totalItemHour = 0;
                            @endphp
                        <tbody>
                            @foreach($report_by_employees as $employee)
                            <tr>
                                <td>{{ Carbon::parse($employee->date)->format('D, F j, Y') }}</td>
                                <td>{{ $employee->user->name ?? 'N/A' }}</td>
                                <td>{{ $employee->start_time }}</td>
                                <td>{{ $employee->end_time }}</td>
                                <td>${{ $employee->rate }}</td>
                                @php
                                    $totalTimeInSeconds = $employee->total_time_in_sec;
                                    $hours = intdiv($totalTimeInSeconds, 3600); // Total hours
                                    $minutes = intdiv($totalTimeInSeconds % 3600, 60); // Remaining minutes

                                    // Accumulate totals
                                    $totalHours += $hours;
                                    $totalMinutes += $minutes;
                                    $totalPaid += $employee->total_paid;
                                    $totalQty += $employee->total_qty ?? 0;
                                    $totalPackingCost += $employee->total_packing_cost;
                                    $totalItemHour += $employee->total_item_hour;
                                @endphp
                                <td>{{ $hours }} H {{ $minutes }} m</td>
                                <td>${{ $employee->total_paid }}</td>
                                <td>{{ $employee->total_qty ?? 0}}</td>
                                <td>{{ number_format($employee->total_packing_cost, 3) }}</td>
                                <td>{{ number_format($employee->total_item_hour, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5">Total</td>
                                <td>
                                    @php
                                        // Convert total minutes to hours and minutes
                                        $totalHours += intdiv($totalMinutes, 60);
                                        $totalMinutes = $totalMinutes % 60;
                                    @endphp
                                    {{ $totalHours }} H {{ $totalMinutes }} m
                                </td>
                                <td>${{ number_format($totalPaid, 2) }}</td>
                                <td>{{ number_format($totalQty, 0) }}</td>
                                <td>{{ number_format($totalPackingCost, 3) }}</td>
                                <td>{{ number_format($totalItemHour, 2) }}</td>
                            </tr>
                        </tfoot>
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
       

        $('#resetButton').click(function() {
            $('#search_form')[0].reset();
            
            $('#filter-select').val('today');
            $('#employee-select').val('all');
            $('#date-div').addClass('d-none');
            $('#search_form').submit();
        });


        const $filterSelect = $('#filter-select');
        const $dateDiv = $('#date-div');

        function toggleDateDiv() {
            if ($filterSelect.val() === 'custom') {
                $dateDiv.removeClass('d-none');
            } else {
                $dateDiv.addClass('d-none');
            }
        }

        $filterSelect.on('change', toggleDateDiv);
        toggleDateDiv();
    });
</script>
@endsection