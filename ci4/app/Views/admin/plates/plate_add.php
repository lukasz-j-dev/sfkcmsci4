<!-- Bootstrap Select Css -->
<style>
.err{
	color: red;
}
</style>
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

       

      <?php echo form_open(base_url('admin/plates/add'), 'class="form-horizontal"');  ?> 

        <div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Plate</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="plate" name="plate" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Plate Type</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_plate_type" name="dmv_plate_type" class="form-control datepicker">

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

                        <input type="text" id="dmv_contact_name" name="dmv_contact_name" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Contact Address</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_address" name="dmv_contact_address" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Contact City</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_city" name="dmv_contact_city" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Contact State</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_state" name="dmv_contact_state" class="form-control datepicker">

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

                        <input type="text" id="dmv_contact_zipcode" name="dmv_contact_zipcode" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Vehicle Vin</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_vin" name="dmv_vehicle_vin" class="form-control datepicker">

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

                        <input type="text" id="dmv_vehicle_body" name="dmv_vehicle_body" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Vehicle Year</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_year" name="dmv_vehicle_year" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Vehicle Make</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_make" name="dmv_vehicle_make" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Vehicle Color</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_vehicle_color" name="dmv_vehicle_color" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Last Checked Date</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_last_checked_date" name="dmv_last_checked_date" class="form-control datepicker">

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

                        <input type="text" id="dmv_status" name="dmv_status" class="form-control datepicker">

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

                        <input type="text" id="dmv_expiration_date" name="dmv_expiration_date" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Sex</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_sex" name="dmv_contact_sex" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
		<div class="row clearfix">

            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">

                <label for="username">Dmv Birth Date</label>

            </div>

            <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">

                <div class="form-group">

                    <div class="form-line">

                        <input type="text" id="dmv_contact_birth_date" name="dmv_contact_birth_date" class="form-control datepicker">

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

                        <input type="text" id="dmv_contact_county" name="dmv_contact_county" class="form-control datepicker">

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

                        <input type="text" id="dmv_mid_number" name="dmv_mid_number" class="form-control datepicker">

                    </div>

                </div>

            </div>

        </div>
		
	

        <div class="row clearfix">

            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">

                <input type="submit" name="submit" value="ADD" class="btn btn-primary m-t-15 waves-effect">

            </div>

        </div>

      <?php echo form_close();?>

    </div>

  </div>

</div>

</div>

</div>
<script>
$(document).ready(function(){
	$('#plate').on('blur', function(){
		var plate = this.value;
		var elm = $(this);
		$('.err').remove();
		$.ajax({
			url: '<?= base_url('admin/plates/is_exist'); ?>',
			type: 'post',
			dataType: 'json',
			data: {plate: plate},
			success: function(result){
				console.log(result);
				if(result.success){
					if(result.records) elm.parent().parent().append('<span class="err">The plate number already exists</span>');
				}
			},
			error: function(err){
				console.log('err');
				console.log(err);
			}
		});
	});
});
</script>