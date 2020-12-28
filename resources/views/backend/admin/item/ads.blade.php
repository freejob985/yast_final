@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.item') }}</h1>
            <p class="mb-4">{{ __('backend.item.item-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.item.add-item') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12"><span class="text-lg">{{ __('backend.shared.data-filter') }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <form class="form-inline" action="{{ route('admin.items.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="category">
                                        <option value="0">{{ __('backend.item.select-category') }}</option>
                                        @foreach($all_categories as $key => $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="state">
                                        <option value="0">{{ __('backend.item.select-state') }}</option>
                                        @foreach($all_states as $key => $state)
                                            <option value="{{ $state->id }}" {{ $state->id == $state_id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">{{ __('backend.shared.update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.item.id') }}</th>
                                <th>{{ __('backend.category.category') }}</th>
                                <th>{{ __('backend.item.title') }}</th>
                                <th>{{ __('backend.item.address') }}</th>
                                <th>{{ __('backend.city.city') }}</th>
                                <th>{{ __('backend.state.state') }}</th>
                                <th>{{ __('backend.item.status') }}</th>
                                <th>{{ __('backend.item.featured') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.item.id') }}</th>
                                <th>{{ __('backend.category.category') }}</th>
                                <th>{{ __('backend.item.title') }}</th>
                                <th>{{ __('backend.item.address') }}</th>
                                <th>{{ __('backend.city.city') }}</th>
                                <th>{{ __('backend.state.state') }}</th>
                                <th>{{ __('backend.item.status') }}</th>
                                <th>{{ __('backend.item.featured') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($items as $key => $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->category->category_name }}</td>
                                    <td>{{ $item->item_title }}</td>
                                    <td>{{ $item->item_address }}</td>
                                    <td>{{ $item->city->city_name }}</td>
                                    <td>{{ $item->state->state_name }}</td>
                                    <td>
                                        @if($item->item_status == \App\Item::ITEM_SUBMITTED)
                                            <span class="text-warning">{{ __('backend.item.submitted') }}</span>
                                        @elseif($item->item_status == \App\Item::ITEM_PUBLISHED)
                                            <span class="text-success">{{ __('backend.item.published') }}</span>
                                        @elseif($item->item_status == \App\Item::ITEM_SUSPENDED)
                                            <span class="text-danger">{{ __('backend.item.suspended') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->item_featured == 1 ? 'Featured' : '' }}</td>
                                    <td>
                                        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-primary mb-1">
                                            {{ __('backend.shared.edit') }}
                                        </a>
                                        @if($item->item_status == \App\Item::ITEM_PUBLISHED)
                                            <form action="{{ route('admin.items.disapprove', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-warning mb-1">{{ __('backend.shared.disapprove') }}</button>
                                            </form>

                                            <form action="{{ route('admin.items.suspend', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('backend.shared.suspend') }}</button>
                                            </form>
                                        @elseif($item->item_status == \App\Item::ITEM_SUBMITTED)
                                            <form action="{{ route('admin.items.approve', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success mb-1">{{ __('backend.shared.approve') }}</button>
                                            </form>

                                            <form action="{{ route('admin.items.suspend', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('backend.shared.suspend') }}</button>
                                            </form>
                                        @elseif($item->item_status == \App\Item::ITEM_SUSPENDED)
                                            <form action="{{ route('admin.items.approve', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success mb-1">{{ __('backend.shared.approve') }}</button>
                                            </form>

                                            <form action="{{ route('admin.items.disapprove', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-warning">{{ __('backend.shared.disapprove') }}</button>
                                            </form>
                                        @endif
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
        });
    </script>
@endsection
