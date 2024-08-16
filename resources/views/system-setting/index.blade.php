@extends('layouts.app')

@section('title', 'System Setting | Prepcenter')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-xxl-6">
            <h5 class="mb-3">System Setting</h5>
            <div class="card">
                <div class="card-body">
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
    })
</script>
@endsection
