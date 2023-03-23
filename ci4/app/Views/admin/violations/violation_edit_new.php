<style>
    .err {
        color: red;
    }

    .succ {
        color: green;
    }

    .form-group.dmv {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form.dmv {
        margin-left: 10px;
    }

    .tooltip-plate {
        cursor: pointer;
    }

    /* Shang */
    @import url('https://fonts.googleapis.com/css?family=Open+Sans:400,600');
    /* VARS */
    /* MIXINS */
    /* STYLE THE HTML ELEMENTS (INCLUDES RESETS FOR THE DEFAULT FIELDSET AND LEGEND STYLES) */
    fieldset {
        margin: 0;
        /* padding: 2rem; */
        /* box-sizing: border-box; */
        display: block;
        /* border: solid 1px #ccc; */
        min-width: 0;
        background-color: #fff;
    }
    fieldset legend {
        margin: 0 0 1.5rem;
        padding: 0;
        width: 100%;
        float: left;
        display: table;
        font-size: 1.5rem;
        line-height: 140%;
        font-weight: 600;
        color: #333;
    }
    fieldset legend + * {
        clear: both;
    }
    /* body:not(:-moz-handler-blocked) fieldset {
        display: table-cell;
    } */
    /* TOGGLE STYLING */
    fieldset .toggle {
        margin: 0 0 1.5rem;
        box-sizing: border-box;
        font-size: 0;
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-start;
        align-items: stretch;
        gap: 15px;
    }
    fieldset .toggle input {
        width: 0;
        height: 0;
        position: absolute;
        left: -9999px;
    }
    fieldset .toggle input[type="radio"]:checked + label:after, fieldset .toggle input[type="radio"] + label:before {
        display: none;
    }
    fieldset .toggle input + label {
        margin: 10px 0 !important;
        padding: 1.5rem 3.2rem !important;
        padding-top: 1.6rem !important;
        padding-left: 0px;
        box-sizing: border-box;
        position: relative;
        /* display: inline-block; */
        border: solid 1px #ddd !important;
        border-radius: 6px !important;
        background-color: #fff;
        font-size: 1.3rem !important;
        line-height: 40px !important;
        height: 40px !important;
        font-weight: 600 !important;
        text-align: center;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 0 0 rgba(255, 255, 255, 0);
        transition: border-color 0.15s ease-out, color 0.25s ease-out, background-color 0.15s ease-out, box-shadow 0.15s ease-out;
    }
    fieldset .toggle input:checked + label {
        background-color: #64B6F6;
        color: #fff;
        box-shadow: 0 0 10px rgba(102, 179, 251, .5);
        border-color: #64B6F6 !important;
        z-index: 1;
        outline: none !important;
    }
    fieldset .toggle input:focus + label {
        outline: dotted 1px #ccc;
        outline-offset: 0.45rem;
    }
    /* fieldset .toggle label[for="sc_sloweddown"] {
        border-left: 0px;
    } */
    @media (max-width: 800px) {
        fieldset .toggle input + label {
            padding: 0.75rem 0.25rem;
            flex: 0 0 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }

    .classification {
        display: flex;
    }
    .threshold {
        margin-left: 40px;
        margin-top: 10px;
    }
    .threshold p {
        margin: 0px !important;
    }
    .threshold p:last-of-type {
        font-size: 15px;
    }
    .field-label {
        font-size: 10px;
    }
    video, .card-image {
        border-radius: 10px;
        overflow: hidden;
    }
    .card-image {
        padding: 0px;
    }
    .card-image .m-4 {
        border: solid 1px #ddd !important;
        padding: 13px;
        border-radius: 0 0 10px 10px;
        border-top: 0px !important;
    }
    .card-image .m-4 p:last-of-type {
        margin-bottom: 0px;
    }
    .card-image .text-lg {
        margin-bottom: 0px !important;
        font-size: 18px;
    }
    .text-md {
        font-size: 15px;
    }
    .dmv_contact.title p {
        margin-bottom: 3px;
    }
    .dmv_contact {
        text-align: left;
    }
    .dmv_contact.title {
        margin-top: 15px;
        margin-left: 13px;
        padding-bottom: 25px;
        border-bottom: 1px solid #ddd;
    }
    .uppercase {
        text-transform: uppercase !important;
    }
    .font-semibold {
        font-weight: 500;
    }
    .block__info {
        padding-bottom: 5px;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .block__info p:first-of-type {
        margin-bottom: 3px;
    }
    .block__info input {
        border: 0px !important;
        box-shadow: none !important;
        height: 25px !important;
        padding: 0px !important;
        width: 100%;
    }
    .block__info .bootstrap-select.form-control {
        border-bottom: 0px !important;
        height: 25px !important;
    }
    .block__info .bootstrap-select.form-control button {
        padding: 0px !important;
        margin-top: 0px !important;
        outline: none !important;
        padding-top: 5px !important;
    }
    .block__info .bootstrap-select.form-control button span {
        margin-left: 0px !important;
        outline: none !important;
        font-size: 15px !important;
        font-weight: 500 !important;
    }
    .column-3 {
        display: flex;
        gap: 20px;
    }
    .column-1 {
        /* margin-top: 50px; */
    }
    .column-1 .title {
        display: flex;
    }
    .column-1 .title .blank {
        width: 70px;
    }
    .column-1 .title .dmv {
        width: 80px;
    }
    .column-1 .title .mmc {
        width: 180px;
    }
    .column-1 .body, .column-1 .make, .column-1 .color, .column-1 .state {
        width: 350px;
        text-align: left;
    }
    .column-1 .dmv-mmc {
        display: flex;
        border: 1px solid #ddd;
        border-radius: 10px;
        height: 120px;
        width: 350px;
        padding: 15px;
    }
    .column-1 .dmv-mmc .list-title, .column-1 .dmv-mmc .list-dmv, .column-1 .dmv-mmc .list-mmc {
        text-align: left !important;
        display: grid;
        align-items: center;
        font-size: 12px;
    }
    .column-1 .dmv-mmc .list-title {
        width: 54px;
    }
    .column-1 .dmv-mmc .list-dmv {
        width: 80px;
    }
    .column-1 .dmv-mmc .list-mmc {
        width: 170px;
    }
    .column-1 li {
        list-style-type: none;
        text-align: left;
    }
    .card .header {
        display: flex;
        justify-content: space-between;
    }
    /* Shang */
</style>

<link href="<?php echo base_url() ?>/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<link rel="stylesheet" href="/slim-image-cropper/example/css/slim.min.css">
<link href="<?php echo base_url() ?>/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

<div class="similar-tooltip" style="width: 800px; display: none; display: flex;"></div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card" id="cards">

            <?php echo form_open_multipart(base_url('admin/violations/edit/' . $violation['violation_id']), 'id="editt" class="form-horizontal"') ?>
            
            <div class="header">
                <h2>Edit Violations New</h2>
                
                <div class="button-group">
                    <button type="submit" id="save" name="submit" value="SAVE" class="btn btn-primary m-t-15 waves-effect uppercase">Save</button>
                    <button type="submit" id="update" name="submit" value="UPDATE" class="btn btn-primary m-t-15 waves-effect uppercase">Update</button>
                </div>
            </div>

            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <?php if (isset($msg) || validation_list_errors() !== '') : ?>
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                <?php echo validation_list_errors(); ?>
                                <?php echo isset($msg) ? $msg : ''; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Video side -->
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <?php if (!empty($violation['violation_video_url'])) { ?>
                            <video style="width: 100%; height: 50vh;" controls>
                                <source src="<?php echo $violation['violation_video_url']; ?>" type="video/mp4">
                                Your browser does not support the video.
                            </video>
                        <?php } ?>
                        <p class="mt-2"><span class="font-bold">Stop classification</span> - Select if vehicle failed to stop, did a full stop, extremely slowed down(rolling stop) or drove in wrong direction.</p>

                        <div class="classification">
                            <fieldset>
                                <div class="toggle">
                                    <input type="radio" name="stop_classification" id="sc_fail" value="fail" <?php echo ( $violation['violation_stop_classification'] == 'fail' ) ? 'checked="checked"':''?> />
                                    <label for="sc_fail" class="uppercase">Fail</label>

                                    <input type="radio" name="stop_classification" id="sc_fullstop" value="fullstop" <?php echo ( $violation['violation_stop_classification'] == 'fullstop' ) ? 'checked="checked"':''?> />
                                    <label for="sc_fullstop" class="uppercase">Full Stop</label>

                                    <input type="radio" name="stop_classification" id="sc_sloweddown" value="sloweddown" <?php echo ( $violation['violation_stop_classification'] == 'sloweddown' ) ? 'checked="checked"':''?> />
                                    <label for="sc_sloweddown" class="uppercase">Slowed Down</label>

                                    <input type="radio" name="stop_classification" id="sc_wrongdirection" value="wrongdirection" <?php echo ( $violation['violation_stop_classification'] == 'wrongdirection' ) ? 'checked="checked"':''?> />
                                    <label for="sc_wrongdirection" class="uppercase">Wrong Direction</label>
                                </div>
                            </fieldset>
                            <div class="threshold">
                                <p class="uppercase font-semibold field-label">Stop Threshold</p>
                                <p class="font-bold"><?php echo $violation['threshold_time']; ?> of <?php echo $violation['threshold_limit']; ?> seconds</p>
                            </div>
                        </div>

                        <div class="row clearfix edit-form-new">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="violation_photo_url" class="uppercase font-semibold field-label">Plate Photo</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="violation_photo_url" name="violation_photo_url" placeHolder="URL to image" value="<?php echo $violation['violation_photo_url']; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix edit-form-new">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="violation_video_url" class="uppercase font-semibold field-label">Violation Video</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="violation_video_url" name="violation_video_url" placeHolder="URL to video" value="<?php echo $violation['violation_video_url']; ?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Video side -->

                    <!-- Information side -->
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="column-3">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 card-image">
                                <div class="">
                                    <div class="image">
                                        <?php if (!empty($violation['violation_photo_url'])) { ?>
                                            <img src="<?php echo $violation['violation_photo_url']; ?>" style="width: 100%;" />
                                        <?php } else { ?>
                                            <img src="https://alameda.edu/wp-content/uploads/2021/06/placeholder-4-300x200.png" style="width: 100%;" />
                                        <?php } ?>
                                    </div>
                                    <div class="m-4">
                                        <p class="text-lg font-bold uppercase"><?php echo $violation['plate_number'];?></p>
                                        <?php if ( !empty($plate_details['dmv_plate_type']) ) { ?>
                                        <p class="uppercase font-semibold"><?php echo $plate_details['dmv_plate_type']; ?></p>
                                        <?php } ?>
                                        <input type="hidden" name="plate_number" value="<?php echo $violation['plate_number'];?>"/>
                                    </div>
                                </div>

                                <div class="dmv_contact title">
                                    <p class="text-md font-bold">DVM Plate Contact</p>
                                    <?php if (!empty($plate_details['dmv_contact_name'])) { ?>
                                        <div class="dmv_contact"><?php echo $plate_details['dmv_contact_name']; ?></div>
                                    <?php } ?>
                                    <?php if (!empty($plate_details['dmv_contact_address'])) { ?>
                                        <div class="dmv_contact"><?php echo $plate_details['dmv_contact_address']; ?></div>
                                    <?php } ?>
                                    <div>
                                        <?php if (!empty($plate_details['dmv_contact_city'])) { ?>
                                            <span class="dmv_contact"><?php echo trim($plate_details['dmv_contact_city']) . ', '; ?></span>
                                        <?php } ?>
                                        <?php if (!empty($plate_details['dmv_contact_state'])) { ?>
                                            <span class="dmv_contact"><?php echo trim($plate_details['dmv_contact_state']) . ', '; ?></span>
                                        <?php } ?>
                                        <?php if (!empty($plate_details['dmv_contact_zipcode'])) { ?>
                                            <span class="dmv_contact"><?php echo trim($plate_details['dmv_contact_zipcode']); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Violation ID</p>
                                    <p class="font-semibold"><?php echo $violation['violation_id']; ?></p>
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Violation Date</p>
                                    <?php $ndnd = $violation['violation_date'];
                                    if ($ndnd == NULL || $ndnd == '0000-00-00 00:00:00')
                                        $ndnd = '';
                                    else $ndnd = date('M d Y', strtotime($violation['violation_date']));
                                    ?>
                                    <input type="text" id="violation_date" name="violation_date" value="<?php echo $ndnd; ?>" class="form-control datepicker font-semibold">
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Notice Date</p>
                                    <?php
                                    $ndn = $violation['violation_notice_date'];
                                    if ($ndn == NULL || $ndn == '0000-00-00 00:00:00')
                                        $ndn = '';
                                    else $ndn = date('M d Y', strtotime($violation['violation_notice_date']));
                                    ?>
                                    <input type="text" id="violation_notice_date" name="violation_notice_date" value="<?php echo $ndn; ?>" class="form-control datepicker font-semibold">
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Violation Status</p>
                                    <select class="form-control show-tick" id="violation_status" name="violation_status">
                                        <option value="">-- Please select --</option>
                                        <option value="1" <?php echo ($violation['violation_status'] == '1') ? ' selected="selected"' : ''; ?>>New</option>
                                        <option value="2" <?php echo ($violation['violation_status'] == '2') ? ' selected="selected"' : ''; ?>>Reviewed</option>
                                        <option value="3" <?php echo ($violation['violation_status'] == '3') ? ' selected="selected"' : ''; ?>>Mailed</option>
                                        <option value="4" <?php echo ($violation['violation_status'] == '4') ? ' selected="selected"' : ''; ?>>Archived</option>
                                        <option value="5" <?php echo ($violation['violation_status'] == '5') ? ' selected="selected"' : ''; ?>>Dismissed</option>
                                        <option value="6" <?php echo ($violation['violation_status'] == '6') ? ' selected="selected"' : ''; ?>>Disputed</option>
                                    </select>
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Violation Type</p>
                                    <select class="form-control show-tick" id="violation_type" name="violation_type">
                                        <option value="">-- Please select --</option>
                                        <option value="1" <?php echo ($violation['violation_type'] == '1') ? ' selected="selected"' : ''; ?>>Violation</option>
                                        <option value="2" <?php echo ($violation['violation_type'] == '2') ? ' selected="selected"' : ''; ?>>Warning</option>
                                    </select>
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Payment Status</p>
                                    <select class="form-control show-tick" id="payment_status" name="payment_status">
                                        <option value="">-- Please select --</option>
                                        <option value="0" <?php echo ($violation['payment_status'] == '0') ? ' selected="selected"' : ''; ?>>Unpaid</option>
                                        <option value="1" <?php echo ($violation['payment_status'] == '1') ? ' selected="selected"' : ''; ?>>Paid</option>
                                    </select>
                                </div>
                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Payment Method</p>
                                    <select class="form-control show-tick" id="payment_method" name="payment_method">
                                        <option value="">-- Please select --</option>
                                        <option value="Credit card" <?php echo ($violation['payment_method'] == 'Credit card') ? ' selected="selected"' : ''; ?>>Credit card</option>
                                        <option value="Check/Money Order" <?php echo ($violation['payment_method'] == 'Check/Money Order') ? ' selected="selected"' : ''; ?>>Check/Money Order</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Violation Number</p>
                                    <p class="font-semibold"><?php echo $violation['violation_number'] . '-' . $violation['violation_pin']; ?></p>
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Date Added</p>
                                    <?php $ndnddd = $violation['violation_added_date'];
                                    if ($ndnddd == NULL || $ndnddd == '0000-00-00 00:00:00')
                                        $ndnddd = '';
                                    else $ndnddd = date('M d Y', strtotime($violation['violation_added_date']));
                                    ?>
                                    <input type="text" id="violation_added_date" name="violation_added_date" value="<?php echo $ndnddd; ?>" class="form-control datepicker font-semibold">
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Due Date</p>
                                    <?php $ndndd = $violation['payment_due_date'];
                                    if ($ndndd == NULL || $ndndd == '0000-00-00 00:00:00')
                                        $ndndd = '';
                                    else $ndndd = date('M d Y', strtotime($violation['payment_due_date']));
                                    ?>
                                    <input type="text" id="payment_due_date" name="payment_due_date" value="<?php echo $ndndd; ?>" class="form-control datepicker font-semibold">
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Village Court</p>
                                    <select class="form-control show-tick" id="municipality_id" name="municipality_id">
                                        <option value="">-- Please select --</option>
                                        <?php foreach ($violation['simple_villages'] as $simple_villages) { ?>
                                            <option value="<?php echo $simple_villages['municipality_id']; ?>" <?php echo ($violation['municipality_id'] == $simple_villages['municipality_id']) ? ' selected="selected"' : ''; ?>><?php echo $simple_villages['municipality_name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Camera Location</p>
                                    <select class="form-control show-tick" id="camera_id" name="camera_id">
                                        <option value="">-- Please select --</option>
                                        <?php foreach ($violation['simple_cameras'] as $simple_cameras) { ?>
                                            <option value="<?php echo $simple_cameras['scene_id']; ?>" <?php echo ($violation['camera_id'] == $simple_cameras['scene_id']) ? ' selected' : ''; ?>><?php echo $simple_cameras['scene_location']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Date Paid</p>
                                    <?php
                                    $dpn = $violation['payment_date'];
                                    if ($dpn == NULL || $dpn == '0000-00-00 00:00:00')
                                        $dpn = '';
                                    else $dpn = date('M d Y', strtotime($violation['payment_date']));
                                    ?>
                                    <input type="text" id="payment_date" name="payment_date" value="<?php echo $dpn; ?>" class="form-control datepicker font-semibold">
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Amount Due</p>
                                    <input type="text" id="payment_fine_amount" name="payment_fine_amount" value="<?php echo $violation['payment_fine_amount']; ?>" class="form-control font-semibold">
                                </div>

                                <div class="block__info">
                                    <p class="uppercase font-semibold field-label">Amount Paid</p>
                                    <input type="text" id="payment_amount" name="payment_amount" value="<?php echo $violation['payment_amount']; ?>" class="form-control font-semibold">
                                </div>
                            </div>
                        </div>

                        <div class="column-1">
                            <div class="title">
                                <div class="blank"></div>
                                <div class="dmv"><span class="font-bold uppercase">DMV</span></div>
                                <div class="mmc"><span class="font-bold uppercase">MMC&nbsp;</span> Score: <?php if (!empty($mmc_details['mmc_plate_score'])) echo $mmc_details['mmc_plate_score'];?>%</div>
                            </div>
                            <div class="dmv-mmc">
                                <div class="list-title">
                                    <li class="uppercase font-bold">Body</li>
                                    <li class="uppercase font-bold">Make</li>
                                    <li class="uppercase font-bold">Color</li>
                                    <li class="uppercase font-bold">State</li>
                                </div>

                                <div class="list-dmv">
                                    <?php if (!empty($plate_details['dmv_vehicle_body'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $plate_details['dmv_vehicle_body'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>

                                    <?php if (!empty($plate_details['dmv_vehicle_make'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $plate_details['dmv_vehicle_make'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>

                                    <?php if (!empty($plate_details['dmv_vehicle_color'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $plate_details['dmv_vehicle_color'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>

                                    <?php if (!empty($plate_details['dmv_contact_state'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $plate_details['dmv_contact_state'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>
                                </div>

                                <div class="list-mmc">
                                <?php if (!empty($mmc_details['mmc_vehicle_type_body'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $mmc_details['mmc_vehicle_type_body'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>

                                    <?php if (!empty($mmc_details['mmc_vehicle_make'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $mmc_details['mmc_vehicle_make'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>

                                    <?php if (!empty($mmc_details['mmc_vehicle_color'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $mmc_details['mmc_vehicle_color'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>

                                    <?php if (!empty($mmc_details['mmc_plate_region'])) {?> 
                                        <li class="uppercase font-semibold"><?php echo $mmc_details['mmc_plate_region'];?></li>
                                    <?php } else {?> <li class="uppercase font-semibold">&nbsp;</li> <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End information side -->
                </div>
            </div> <!-- Body -->

            <?php echo form_close(); ?>

        </div>
    </div>

    <!-- Modal -->
    <div id="confirm-archive" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Archive</h4>
                </div>
                <div class="modal-body">
                    <p>As you sure you want to archive selected violation?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a class="btn btn-primary btn-ok">Archive</a>
                </div>
            </div>
        </div>
    </div>

	<div id="confirm-delete" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete</h4>
				</div>
				<div class="modal-body">
					<p>As you sure you want to delete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<a class="btn btn-danger btn-ok">Delete</a>
				</div>
			</div>
		</div>
	</div>

    <script src="<?php echo base_url() ?>/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?php echo base_url() ?>/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

    <script>
        $('#confirm-archive').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

		$('#confirm-delete').on('show.bs.modal', function(e) {
			$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
		});

        $(document).ready(function() {
            $('.datepicker').datepicker({
                dateFormat: 'M dd yy'
            });

            $('.timepicker').timepicker({});

            $('#village_court').on('change', function() {
                var village_court = this.value;
                $.ajax({
                    url: '/cms/admin/villages/get_village_due/' + village_court + '/',
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);
                        if (typeof result.violation.fine_amount != 'undefined') $('#payment_fine_amount').val(result.violation.fine_amount);
                    },
                    error: function(err) {
                        console.log('err');
                        console.log(err);
                    },
                });
            });

            $('#cards #status').on('change', function() {
                if ($(this).val() == 5) {
                    $("#valreson").css("display", "block");
                } else {
                    $("#valreson").css("display", "none");
                }
            });

            $('#cards #save').on('click', function() {
                var len = parseInt($('#dismissres').val().replace(/\s+/g, '').length);
                var statsue = $('#status option:selected').val();
                if (statsue == 5) {
                    if (len > 60) {
                        $("#resvalidation").css("display", "block");
                        return;
                    } else {
                        $("#resvalidation").css("display", "none");
                        var deditt = $('#editt').attr('action');
                        var urll = deditt + '?save=1';
                        $('#editt').attr('action', urll).submit();
                    }
                    // document.getElementById("editt").submit();
                } else {
                    $("#resvalidation").css("display", "none");
                    var deditt = $('#editt').attr('action');
                    var urll = deditt + '?save=1';
                    $('#editt').attr('action', urll).submit();
                }
            });

            $('#cards #update').on('click', function() {
                var len = parseInt($('#dismissres').val().replace(/\s+/g, '').length);
                var statsue = $('#status option:selected').val();
                if (statsue == 5) {
                    if (len > 60) {
                        $("#resvalidation").css("display", "block");
                        return;
                    } else {
                        $("#resvalidation").css("display", "none");
                        document.getElementById("editt").submit();
                    }
                } else {
                    $("#resvalidation").css("display", "none");
                    document.getElementById("editt").submit();
                }
            });

            $('#cards #dismissres').on('focusout', function() {
                var len = parseInt($('#dismissres').val().replace(/\s+/g, '').length);
                if (len > 60) {
                    $("#resvalidation").css("display", "block");
                    return;
                }
            });

            $('#plate_number').on('blur', function() {
                var plate = this.value;
                $.ajax({
                    url: '/cms/admin/plates/plate_search/' + plate + '/',
                    dataType: 'json',
                    success: function(result) {
                        console.log(result);
                        if (typeof result.plate_details.id != 'undefined') {
                            $('#state').val(result.plate_details.state);
                            $('#type').val(result.plate_details.plate_type);
                            $('#full_name').val(result.plate_details.name);
                            $('#address1').val(result.plate_details.address);
                            $('#city').val(result.plate_details.city);
                            $('#zip').val(result.plate_details.zip_code);
                        }
                    },
                    error: function(err) {
                        console.log('err');
                        console.log(err);
                    },
                });
            });

            $('#add_plate').on('click', function(e) {
                e.preventDefault();
                var plate = $('#plate_number').val();
                var elm = $(this);
                elm.prop('disabled', true);
                $('.err').remove();
                $.ajax({
                    url: '<?php echo base_url('admin/plates/add_ajax'); ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        plate: plate
                    },
                    success: function(result) {
                        elm.prop('disabled', false);
                        console.log(result);
                        if (result.success) {
                            if (result.exist) elm.after('<span class="err">The plate number already exists</span>');
                            else if (result.resp) elm.after('<span class="succ">Plate successfully added</span>');
                            elm.remove();
                        }
                    },
                    error: function(err) {
                        elm.prop('disabled', false);
                        console.log('err');
                        console.log(err);
                    }
                });
            });

            $('.btn-dmv-api').on('click', function(e) {
                e.preventDefault();

                if ($('.btn-dmv-api').text() == 'Checking...') {
                    return;
                }
                $('.btn-dmv-api').html('Checking...');

                // var url = $('#dmv-url').val();
                var url = "<?php echo base_url('admin/plates/dmv_api/'); ?>";
                var id = "<?php echo $violation['violation_id']; ?>";
                url = url +'/'+ $('#plate_number').val() + '/' + id;

                $.ajax({
                    url: url,
                    type: 'get',
                    dataType: 'json',
                    success: function(result) {
                        $('.btn-dmv-api').html('DMV API lookup');
                        console.log(result);
                    },
                    error: function(err) {
                        $('.btn-dmv-api').html('DMV API lookup');
                        console.log(err);
                    },
                    complete: function(result) {
                        // window.location.reload();
                        window.location.href = "<?php echo base_url('admin/violations/edit/'); ?>/44444" + id;
                    }
                });
            });

            var currentMousePos = {
                x: -1,
                y: -1
            };

            $(document).mousemove(function(event) {
                if ($(".similar-tooltip")) {
                    var el = $(".similar-tooltip");
                    el.css({
                        'position': 'absolute',
                        'z-index': '1000'
                    });
                    el.css("left", event.pageX + 50);
                    el.css("top", event.pageY - 200);
                }
            });

            $(".tooltip-plate").on("mouseenter", function() {
                    var id = $(this).attr('data-id');
                    var url = "<?php echo base_url('admin/violations/tooltip/') ?>";
                    url = url + id;
                    $.ajax({
                        url: url,
                        type: 'get',
                        async: false,
                        success: function(response) {
                            $(".similar-tooltip").empty();
                            $(".similar-tooltip").append(response);
                            $(".similar-tooltip").show();
                        }
                    });
                })
                .on("mouseleave", function() {
                    if ($(".similar-tooltip"))
                        $(".similar-tooltip").hide();
                });
        });
    </script>

    <script src="/slim-image-cropper/example/js/slim.kickstart.min.js"></script>
