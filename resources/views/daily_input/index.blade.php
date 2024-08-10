@extends('layouts.app')

@section('title', 'Daily Inputs | Prepcenter')

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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily_inputs as $daily_input)
                            <tr>
                                <td>{{ Carbon::parse($daily_input->date)->format('l F j, Y') }}</td>
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
                                    <a href="{{ route('daily-input.show', $daily_input->id) }}" class="btn btn-primary">
                                        <i class="ri-eye-fill align-bottom me-2"></i> View
                                    </a>
                                    {{-- <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li>
                                                <a href="{{ route('daily-input.show', $daily_input->id) }}" class="dropdown-item edit-item-btn"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                            </li>
                                            <li>
                                                <form method="POST" action="#" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
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
        $('#scroll-horizontal').DataTable();
    });
</script>
@endsection