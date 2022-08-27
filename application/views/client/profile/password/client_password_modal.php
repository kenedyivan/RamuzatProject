<!-- bootstrap modal -->
<div class="modal inmodal fade" id="client-password-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <?php
            if(isset($user)){
                ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3 class="modal-title">Password</h3>
                </div>
                <?php
            }else {
                $client_id = $this->session->userdata("id");
                ?>
                <div class="modal-header">
                    <h3 class="modal-title">Please set your Password</h3>
                </div>
                <?php
            }
            ?>
        
            <div class="modal-body">
            <?php echo form_open_multipart("password/create", array('id' => 'clientSetPassword', 'class' => 'formValidate', 'name' => 'clientSetPassword', 'method' => 'post', 'role' => 'form')); ?>
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="user_type" id="member">
            <input type="hidden" name="has_set_password" value="true" id="member">
            <?php
            if(isset($user)){
                ?>
                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                <?php
            }else {
                $client_id = $this->session->userdata("id");
                ?>
                <input type="hidden" name="user_id" value="<?php echo $client_id; ?>">
                <?php
            }
            ?>
            
            <div class="form-group row">
            <label class="col-lg-4 col-form-label">New Password<span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="password" name="password"  placeholder="*********" id="password" class="form-control m-b" required="required">
                
            </div>
            </div>
            <div class="form-group row">
            <label class="col-lg-4 col-form-label">Confirm password<span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="password" name="confirmpassword" placeholder="*********" id="confirmpassword" class="form-control m-b" required="required">
                
            </div>					
            </div>
            <div class="form-group row">
            <div class="col-lg-12">
            <div class="pull-right">
            <?php
            if(isset($user)){
                ?>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <?php
            }
            ?>
            <button id="btn-submit" type="submit" class="btn btn-success btn-sm save_data">
            <i class="fa fa-check"></i> Set Password</button>
          
                </div>
            </div>					
            </div>					
          </form>
          </div>
        </div>
    </div>
</div>
<!-- bootstrap modal ends -->
