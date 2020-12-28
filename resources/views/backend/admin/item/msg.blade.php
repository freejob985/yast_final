@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.message.message') }}</h1>
            <p class="mb-4">{{ __('backend.message.message-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.message.id') }}</th>
                                <th>{{ __('backend.message.subject') }}</th>
                                <th>المحتوي</th>
                                <th>الرابط</th>
                                <th>الدعوة من</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.message.id') }}</th>
                                <th>{{ __('backend.message.subject') }}</th>
                                <th>المحتوي</th>
                                <th>الرابط</th>
                                <th>الدعوة من</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($Invitations as $key => $thread)
                                <tr>
                                    <td>{{ $thread->id }}</td>
                                    <td>{{ $thread->Title }}</td>
                                    <td>{{ $thread->Title }}</td>
                                    <td><a href="https://yasta.net/item/{{ $thread->Link	 }}" class="btn btn-success">الرابط</a></td>  
                                    <td>{{ $thread->Calling }}</td>                               
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endsection
