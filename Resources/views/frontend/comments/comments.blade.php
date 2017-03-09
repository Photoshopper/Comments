<h6>{{ trans('comments::comments.title.comments') }} ({{ Comment::count($model) }})</h6>

@if (Session::has('success'))
    <div class="alert alert-success fade in alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('success') }}
    </div>
@endif

@if($currentUser)
    {!! Form::open(['route' => ['comments.comment.store'], 'method' => 'post', 'class' => 'comment-form']) !!}

    <div class='form-group comment-group{{ $errors->has('comment') ? ' has-error' : '' }}'>
        {!! Form::label('comment', trans('comments::comments.title.comment')) !!}
        {!! Form::textarea('comment', old('comment'), ['class' => 'form-control comment', 'rows' => 3, 'maxlength' => 500]) !!}
        {!! $errors->first('comment', '<span class="help-block">:message</span>') !!}
        <div class="textarea-counter" id="textarea-counter"></div>
    </div>

    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
        <div id="g-recaptcha" class="g-recaptcha"></div>
        {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
    </div>

    {!! Form::hidden('commentable_id', $model->id, ['class' => 'commentable_id']) !!}
    {!! Form::hidden('commentable_type', get_class($model), ['class' => 'commentable_type']) !!}
    {!! Form::submit(trans('comments::comments.form.submit'), ['class' => 'btn btn-success btn-sm comment-submit']) !!}

    {!! Form::close() !!}
@else
    <p>{!! trans('comments::comments.form.authorization', ['startlink' => '<a href="' . route('login') . '">', 'endlink' => '</a>']) !!}</p>
@endif

<div class="comments-wrapper">
    @if($comments_list)
        {!! $comments_list !!}
    @else
        <p class="no-comments">{{ trans('comments::comments.form.no comments') }}.</p>
    @endif
</div>

@section('styles')
    @parent
    <link href="{{ asset('modules/comments/css/comments.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('scripts')
    @parent
    <script>
        var route = '{{ route('comments.comment.store') }}';
        var reply_text = '{{ trans('comments::comments.button.reply') }}';
        var submit_text = '{{ trans('comments::comments.form.submit') }}'
    </script>
    <script src="{{ asset('modules/comments/js/jquery.comments.js') }}"></script>
    <script type="text/javascript">
        var widgetId1;
        var onloadCallback = function() {
            widgetId1 = grecaptcha.render('g-recaptcha', {
                'sitekey' : '{{ env('RECAPTCHA_PUBLIC_KEY') }}'
            });
        };
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

@endsection