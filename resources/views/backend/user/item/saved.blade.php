@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.saved-item') }}</h1>
            <p class="mb-4">{{ __('backend.item.saved-item-desc') }}</p>
        </div>
         
        @if (Auth::check())
        @if(Auth::user()->Type==="2" or Auth::user()->Type==="2" )
        <div class="col-3 text-right">
            <a href="{{ route('user.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.item.add-item') }}</span>
            </a>
        </div>
        @endif
        @endif

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
                                <th>{{ __('backend.category.category') }}</th>
                                <th>{{ __('backend.item.title') }}</th>
                                <th>{{ __('backend.item.address') }}</th>
                                <th>{{ __('backend.city.city') }}</th>
                                <th>{{ __('backend.state.state') }}</th>
                                <th>{{ __('backend.item.featured') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.category.category') }}</th>
                                <th>{{ __('backend.item.title') }}</th>
                                <th>{{ __('backend.item.address') }}</th>
                                <th>{{ __('backend.city.city') }}</th>
                                <th>{{ __('backend.state.state') }}</th>
                                <th>{{ __('backend.item.featured') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($saved_items as $key => $item)
                                <tr>
                                    <td>{{ $item->category->category_name }}</td>
                                    <td>{{ $item->item_title }}</td>
                                    <td>{{ $item->item_address_hide == \App\Item::ITEM_ADDR_HIDE ? '' : $item->item_address }}</td>
                                    <td>{{ $item->city->city_name }}</td>
                                    <td>{{ $item->state->state_name }}</td>
                                    <td>{{ $item->item_featured == 1 ? 'Featured' : '' }}</td>
                                    <td>
                                        <a href="{{ route('page.item', $item->item_slug) }}" class="btn btn-sm btn-primary mb-1 rounded-circle" target="_blank">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        <a class="btn btn-sm mb-1 btn-secondary rounded-circle text-white saved-item-remove-button" id="saved-item-remove-button-{{ $item->id }}"><i class="far fa-trash-alt"></i></a>
                                        <form id="saved-item-remove-button-{{ $item->id }}-form" action="{{ route('user.items.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                            @csrf
                                        </form>
                                    </td>
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

            $('.saved-item-remove-button').on('click', function(){
                $(this).addClass("disabled");
                $("#" + $(this).attr('id') + "-form").submit();
            });
        });
    </script>
@endsection
