@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.category.add-category') }}</h1>
            <p class="mb-4">{{ __('backend.category.add-category-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="p-5" enctype="multipart/form-data">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="category_name" class="text-black">{{ __('backend.category.category-name') }}</label>
                                <input id="category_name" type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" value="{{ old('category_name') }}" autofocus>
                                @error('category_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="category_icon">{{ __('backend.category.category-icon') }}</label>
                                <input id="category_icon" type="text" class="form-control @error('category_icon') is-invalid @enderror" name="category_icon" value="{{ old('category_icon') }}">
                                <small class="text-muted">
                                    {!! __('backend.category.category-icon-help') !!}
                                </small>
                                @error('category_icon')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
   <div class="row form-group">
                            <div class="col-md-12">
                                
  <input type="file" name="fileToUpload" id="fileToUpload">
     </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection
