@extends('layouts.app')

@section('title', 'Add Employee | Prepcenter')

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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Add Employee</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form id="employeeForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <label for="">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Phone</label>
                                <input type="number" name="phone_no" class="form-control">
                                @error('phone_no')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Privilege</label>
                                <div class="d-flex">
                                    <div class="form-check ms-2">
                                        <input class="form-check-input" type="radio" name="role" id="1" value="2">
                                        <label class="form-check-label" for="2">
                                        Manager
                                        </label>
                                    </div>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input" type="radio" name="role" id="2" value="3">
                                        <label class="form-check-label" for="3">
                                        User
                                        </label>
                                    </div>
                                    @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Department</label>
                                <select class="form-select" name="department">
                                    <option value="" disabled>- Select Department -</option>
                                    <option value="1">one</option>
                                    <option value="2">two</option>
                                </select>
                                @error('department')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Status</label>
                                <div class="d-flex">
                                    <div class="form-check ms-2">
                                        <input class="form-check-input" type="radio" name="status" id="0" value="0">
                                        <label class="form-check-label" for="active">
                                        Active
                                        </label>
                                    </div>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input" type="radio" name="status" id="1" value="1">
                                        <label class="form-check-label" for="not_active">
                                        Not Active
                                        </label>
                                    </div>
                                </div>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Rate</label>
                                <input type="text" name="rate" class="form-control">
                                @error('rate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-danger" id="resetButton">RESET</button>
                                    <button type="submit" class="btn btn-primary ms-2">SAVE</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- end page title -->

</div>

@endsection

@section('script')
<script>
     $(document).ready(function() {
        $('#resetButton').click(function() {
            $('#employeeForm')[0].reset(); // [0] is used to access the DOM element
        });

        $('#employeeForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create FormData object
            let formData = new FormData(this);

            // Send AJAX request
            $.ajax({
                url: "{{ route('employees.store') }}",
                type: 'POST',
                data: formData,
                contentType: false, // Tell jQuery not to set content type
                processData: false, // Tell jQuery not to process data
                success: function(response) {
                    if (response.success) {
                        window.location.href = "{{ route('employees.index') }}"; // Redirect to the URL
                    } else {
                        alert('An error occurred.');
                    }
                    console.log(data.success);
                },
                error: function(xhr) {
                    // Handle error response
                    let errors = xhr.responseJSON.errors;
                    
                }
            });
        });
    });
</script>
@endsection