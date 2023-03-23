<!-- Bootstrap Select Css -->

<link href="<?= base_url() ?>/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" href="/slim-image-cropper/example/css/slim.min.css">

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Edit Villages
                </h2>
                <a href="<?= base_url('admin/villages'); ?>" class="btn bg-deep-orange waves-effect pull-right">Villages List</a>
            </div>

            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <?php if (isset($msg) || validation_list_errors() !== '') : ?>
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                <?= validation_list_errors(); ?>
                                <?= isset($msg) ? $msg : ''; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php echo form_open_multipart(base_url('admin/villages/edit/' . $village['municipality_id']), 'class="form-horizontal"');  ?>
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label>Village ID</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <span style="vertical-align: middle; margin-top: 8px; display: inline-block;"><?= $village['municipality_id']; ?></label>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label>Added By</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <span style="vertical-align: middle; margin-top: 8px; display: inline-block;"><?= $village['added_by_details']; ?></span>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="village_court">Municipality Name</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="municipality_name" name="municipality_name" value="<?= $village['municipality_name']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="village_logo">Village logo</label>
                        </div> 
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <div class="slim">
                                        <input type="file" id="municipality_logo" name="municipality_logo" class="form-control">
                                    </div>
                                </div>
                                <?php if (!empty($village['municipality_logo'])) { ?>
                                    <div style="margin-top: 10px;">
                                        <img src="<?= base_url("/images/villages/" . $village['municipality_logo']); ?>" style="max-height: 120px;" />
                                    </div>
                                <?php } ?> 
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="village_court">municipality_email_general</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="municipality_email_general" id="municipality_email_general" name="municipality_email_general" value="<?= $village['municipality_email_general']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="fine_amount">Fine amount</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="violation_fine_amount" name="violation_fine_amount" value="<?= $village['violation_fine_amount']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="payable_to">Payable to</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="payable_to_name" name="payable_to_name" value="<?= $village['payable_to_name']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="notes">municipality_email_violation_pdf</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea type="text" id="municipality_email_violation_pdf" name="municipality_email_violation_pdf" class="form-control"> <?= $village['municipality_email_violation_pdf']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="violation_date"> Address1 1</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea type="text" id="municipality_address1" name="municipality_address1" class="form-control"><?= $village['municipality_address1']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="violation_date">Address 2</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <textarea type="text" id="municipality_address2" name="municipality_address2" class="form-control"><?= $village['municipality_address2']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="violation_date">City</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="municipality_city" name="municipality_city" value="<?= $village['municipality_city']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="state">State</label>
                        </div>

                        <?php $states = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"); ?>

                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" id="municipality_state" name="municipality_state">
                                        <option value="">-- Please select --</option>
                                        <?php foreach ($states as $state) { ?>
                                            <option <?= ($village['municipality_state'] == $state) ? ' selected="selected"' : ''; ?> value="<?= $state; ?>"><?= $state; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="violation_date">Zip</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="municipality_zipcode" name="municipality_zipcode" value="<?= $village['municipality_zipcode']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="status">Status</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" id="municipality_status" name="municipality_status">
                                        <option value="">-- Please select --</option>
                                        <option value="1" <?= ($village['municipality_status'] == '1') ? ' selected="selected"' : ''; ?>>Active</option>
                                        <option value="2" <?= ($village['municipality_status'] == '2') ? ' selected="selected"' : ''; ?>>Inactive</option>
                                        <option value="3" <?= ($village['municipality_status'] == '3') ? ' selected="selected"' : ''; ?>>Archived</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="stripe_pub_key">Stripe Public Key</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" id="stripe_pub_key" name="stripe_pub_key" value="<?= $village['stripe_pub_key']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="stripe_sec_key">Stripe Secret Key</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="password" id="stripe_sec_key" name="stripe_sec_key" value="<?= $village['stripe_sec_key']; ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="date_added">Date added</label>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" id="created_date" name="created_date" value="<?= $village['created_date']; ?>" class="form-control datepicker">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                            <input type="submit" name="submit" value="UPDATE" class="btn btn-primary m-t-15 waves-effect">
                        </div>
                    </div>

                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'M-dd-y'
        });
        $('.timepicker').timepicker({});
    });
</script>
<script src="/slim-image-cropper/example/js/slim.kickstart.min.js"></script>