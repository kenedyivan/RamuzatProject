<!-- bootstrap modal -->
<div class="modal inmodal fade" id="add_collateral_docs_setup-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <form action="<?php echo site_url('index.php/Collateral_docs_setup/create')?>" id="formCollateral_docs_setup" class="formValidate form-horizontal" method="post" enctype="multipart/form-data">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title">Collateral Type Details</h3>
                <small class="font-bold">Please Make sure you enter all the required fields correctly</small>
            </div>
            <div class="modal-body">
                <input type="hidden"  name="id">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Collateral type name<span class="text-danger">*</span></label>
                      <div class="col-lg-6 form-group">
                        <input type="text" name="collateral_type_name" class="form-control m-b" required="required">
                      </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Description<span class="text-danger">*</span></label>
                      <div class="col-lg-6 form-group">
                        <textarea type="text" name="description" class="form-control m-b" required="required"></textarea>
                      </div>
                </div>

                        <div class="modal-footer">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary"><?php
                            if (isset($saveButton)) {
                                echo $saveButton;
                            }else{
                                echo "Save";
                            }
                         ?></button>
                        </div>
            </div>

        </form>
        </div>
    </div>
</div>
