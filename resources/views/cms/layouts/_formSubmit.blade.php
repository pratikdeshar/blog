<div class="form-actions right">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ isset($back) ? $back : url()->previous() }}" type="button" class="btn default"> <i
                    class="fa fa-backward"></i> Back</a>
            <button type="submit" class="btn green"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>
</div>
