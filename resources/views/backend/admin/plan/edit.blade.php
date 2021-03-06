@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.plan.edit-plan') }}</h1>
            <p class="mb-4">{{ __('backend.plan.edit-plan-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.plans.index') }}" class="btn btn-info btn-icon-split">
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
                    <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" class="p-5">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                <span>{{ __('backend.plan.plan-type') }}: </span>
                                @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                    <span class="text-gray-800">{{ __('backend.plan.free-plan') }}</span>
                                @else
                                    <span class="text-gray-800">{{ __('backend.plan.paid-plan') }}</span>
                                @endif

                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_name" class="text-black">{{ __('backend.plan.name') }}</label>
                                <input id="plan_name" type="text" class="form-control @error('plan_name') is-invalid @enderror" name="plan_name" value="{{ old('plan_name') ? old('plan_name') : $plan->plan_name }}" autofocus>
                                @error('plan_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="plan_features">{{ __('backend.plan.features') }}</label>
                                <textarea rows="6" id="plan_features" type="text" class="form-control @error('plan_features') is-invalid @enderror" name="plan_features">{{ old('plan_features') ? old('plan_features') : $plan->plan_features }}</textarea>
                                @error('plan_features')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="plan_period" class="text-black">{{ __('backend.plan.billing-period') }}</label>

                                    <select class="custom-select" name="plan_period">
                                        <option value="30" {{ ( $plan->plan_period == '30') ? 'selected' : '' }} >
                                            {{ __('backend.plan.monthly') }}
                                        </option>
                                        <option value="90"  {{ ( $plan->plan_period == '90') ? 'selected' : '' }} >
                                            {{ __('backend.plan.quarterly') }}
                                        </option>
                                        <option value="360" {{ ( $plan->plan_period == '360') ? 'selected' : '' }}>
                                            {{ __('backend.plan.yearly') }}
                                        </option>
                                                                             <option value="180" {{ ( $plan->plan_period == '180') ? 'selected' : '' }}>
                                نصف سنوي
                                    </option>
                                    

                                    </select>
                                    @error('plan_period')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @endif



                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="link" class="text-black">رابط الفاتورة</label>
                                <input id="link" type="text" class="form-control @error('link') is-invalid @enderror" name="link" value="{{ $plan->link}}" autofocus>
                                @error('link')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label for="plan_max_featured_listing" class="text-black">{{ __('backend.plan.maximum-featured-listing') }}</label>
                                    <input id="plan_max_featured_listing" type="text" class="form-control @error('plan_max_featured_listing') is-invalid @enderror" name="plan_max_featured_listing" value="{{ old('plan_max_featured_listing') ? old('plan_max_featured_listing') : $plan->plan_max_featured_listing }}">
                                    @error('plan_max_featured_listing')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_price" class="text-black">{{ __('backend.plan.price') }}</label>
                                <input id="plan_price" type="text" class="form-control @error('plan_price') is-invalid @enderror" name="plan_price" value="{{ old('plan_price') ? old('plan_price') : $plan->plan_price }}">
                                @error('plan_price')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        @if($plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="plan_status" class="text-black">{{ __('backend.plan.status') }}</label>

                                <select class="custom-select" name="plan_status">
                                    <option value="{{ \App\Plan::PLAN_ENABLED }}" {{ (old('plan_status') ? old('plan_status') : $plan->plan_status) == \App\Plan::PLAN_ENABLED ? 'selected' : '' }}>
                                        {{ __('backend.plan.enabled') }}
                                    </option>
                                    <option value="{{ \App\Plan::PLAN_DISABLED }}" {{ (old('plan_status') ? old('plan_status') : $plan->plan_status) == \App\Plan::PLAN_DISABLED ? 'selected' : '' }}>
                                        {{ __('backend.plan.disabled') }}
                                    </option>
                                </select>
                                @error('plan_status')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.plan'), 'record_name' => $plan->plan_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
@endsection
