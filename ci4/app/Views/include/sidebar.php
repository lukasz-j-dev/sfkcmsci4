<?php //if (!isset($cur_tab)) $cur_tab = $this->uri->segment(2)==''?'dashboard': $this->uri->segment(2); ?>
<?php //if (!isset($sub_tab)) $sub_tab = $this->uri->segment(3)==''?'dashboard': $this->uri->segment(3); ?>
<?php $session = \Config\Services::session();?>
<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="<?= base_url()?>/images/user.png" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=strtoupper($session->get('name'));?></div>
            <div class="email"><?=$session->get('email');?></div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li id=""><a href="<?= base_url('admin/profile'); ?>"><i class="material-icons">person</i>Profile</a></li>
                    <li role="seperator" class="divider"></li>
                    <li id=""><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                    <li id=""><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                    <li id=""><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                    <li role="seperator" class="divider"></li>
                    <li id=""><a href="<?= base_url('auth/logout'); ?>"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>
            <li id="violations">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">assignment_late</i>
                    <span>Violations</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="<?= base_url('admin/violations/import'); ?>">Import</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/all'); ?>">All Violations</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/sent'); ?>">Sent</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/unpaid'); ?>">Unpaid</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/disputed'); ?>">Disputed</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/paid'); ?>">Paid</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/pastdue'); ?>">Past due</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/dismissed'); ?>">Dismissed</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/archived'); ?>">Archived</a>
                    </li>
                </ul>
            </li>
            <li id="pre-check">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">playlist_add_check</i>
                    <span>Pre Check</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="<?= base_url('admin/violations/duplicates_new/exact_date'); ?>">Duplicate Plates</a>
                    </li>
                    <li>
                        <a href="<?= base_url('admin/violations/duplicates_new/6hours'); ?>">Duplicate Plates 6h</a>
                    </li>
                    <li>
                        <a href="">Duplicate Media</a>
                    </li>
                    <li>
                        <a href="">Duplicate Issues</a>
                    </li>
                </ul>
            </li>
            <li id="video-review">
                <a href="#">
                    <i class="material-icons">videocam</i>
                    <span>Video Review</span>
                </a>
            </li>
            <li id="plate-review">
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">rate_review</i>
                    <span>Plate Review</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="#">Missing DMV status</a>
                    </li>
                    <li>
                        <a href="#">DMV contact not found</a>
                    </li>
                </ul>
            </li>
            <li id="vehicle-review">
                <a href="#">
                    <i class="material-icons">directions_car</i>
                    <span>Vehicle Review</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">warning</i>
                    <span>Warning Periods</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="#">First Time Violation (not mailed yet)</a>
                    </li>
                    <li>
                        <a href="#">Violation Before First Notice</a>
                    </li>
                    <li>
                        <a href="#">Within Warning Period</a>
                    </li>
                    <li>
                        <a href="#">Outside Warning Period</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">email</i>
                    <span>Ready To Mail</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="#">Multi Violator Check</a>
                    </li>
                    <li>
                        <a href="#">Duplicate Check</a>
                    </li>
                    <li>
                        <a href="#">Print Violations</a>
                    </li>
                    <li>
                        <a href="#">Print Warnings</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">local_car_wash</i>
                    <span>Plates</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="#">DMV</a>
                    </li>
                    <li>
                        <a href="#">Plate Snapshot</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">perm_data_setting</i>
                    <span>Vehicles (MMC)</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="#">Import MMC</a>
                    </li>
                    <li>
                        <a href="#">All Vehicles</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="<?= base_url('admin/users'); ?>">
                    <i class="material-icons">person</i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">settings</i>
                    <span>Settings</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="#">Municipalities</a>
                    </li>
                    <li>
                        <a href="#">Camera Scenes</a>
                    </li>
                    <li>
                        <a href="#">Users</a>
                    </li>
                    <li id="">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span>CMS Settings</span>
                        </a>
                        <ul class="ml-menu">
                            <li id="">
                                <a href="#">
                                    <span>Default Display number of rows in table (50, 100, 250, 500)</span>
                                </a>
                                <a href="#">
                                    <span>Mandrill Email settings</span>
                                </a>
                                <a href="#">
                                    <span>DMV Settings</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <div class="copyright">
            <a href="javascript:void(0);"><?php //$this->general_settings['copyright'] ?></a>.
        </div>
    </div>
    <!-- #Footer -->
</aside>
<!-- #END# Left Sidebar -->

<script>
    <?php $cur_tab=1;?>
    <?php $sub_tab=1;?>
    $("#<?= $cur_tab; ?>").addClass('active');
    $("#<?= $sub_tab; ?>").addClass('active');
</script>
