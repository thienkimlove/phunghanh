@extends('admin.template')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{strtoupper($model)}}</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            @if ($content->id)
                <h2>Edit</h2>
                {!! Form::model($content, ['method' => 'PATCH', 'route' => [$model.'.update', $content->id], 'files' => true]) !!}
            @else
                <h2>Add</h2>
                {!! Form::model($content, ['route' => [$model.'.store'], 'files' => true]) !!}
            @endif

            <div class="form-group">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>


                <div class="form-group">
                    {!! Form::label('click_url', 'Click Url') !!}
                    {!! Form::text('click_url', null, ['class' => 'form-control']) !!}
                </div>


                <div class="form-group">
                    {!! Form::label('callback_url', 'Callback Url') !!}
                    {!! Form::text('callback_url', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('map_params', 'Map Params') !!}
                    {!! Form::textarea('map_params', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('extend_params', 'Extend Params (Separate with comma)') !!}
                    {!! Form::text('extend_params', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('callback_allow_ip', 'CallBack Allow IPs') !!}
                    {!! Form::text('callback_allow_ip', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', array(1 => 'Active', 0 => 'Inactive'), null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('is_sms_callback', 'SMS mode') !!}
                    {!! Form::select('is_sms_callback', array(0 => 'Not using SMS callback', 1 => 'Partner call SMS update', 2 => 'System running cron to update'), null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('cron_url', 'Cron URL (In case SMS Callback Mode = System running cron to update )') !!}
                    {!! Form::text('cron_url', null, ['class' => 'form-control']) !!}
                </div>


                <div class="form-group">
                    {!! Form::label('auto', 'Auto Add 1 PostBack Every 10K') !!}
                    {!! Form::select('auto', array( 0 => 'Inactive', 1 => 'Active'), null, ['class' => 'form-control']) !!}
                </div>

            <div class="form-group">
                {!! Form::submit('Save', ['class' => 'btn btn-primary form-control']) !!}
            </div>

            {!! Form::close() !!}

            @include('admin.list')

        </div>
    </div>
@endsection