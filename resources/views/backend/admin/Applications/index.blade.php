@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')


    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
    
                                <th>كود العملية</th>
                                <th>الباقة</th>
                                <th>السعر</th>
                                <th>المستخدم</th>
                                <th>عن طريق</th>
                                <th>التفعيل</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>كود العملية</th>
                                <th>الباقة</th>
                                <th>السعر</th>
                                <th>المستخدم</th>
                                <th>عن طريق</th>
                                <th>التفعيل</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_plans as $key => $plan)
                                <tr>
                                     <td>{{$plan->Code}}</td>
                                      <td>{{$plan->Package}}</td>
                                       <td>{{$plan->price}}</td>
                                        <td>{{$plan->User}}</td>
                                         <td>{{$plan->Type}}</td>
                                          <td>
                                              
                    <form action="{{ route('admin.CourseProgresss.quick',$plan->id) }}" method="POST">
                      {{ csrf_field() }}
                      <button  type="Submit" class="btn btn-xs {{ $plan->st ==1 ? 'btn-success' : 'btn-danger' }}">
                        @if($plan->st ==1)
                       مفعل
                        @else
                        غير مفعل
                        @endif
                      </button>
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
        });
    </script>
@endsection
