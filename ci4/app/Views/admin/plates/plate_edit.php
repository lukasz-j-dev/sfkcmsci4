<!-- Bootstrap Select Css -->

<link href="<?= base_url() ?>/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

<div class="row clearfix">

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

<div class="card">

  <div class="header">

    <h2>

      ADD Plates

    </h2>

    <a href="<?= base_url('admin/plates/'); ?>" class="btn bg-deep-orange waves-effect pull-right">Plates List</a>

  </div>

  <div class="body">

    <div class="row clearfix">

      <div class="col-md-12">

        <?php if(isset($msg) || validation_list_errors() !== ''): ?>

          <div class="alert alert-warning alert-dismissible">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

              <h4><i class="icon fa fa-warning"></i> Alert!</h4>

              <?= validation_list_errors();?>

              <?= isset($msg)? $msg: ''; ?>

          </div>

        <?php endif; ?>

      </div>

       

      <?php echo form_open(base_url('admin/plates/edit/' . $plate_details['plate_id']), 'class="form-horizontal"');  ?> 

        <div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Plate Number:</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

               <label style="margin-top: 8px;"><?= $plate_details['plate_number']; ?></label>
                <?php /* <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="plate" name="plate" class="form-control">

                    </div>

                </div> */ ?>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Plate Type</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_plate_type" name="dmv_plate_type" value="<?= $plate_details['dmv_plate_type']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Contact Name</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_name" name="dmv_contact_name" value="<?= $plate_details['dmv_contact_name']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Plate Address</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_address" name="dmv_contact_address" value="<?= $plate_details['dmv_contact_address']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">dmv_contact_city</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_city" name="dmv_contact_city" value="<?= $plate_details['dmv_contact_city']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">dmv_contact_state</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_state" name="dmv_contact_state" value="<?= $plate_details['dmv_contact_state']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Contact Zipcode</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_zipcode" name="dmv_contact_zipcode" value="<?= $plate_details['dmv_contact_zipcode']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Plate Vin</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="vin" name="dmv_vehicle_vin" value="<?= $plate_details['dmv_vehicle_vin']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Vehicle Body</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_body" name="dmv_vehicle_body" value="<?= $plate_details['dmv_vehicle_body']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">dmv_vehicle_year</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_year" name="dmv_vehicle_year" value="<?= $plate_details['dmv_vehicle_year']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">dmv_vehicle_make</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_make" name="dmv_vehicle_make" value="<?= $plate_details['dmv_vehicle_make']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Color</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_color" name="dmv_vehicle_color" value="<?= $plate_details['dmv_vehicle_color']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Date Checked</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_last_checked_date" value="<?= $plate_details['dmv_last_checked_date']; ?>" name="dmv_last_checked_date" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Status</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_status" name="dmv_status" value="<?= $plate_details['dmv_status']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Expiration Date</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="exp_date" name="dmv_expiration_date" value="<?= $plate_details['dmv_expiration_date']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">dmv_contact_sex</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_sex" name="dmv_contact_sex" value="<?= $plate_details['dmv_contact_sex']; ?>" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">dmv_contact_birth_date</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_birth_date" value="<?= $plate_details['dmv_contact_birth_date']; ?>" name="dmv_contact_birth_date" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Country</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_county" value="<?= $plate_details['dmv_contact_county']; ?>" name="country" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Mid Number</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_mid_number" value="<?= $plate_details['dmv_mid_number']; ?>" name="dmv_mid_number" class="form-control">

                    </div>

                </div>

            </div>

        </div>
        
        	<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Last Fetched Date </label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_last_checked_date" value="<?= $plate_details['dmv_last_checked_date']; ?>" name="dmv_last_checked_date" class="form-control">

                    </div>

                </div>

            </div>

        </div>
		
	

        <div class="row clearfix">

            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">

                <input type="submit" name="submit" value="Edit" class="btn btn-primary m-t-15 waves-effect">

            </div>

        </div>

      <?php echo form_close();?>

    </div>

  </div>

</div>

</div>

</div>