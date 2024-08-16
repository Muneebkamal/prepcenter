@extends('layouts.app')

@section('title', 'Emloyees | Prepcenter')

@section('content')

<div class="container-fluid">
                        
    <!-- start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Employee</h4>

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
        <div class="col-lg-12 p-0">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Employees</h5>
                    <div class="add-btn">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th><small>Full Name</small></th>
                                <th><small>Department</small></th>
                                <th><small>Privilege</small></th>
                                <th><small>Status</small></th>
                                <th><small>Action</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>
                                        <small class="fw-bold m-0"><a class="text-dark text-decoration-none" href="{{ route('employees.show', $employee->id) }}">{{ $employee->name }}</a></small><br>
                                        <small class="m-0"><a href="{{ route('employees.show', $employee->id) }}">{{ $employee->email }}</a></small>
                                    </td>
                                    <td>
                                        <small class="m-0">
                                            @if($employee->departments)
                                                {{ $employee->departments->dep_name ?? '--' }}
                                            @else
                                                No Department
                                            @endif
                                        </small>
                                    <td>
                                        @if($employee->role == 1)
                                            <small class="m-0">Manager</small>
                                        @elseif($employee->role == 2)
                                            <small class="m-0">User</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->status == '0')
                                            <div class="badge rounded-pill bg-primary">Active</div>
                                        @elseif($employee->status == '1')
                                            <div class="badge rounded-pill bg-dark">Not Active</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-success edit-item-btn me-1 p-1"><i class="ri-pencil-fill align-bottom"></i> Edit</a>
                                            <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger remove-item-btn p-1">
                                                    <i class="ri-delete-bin-fill align-bottom"></i> Delete
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
                                                    <a href="{{ route('employees.show', $employee->id) }}" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" style="display:inline;">
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
        $('#example1').DataTable({
            "ordering": false
        });
    });
</script>
@endsection