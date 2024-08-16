@extends('layouts.app')

@section('title', 'Import Products | Prepcenter')

@section('styles')
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Import CSV File</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="col-md-5">
                                <form action="#" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label for="file">Select CSV File:</label>
                                    <input class="form-control" id="show_file" name="show_file" type="file" accept=".csv">

                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="submit" class="btn btn-primary me-2">Upload</button>
                                        <a class="btn btn-danger">Cancel / Back</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <table id="example1" class="csvTable table table-striped align-middle d-none" style="width:100%">
                                <thead>
                                    <tr id="tableHeaders">
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    
    <!-- end page title -->

</div>

@endsection

@section('script')
<script>
    // $(document).ready(function() {
    //     $('#success-alert').each(function() {
    //         setTimeout(() => $(this).fadeOut('slow'), 3000); // 3000 milliseconds = 3 seconds
    //     });

    //     // Set a timeout for the error alert
    //     $('#error-alert').each(function() {
    //         setTimeout(() => $(this).fadeOut('slow'), 3000); // 3000 milliseconds = 3 seconds
    //     });
    // });

    $(document).ready(function() {
        $('#show_file').on('change', function(event) {
            var file = event.target.files[0];
            if (file && file.type === 'text/csv') {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var text = e.target.result;
                    var rows = text.split('\n').map(function(row) {
                        return row.split(',');
                    });
                    var headers = rows[0];
                    var $tableHeaders = $('#tableHeaders');
                    var $tableBody = $('#tableBody');
                    var $csvTable = $('.csvTable');

                    // Show the table
                    $csvTable.show();

                    // Clear previous content
                    $tableHeaders.empty();
                    $tableBody.empty();

                    // Create table headers
                    headers.forEach(function(header) {
                        $tableHeaders.append('<th>' + header + '</th>');
                    });

                    // Create table rows
                    rows.slice(1).forEach(function(row) {
                        var $tr = $('<tr>');
                        row.forEach(function(cell) {
                            $tr.append('<td>' + cell + '</td>');
                        });
                        $tableBody.append($tr);
                    });
                };
                reader.readAsText(file);
            } else {
                alert('Please upload a CSV file.');
                $('.csvTable').hide(); // Hide the table if the file is not a CSV
            }
        });
    });
</script>
@endsection