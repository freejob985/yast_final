@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        
        
        
                           @if (Auth::check())
                        @if(Auth::user()->Type==="1" or Auth::user()->isAdmin())
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.items.create') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>
                            @else
                                <a href="{{ route('user.items.create') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>
                            @endif
                              @endif
                               @endif
                          
                            
                            
                            
                  
                        
                        
                          @if (Auth::check())
                         @if(Auth::user()->Type==="2" or Auth::user()->isAdmin() )
                         @if(Auth::user()->isAdmin())
                         <a href="/admin/items/create?category=7" class="btn btn-success py-2 px-4 text-white">{{ __('frontend.header.a2') }}</a>
                                                         <a href="{{ route('admin.items.create') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>

                            @else
                         <a href="/user/items/create?category=7" class="btn btn-success py-2 px-4 text-white">{{ __('frontend.header.a2') }}</a>
                                                         <a href="{{ route('user.items.create') }}" class="cta"><span class="bg-primary text-white rounded"><i class="fas fa-plus mr-1"></i> {{ __('frontend.header.list-business') }}</span></a>

                            @endif
                            
                             @else

                          @endif
                           @endif
                           
                           
                                  
                          @if (Auth::check())
                         @if(Auth::user()->Type==="3" or Auth::user()->isAdmin() )
                         @if(Auth::user()->isAdmin())
                         <a href="/admin/items/create?category=7" class="btn btn-success py-2 px-4 text-white">{{ __('frontend.header.a2') }}</a>

                            @else
                         <a href="/user/items/create?category=7" class="btn btn-success py-2 px-4 text-white">{{ __('frontend.header.a2') }}</a>

                            @endif
                            
                             @else

                          @endif
                           @endif
                           
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('backend.homepage.pending-listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending_item_count }}</div>
                            
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('backend.homepage.all-listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $item_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('backend.homepage.all-messages') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $message_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
           @if(Auth::user()->Type==="2" or Auth::user()->Type==="3" )
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><a href="{{ route('user.msg') }}">الدعوات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="{{ route('user.msg') }}">{{ DB::table('Invitations')->where('User', Auth::user()->id)->count() }}</a></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          @endif
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('backend.homepage.all-comments') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $comment_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('backend.homepage.message') }}</h6>
                </div>
                <div class="card-body">

                    @foreach($recent_threads as $key => $thread)
                        <div class="row pt-2 pb-2 {{ $key%2 == 0 ? 'bg-light' : '' }}">
                            <div class="col-9">
                                <span>{{ $thread->latestMessage->body }}</span>
                            </div>
                            <div class="col-3 text-right">
                                <span>{{ $thread->latestMessage->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="{{ route('user.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('backend.homepage.comment') }}</h6>
                </div>
                <div class="card-body">

                    @foreach($recent_comments as $key => $comment)
                        <div class="row pt-2 pb-2 {{ $key%2 == 0 ? 'bg-light' : '' }}">
                            <div class="col-9">
                                <span>{{ $comment->comment }}</span>
                            </div>
                            <div class="col-3 text-right">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="{{ route('user.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
