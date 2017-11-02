<div class="btn-group" data-toggle="buttons">
    @foreach($options as $option => $label)
        <label class="btn btn-default btn-sm {{ \Request::get('classify', 99) == $option ? 'active' : '' }}">
            <input type="radio" class="classify" value="{{ $option }}">{{$label}}
        </label>
    @endforeach
</div>
