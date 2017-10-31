<select name="settle_if" id="settle_if" >
    <option value="0">选择代付接口</option>
    @foreach($ifs as $if)
    <option value="{{$if->id}}">{{$if->name}}</option>
    @endforeach
</select>


