<div class="box-body">
    <div class='form-group{{ $errors->has('comment') ? ' has-error' : '' }}'>
        {!! Form::label('comment', trans('comments::comments.table.comment')) !!}
        {!! Form::textarea('comment', old('comment'), ['class' => 'form-control']) !!}
        {!! $errors->first('comment', '<span class="help-block">:message</span>') !!}
    </div>

    <div class="form-group">
        {{ trans('comments::comments.form.lang') }}:
        @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
            @if($comment->locale == $locale)
                {!!  $language['native'] !!}
            @endif
        @endforeach
    </div>
</div>