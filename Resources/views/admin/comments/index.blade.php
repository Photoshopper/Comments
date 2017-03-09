@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('comments::comments.title.comments') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('comments::comments.title.comments') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row hidden">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.comments.comment.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('comments::comments.button.create comment') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <ul class="nav nav-pills" style="margin-bottom:15px;">
                        <li{{ request()->is('*/backend/comments/comments') || request()->is('backend/comments/comments') ? ' class=active' : '' }}>
                            <a href="{{ route('admin.comments.comment.index') }}">{{ trans('comments::comments.button.all') }} <span class="badge">{{ $countAll }}</span></a>
                        </li>
                        <li{{ request()->is('*/backend/comments/comments/unapproved') || request()->is('backend/comments/comments/unapproved') ? ' class=active' : '' }}>
                            <a href="{{ route('admin.comments.comment.unapproved') }}">{{ trans('comments::comments.button.pending') }} <span class="badge">{{ $countUnapproved }}</span></a>
                        </li>
                    </ul>

                    {!! Form::open(['route' => ['admin.comments.comment.bulkAction'], 'method' => 'post', 'class' => 'form-inline']) !!}

                    <div class="form-group{{ $errors->has('bulk_action') || $errors->has('comments') ? ' has-error' : '' }}" style="margin-bottom: 15px;">
                        {!! Form::select('bulk_action', [
                            null => trans('comments::comments.form.bulk actions'),
                            'approve' => trans('comments::comments.form.approve comments'),
                            'unapprove' => trans('comments::comments.form.unapprove comments'),
                            'delete' => trans('comments::comments.form.delete comments')
                        ], null, ['class' => 'form-control']) !!}

                        {!! Form::submit(trans('comments::comments.form.apply'), ['class' => 'btn btn-primary']) !!}
                        {!! $errors->first('bulk_action', '<span class="help-block">:message</span>') !!}
                        {!! $errors->first('comments', '<span class="help-block">:message</span>') !!}
                    </div>

                    <table class="data-table table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th data-sortable="false"></th>
                            <th data-sortable="false">{{ trans('comments::comments.table.user') }}</th>
                            <th>{{ trans('comments::comments.table.comment') }}</th>
                            <th>{{ trans('comments::comments.table.status') }}</th>
                            <th>{{ trans('comments::comments.table.created_at') }}</th>
                            <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td>
                                {!! Form::checkbox('comments[]', $comment->id, false, ['class' => 'flat-blue']) !!}
                            </td>
                            <td width="200">
                                <p><strong>{{ Comment::getUsername($comment->user_id) }}</strong> (ID: {{ $comment->user_id }})</p>
                                <a href="http://whatismyipaddress.com/ip/{{ $comment->ip }}" target="_blank">{{ $comment->ip }}</a>
                            </td>
                            <td>
                                {{ $comment->comment }}
                            </td>
                            <td>
                                <span class="label {{ $comment->status ? 'bg-green' : 'bg-gray' }}">
                                    {{ $comment->status ? trans('comments::comments.table.approved') : trans('comments::comments.table.pending') }}
                                </span>
                            </td>
                            <td width="150">
                                <p>{{ $comment->created_at->format('d.m.Y H:i') }}</p>
                            </td>
                            <td width="130">
                                <div class="btn-group">
                                    <a href="{{ url($comment->url) }}" target="_blank" class="btn btn-default btn-flat"><i class="fa fa-external-link"></i></a>
                                    <a href="{{ route('admin.comments.comment.edit', [$comment->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
                                    <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.comments.comment.destroy', [$comment->id]) }}"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>{{ trans('comments::comments.table.user') }}</th>
                            <th>{{ trans('comments::comments.table.comment') }}</th>
                            <th>{{ trans('comments::comments.table.status') }}</th>
                            <th>{{ trans('comments::comments.table.created_at') }}</th>
                            <th>{{ trans('core::core.table.actions') }}</th>
                        </tr>
                        </tfoot>
                    </table>
                    {!! Form::close() !!}
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('comments::comments.title.create comment') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.comments.comment.create') ?>" }
                ]
            });
        });
    </script>
    <script>
        $( document ).ready(function() {
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
        });
    </script>
    <?php $locale = locale(); ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>
    <script type="text/javascript">
        $(function () {
            $.fn.dataTable.moment('DD.MM.YYYY HH:mm');
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 4, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
@stop
