@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.subscription.subscription') }}</h1>
            <p class="mb-4">{{ __('backend.subscription.subscription-desc-user') }}</p>
        </div>
          @if(!empty($subscription->id))
        <div class="col-3 text-right">
            <a href="{{ route('user.subscriptions.edit', $subscription->id) }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.subscription.switch-plan') }}</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row mb-4">
                <div class="col-6">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800">{{ __('backend.plan.plan-info') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {{ __('backend.plan.plan-name') }}:
                        </div>
                          @if(!empty($subscription->plan->plan_name))
                        <div class="col-8">
                            {{ $subscription->plan->plan_name }}
                        </div>
                          @endif
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {{ __('backend.plan.plan-type') }}:
                        </div>
                           @if(!empty($subscription->plan->plan_name))
                        <div class="col-8">
                            @if($subscription->plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                {{ __('backend.plan.free-plan') }}
                            @else
                                {{ __('backend.plan.paid-plan') }}
                            @endif
                        </div>
                         @endif
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {{ __('backend.plan.features') }}:
                        </div>
                           @if(!empty($subscription->plan->plan_name))
                        <div class="col-8">
                            {{ $subscription->plan->plan_features }}
                        </div>
                          @endif
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {{ __('backend.plan.price') }}:
                        </div>
                         @if(!empty($subscription->plan->plan_name))
                        <div class="col-8">
                            {{ $subscription->plan->plan_price }}
                        </div>
                          @endif
                    </div>
                    <div class="row">
                        <div class="col-4">
                            {{ __('backend.plan.period') }}:
                        </div>
                        <div class="col-8">
                            @if($subscription->plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                {{ __('backend.plan.lifetime') }}
                            @elseif($subscription->plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                {{ __('backend.plan.monthly') }}
                            @elseif($subscription->plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                {{ __('backend.plan.quarterly') }}
                            @elseif($subscription->plan->plan_period == \App\Plan::PLAN_YEARLY)
                                {{ __('backend.plan.yearly') }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800">{{ __('backend.subscription.subscription') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            {{ __('backend.plan.maximum-featured-listing') }}:
                        </div>
                        <div class="col-7">
                            {{ $subscription->subscription_max_featured_listing >= 0 ? $subscription->subscription_max_featured_listing : __('backend.plan.unlimited') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            {{ __('backend.subscription.started-at') }}:
                        </div>
                        <div class="col-7">
                            {{ $subscription->subscription_start_date }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            {{ __('backend.subscription.end-at') }}:
                        </div>
                        <div class="col-7">
                            {{ $subscription->subscription_end_date }}
                        </div>
                    </div>
                    @if($subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID)
                    <div class="row mt-3">
                        <div class="col-12">
                            <form method="POST" action="{{ route('user.paypal.recurring.cancel') }}">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                <button type="submit" class="btn btn-danger text-white">
                                    {{ __('backend.subscription.cancel-subscription') }}
                                </button>
                                <small class="form-text text-muted">
                                    {{ __('backend.subscription.cancel-subscription-help') }}
                                </small>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <hr/>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800">Invoices</span>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>{{ __('backend.subscription.invoice-num') }}</th>
                                            <th>{{ __('backend.subscription.title') }}</th>
                                            <th>{{ __('backend.subscription.description') }}</th>
                                            <th>{{ __('backend.subscription.amount') }}</th>
                                            <th>{{ __('backend.subscription.status') }}</th>
                                            <th>{{ __('backend.subscription.date') }}</th>
                                            <th>{{ __('backend.shared.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>{{ __('backend.subscription.invoice-num') }}</th>
                                            <th>{{ __('backend.subscription.title') }}</th>
                                            <th>{{ __('backend.subscription.description') }}</th>
                                            <th>{{ __('backend.subscription.amount') }}</th>
                                            <th>{{ __('backend.subscription.status') }}</th>
                                            <th>{{ __('backend.subscription.date') }}</th>
                                            <th>{{ __('backend.shared.action') }}</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        @foreach($invoices as $key => $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_num }}</td>
                                                <td>{{ $invoice->invoice_item_title }}</td>
                                                <td>{{ $invoice->invoice_item_description }}</td>
                                                <td>{{ $invoice->invoice_amount }}</td>
                                                <td>{{ $invoice->invoice_status }}</td>
                                                <td>{{ $invoice->created_at }}</td>
                                                <td>
                                                    <a href="" class="btn btn-primary btn-circle">
                                                        <i class="fas fa-cog"></i>
                                                    </a>
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
