<div class="row">
    <div class="col-md-12">{!! validationHints() !!}</div>
</div>
<div class="row margin-top-20">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="item_group_id" class="control-label">Item Group {!! validation_error($errors->first('item_group_id'),'item_group_id') !!}</label>
            {!! Form::select('item_group_id', $itemGroups, null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="plant_id" class="control-label">Plant {!! validation_error($errors->first('plant_id'),'plant_id') !!}</label>
            {!! Form::select('plant_id', $plants, null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name" class="control-label">Item Name {!! validation_error($errors->first('name'),'name') !!}</label>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder' => 'Enter Item Name', 'id' => 'name']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="code" class="control-label">Item Code 
                @if(empty($item))
                {!! validation_error($errors->first('code'),'code') !!}
                @endif
            </label>
            @if(empty($item))
                {!! Form::text('code', old('code') ? old('code') : (!empty($item) ? $item->code : $code), ['class'=>'form-control', 'placeholder' => 'Enter Item Code', 'id' => 'code']) !!}
                <small id="codeHelpBlock" class="form-text text-muted">The code may only contain letters and numbers and at least 6 characters long.</small>
            @else
                {!! Form::text('code', $item->code, ['class'=>'form-control', 'placeholder' => 'Enter Item Code', 'id' => 'code', 'disabled' => 'disabled']) !!}
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pr_number" class="control-label">PR Number {!! validation_error($errors->first('pr_number'), 'pr_number') !!}</label>
            {!! Form::text('pr_number', old('pr_number') ? old('pr_number'): (!empty($item->pr_number) ? $item->pr_number : (!empty($pr_number) ? $pr_number : '')), ['class' => 'form-control', 'placeholder' => 'Enter Pr Number', 'id' => 'pr_number']) !!}
            <small id="prHelpBlock" class="form-text text-muted">The pr number may only contain letters and numbers and at least 6 characters long.</small>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="source_type" class="control-label">Source Type {!! validation_error($errors->first('source_type'), 'source_type') !!}</label>
            {!! Form::select('source_type', config('constants.item_source_types'), null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="stock_type" class="control-label">Stock Type {!! validation_error($errors->first('stock_type'), 'stock_type') !!}</label>
            {!! Form::select('stock_type', config('constants.item_stock_types'), null, ['class' => 'form-control chosen-select']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="opening_qty" class="control-label">Opening Qty</label>
            {!! Form::number('opening_qty', old('opening_qty') ? old('opening_qty') : (!empty($item->opening_qty) ? $item->opening_qty : 0), ['class' => 'form-control', 'placeholder' => 'Enter Opening Quantity']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="safety_stock_qty" class="control-label">Safety Stock Qty</label>
            {!! Form::number('safety_stock_qty', old('safety_stock_qty') ? old('safety_stock_qty') : (!empty($item->safety_stock_qty) ? $item->safety_stock_qty : 0), ['class' => 'form-control', 'placeholder' => 'Enter Safety Stock Quantity']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="avg_price" class="control-label">Average Price {!! validation_error($errors->first('avg_price'), 'avg_price') !!}</label>
            {!! Form::text('avg_price', null, ['class' => 'form-control', 'placeholder' => 'Enter Average Price']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="remarks" class="control-label">Remarks </label>
            {!! Form::textarea('remarks', null, ['class' => 'form-control', 'placeholder' => 'Enter Remark', 'size' => '30x2']) !!}
        </div>
    </div>
</div>

@section('custom-style')

@endsection

@section('custom-script')

@endsection


