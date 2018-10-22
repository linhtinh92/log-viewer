@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('logviewer::logviewers.title.logviewers') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i
                        class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('logviewer::logviewers.title.logviewers') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="list-group">
                                @foreach($data_logs['folders'] as $folder)
                                    <div class="list-group-item">
                                        <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                                            <span class="fa fa-folder"></span> {{$folder}}
                                        </a>
                                        @if ($data_logs['current_folder'] == $folder)
                                            <div class="list-group folder">
                                                @foreach($folder_files as $file)
                                                    <a href="?file={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&folder={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                                                       class="list-group-item @if ($data_logs['current_file'] == $file) label-danger @endif">
                                                        {{$file}}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @foreach($data_logs['files'] as $file)
                                    <a href="?file={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                                       class="list-group-item @if ($data_logs['current_file'] == $file) label-danger @endif">
                                        {{$file}}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="table-responsive">
                                <table class="data-table table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        @if ($data_logs['standardFormat'])
                                            <th>@lang('logviewer::logviewers.table.heading.level')</th>
                                            <th>@lang('logviewer::logviewers.table.heading.context')</th>
                                            <th>@lang('logviewer::logviewers.table.heading.date')</th>
                                        @else
                                            <th>@lang('logviewer::logviewers.table.heading.line number')</th>
                                        @endif
                                        <th>@lang('logviewer::logviewers.table.heading.content')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (isset($data_logs['logs'])): ?>
                                    <?php foreach ($data_logs['logs'] as $key=>$log): ?>
                                    <tr data-display="stack{{{$key}}}">
                                        @if ($data_logs['standardFormat'])
                                            <td class="nowrap text-{{{$log['level_class']}}}">
                                                <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                                            </td>
                                            <td class="text">{{$log['context']}}</td>
                                        @endif
                                        <td class="date">{{{$log['date']}}}</td>
                                        <td class="text">
                                            {{{$log['text']}}}
                                            @if (isset($log['in_file']))
                                                <br/>{{{$log['in_file']}}}
                                            @endif
                                            @if ($log['stack'])
                                                <div class="stack" id="stack{{{$key}}}"
                                                     style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        @if ($data_logs['standardFormat'])
                                            <th>@lang('logviewer::logviewers.table.heading.level')</th>
                                            <th>@lang('logviewer::logviewers.table.heading.context')</th>
                                            <th>@lang('logviewer::logviewers.table.heading.date')</th>
                                        @else
                                            <th>@lang('logviewer::logviewers.table.heading.line number')</th>
                                        @endif
                                        <th>@lang('logviewer::logviewers.table.heading.content')</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="p-3">
                                @if($data_logs['current_file'])
                                    <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_file']) }}{{ ($data_logs['current_folder']) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_folder']) : '' }}">
                                        <span class="fa fa-download"></span> @lang('logviewer::logviewers.button.download file')
                                    </a>
                                    |
                                    <a id="clean-log"
                                       href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_file']) }}{{ ($data_logs['current_folder']) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_folder']) : '' }}">
                                        <span class="fa fa-eraser"></span> @lang('logviewer::logviewers.button.clean file')
                                    </a>
                                    |
                                    <a id="delete-log"
                                       href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_file']) }}{{ ($data_logs['current_folder']) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_folder']) : '' }}">
                                        <span class="fa fa-trash"></span> @lang('logviewer::logviewers.button.delete file')
                                    </a>
                                    @if(count($data_logs['files']) > 1)
                                        |
                                        <a id="delete-all-log"
                                           href="?delall=true{{ ($data_logs['current_folder']) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($data_logs['current_folder']) : '' }}">
                                            <span class="fa fa-trash-alt"></span> @lang('logviewer::logviewers.button.delete all files')
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('logviewer::logviewers.title.create logviewer') }}</dd>
    </dl>
@stop

@push('js-stack')
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.data-table tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#delete-log, #clean-log, #delete-all-log').click(function () {
                return confirm('Are you sure?');
            });
        });
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[0, "desc"]],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                },
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@endpush
