<?php
$n = isset($designation) && !empty($designation) ? false : true;
?>
<div class="card">
    <div class="form-body">
        <div class="card-body">
            <form class="ajax-Form" enctype="multipart/form-data" method="post" action="/settings/adddesignation">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designation_name" name="designation_name"
                                   aria-describedby="emailHelp"
                                   placeholder="" value="<?= (!$n) ? $designation->designation_name : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Active</label>
                        <div class="custom-control custom-checkbox mr-sm-2 mb-3">
                            <input type="checkbox" name="status" class="custom-control-input" id="checkbox0" value="1" <?= (!$n && isset($designation) && $designation->status == 1) ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="checkbox0"></label>
                        </div>
                        </div>
                    </div>
                </div>

                <!--
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="box-title">Sizes</h4>
                        <p class="text-muted font-13"> You can add <code> data-size="small",data-size="large"</code> to your input element for different sizes. </p>
                        <div class="m-b-30">
                            <input type="checkbox" checked class="js-switch" data-color="#f62d51" data-size="small" />
                            <input type="checkbox" checked class="js-switch" data-color="#26c6da" />
                            <input type="checkbox" checked class="js-switch" data-color="#ffbc34" data-size="large" /> </div>
                    </div>
                </div>
                -->
                <div class="form-actions pull-right">
                    <input type="hidden" name="id" value="<?= (!$n) ? $designation->id : '0'; ?>">
                    <button type="submit" class="btn waves-effect waves-light btn-success">
                        <i class="fa fa-check"></i> Save</button>
                    <button type="reset" class="btn waves-effect waves-light btn-secondary">Cancel</button>

                </div>

            </form>
        </div>
    </div>
</div>