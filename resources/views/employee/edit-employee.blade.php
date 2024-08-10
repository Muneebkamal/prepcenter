@extends('layouts.app')

@section('title', 'Edit Employee | Prepcenter')

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
                    <h4 class="card-title mb-0 flex-grow-1">Edit Employee</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')

                        @php
                            // Get the full name or default to an empty string
                            $fullName = $employee->name ?? '';

                            // Split by space and handle potential multiple spaces
                            $nameParts = preg_split('/\s+/', $fullName);
                            
                            // Get the first and last names or default to empty strings
                            $firstName = $nameParts[0] ?? '';
                            $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
                        @endphp
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $employee->id }}">
                            <div class="col-md-6 mt-2">
                                <label for="">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ htmlspecialchars($firstName) }}">
                                @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ htmlspecialchars($lastName) }}">
                                @error('last_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="">Phone</label>
                                <input type="number" name="phone_no" class="form-control" value="{{ $employee->phone_no }}">
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
                                        <input class="form-check-input" type="radio" name="role" id="2" value="2" {{ $employee->role == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="2">
                                        Manager
                                        </label>
                                    </div>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input" type="radio" name="role" id="3" value="3" {{ $employee->role == 3 ? 'checked' : '' }}>
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
                                    <option value="1" {{ $employee->department == 1 ? 'selected' : '' }}>one</option>
                                    <option value="2" {{ $employee->department == 2 ? 'selected' : '' }}>two</option>
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
                                        <input class="form-check-input" type="radio" name="status" id="active" value="active" {{ $employee->status == 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                        Active
                                        </label>
                                    </div>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input" type="radio" name="status" id="not_active" value="not_active" {{ $employee->status == 'not_active' ? 'checked' : '' }}>
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
                                <input type="text" name="rate" class="form-control" value="{{ $employee->rate }}">
                                @error('rate')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex justify-content-end mt-3">
                                    <button class="btn btn-danger" id="resetButton">RESET</button>
                                    <button type="submit" class="btn btn-primary ms-2">UPDATE</button>
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
            $('#editForm')[0].reset(); // [0] is used to access the DOM element
        });

        $('#editForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create FormData object
            let formData = new FormData(this);
            let id = $('input[name="id"]').val();
            // Send AJAX request
            $.ajax({
                url: "{{ route('employees.update', 'id') }}".replace('id', id), 
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false, 
                success: function(response) {
                    if (response.success) {
                        window.location.href = "{{ route('employees.index') }}"; // Redirect to the URL
                    } else {
                        alert('An error occurred.');
                    }
                    console.log(data.success);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                }
            });
        });
    });
</script>
@endsection