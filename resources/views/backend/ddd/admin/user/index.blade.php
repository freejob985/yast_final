@extends('backend.admin.layouts.app')

@section('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet">



@endsection

@section('content')

<div class="row justify-content-between">
    <div class="col-9">
        <h1 class="h3 mb-2 text-gray-800">{{ __('backend.user.user') }}</h1>
        <p class="mb-4">{{ __('backend.user.user-desc') }}</p>
    </div>
    <div class="col-3 text-right">
        <a href="{{ route('admin.users.create') }}" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">{{ __('backend.user.add-user') }}</span>
        </a>
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
                                <th>{{ __('backend.user.id') }}</th>
                                <th>{{ __('backend.user.name') }}</th>
                                <th>{{ __('backend.user.email') }}</th>
                                <th>{{ __('backend.user.email-verified') }}</th>
                                <th>{{ __('backend.user.role') }}</th>
                                <th>{{ __('backend.user.created-at') }}</th>
                                <th>{{ __('backend.user.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                                <th>الموبيل</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>{{ __('backend.user.id') }}</th>
                                <th>{{ __('backend.user.name') }}</th>
                                <th>{{ __('backend.user.email') }}</th>
                                <th>{{ __('backend.user.email-verified') }}</th>
                                <th>{{ __('backend.user.role') }}</th>

                                <th>{{ __('backend.user.created-at') }}</th>
                                <th>{{ __('backend.user.status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                                <th>الموبيل</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($all_users as $key => $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if(empty($user->email_verified_at))
                                    <span
                                        class="bg-warning text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.pending') }}</span>
                                    @else
                                    <span
                                        class="bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.verified') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->Type==="1")
                                    صنايعي
                                    @elseif ($user->Type==="2")
                                    شركة
                                    @else
                                    العميل
                                    @endif
                                </td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                    @if($user->user_suspended == \App\User::USER_SUSPENDED)
                                    <span
                                        class="bg-danger text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.suspended') }}</span>
                                    @else
                                    <span
                                        class="bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-primary btn-circle mb-1">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    @if($user->user_suspended == \App\User::USER_NOT_SUSPENDED)
                                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="btn btn-sm btn-danger mb-1">{{ __('backend.shared.suspend') }}</button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="btn btn-sm btn-success mb-1">{{ __('backend.shared.un-lock') }}</button>
                                    </form>
                                    @endif
                                </td>
                                <td>{{ $user->mobil }}</td>
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


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>









<script>

    
    $(document).ready(function() {
    $('#dataTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
  </script>



@endsection