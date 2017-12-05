@extends('admin.template')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Nhập sản lượng của Network Loại SMS Cron Theo Ngày</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <h2>Add</h2>
           <form method="post" action="{{url('admin/report-submit')}}">

            <div class="form-group">
                <div class="form-group">
                    {!! Form::label('network_id', 'Network') !!}
                    {!! Form::select('network_id', ['' => 'Choose Network'] + \App\Site::getSmsCronNetwork(), null, ['class' => 'form-control']) !!}
                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </div>

               <div class="form-group">
                   {!! Form::label('date', 'Ngày có sản lượng') !!}
                   {!! Form::text('date', null, ['class' => 'form-control', 'id' => 'start-network-date']) !!}
               </div>

                <div class="form-group">
                    {!! Form::label('quantity', 'Số lượng') !!}
                    {!! Form::number('quantity', null, ['class' => 'form-control']) !!}
                </div>

            <div class="form-group">
                {!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
            </div>

           </form>

            @include('admin.list')

        </div>
    </div>
@endsection

@section('footer')
    <script>
        $(document).ready(function(){
            jQuery.datetimepicker.setLocale('vi');

            jQuery('#start-network-date').datetimepicker({
                i18n:{
                    vi:{
                        months:[
                            'Thang 1','Thang 2','Thang 3','Thang 4',
                            'Thang 5','Thang 6','Thang 7','Thang 8',
                            'Thang 9','Thang 10','Thang 11','Thang 12',
                        ],
                        dayOfWeek:[
                            "Chu Nhat", "Thu 2", "Thu 3", "Thu 4",
                            "Thu 5", "Thu 6", "Thu 7",
                        ]
                    }
                },
                timepicker:false,
                minDate:'1970-01-02',
                format:'Y-m-d'
            });
        });
    </script>
@endsection