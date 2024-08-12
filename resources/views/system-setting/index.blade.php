@extends('layouts.app')

@section('title', 'System Setting | Prepcenter')

@section('content')

<div class="container-fluid">
                        
    <!-- start page title -->
    {{-- <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Monthly Summary</h4>
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

    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">System Setting</h4>
        </div>
        <div class="card-body">
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
    </div>
    
    
</div>

@endsection
