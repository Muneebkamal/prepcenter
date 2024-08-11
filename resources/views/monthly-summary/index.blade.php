@extends('layouts.app')

@section('title', 'Monthly Summary | Prepcenter')

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
                <h4 class="mb-sm-0">Monthly Summary</h4>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <form id="search_form" action="{{ route('summary.search') }}" method="POST" class="d-flex align-items-center">
            @csrf
            <div class="col-md-3 p-3">
                <label for="month-input">
                    Select Month:
                </label>
                <input type="month" class="form-control" value="{{ request('filter_month') }}" id="month-input" name="filter_month">
            </div>
            <div class="col-md-3 p-3">
                <label for="employee-select">
                    Select Employee:
                </label>
                <select class="form-select" id="employee-select" name="employee_id">
                    <option value="" selected disabled>Select Employee</option>
                    <option value="all" {{ request('employee_id') === 'all' ? 'selected' : '' }}>All Employees</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
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
                    <h5 class="card-title mb-0">Please Select Employee and Month First.</h5>
                </div>
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th data-ordering="false">Working Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Time</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                            @php
                                use Carbon\Carbon;
                            @endphp
                        <tbody>
                            @foreach($monthly_summary as $summary)
                            <tr>
                                <td>{{ Carbon::parse($summary->date)->format('D, F j, Y') }}</td>
                                <td>{{ $summary->start_time }}</td>
                                <td>{{ $summary->end_time }}</td>
                                @php
                                    $totalTimeInSeconds = $summary->total_time_in_sec;
                                    $hours = intdiv($totalTimeInSeconds, 3600);
                                    $minutes = intdiv($totalTimeInSeconds % 3600, 60);
                                @endphp
                                <td>{{ $hours }} H {{ $minutes }} m</td>
                                <td>
                                    <button class="btn btn-success">UPDATE TIME</button>
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
        $('#resetButton').click(function() {
            $('#search_form')[0].reset();
            $('#employee-select').val('');
            $('#month-input').val('');
            window.location.href = "{{ route('monthly.summary') }}";
            $('#search_form').submit();
        });
    });
</script>
@endsection