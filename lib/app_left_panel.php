<?

$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];
?>
<style>
  .xn-profile a:hover{
    background: #fff !important;
  }
</style>
<ul class="x-navigation">
    <li class="xn-logo">
        <a href="home.php" style="padding: 0px !important;"><img src="images/logo.png" style="padding: 5px 2px;" alt="<?=$site_title?>"/></a>
        <a href="#" class="x-navigation-control"></a>
    </li>
    <li class="xn-profile" style="background:#fff">
        <a href="home.php" class="profile-mini">
            <img src="images/logo-original.png" alt="<?=$site_title?> - Logo"/>
        </a>
        <? /* <div class="profile">
            <div class="profile-image">
                <img src="assets/images/users/avatar.jpg" alt="<?=$site_title?> - Logo"/>
            </div>
            <div class="profile-data">
                <div class="profile-data-name">John Doe</div>
                <div class="profile-data-title">Web Developer/Designer</div>
            </div>
            <div class="profile-controls">
                <a href="pages-profile.php" class="profile-control-left"><span class="fa fa-info"></span></a>
                <a href="pages-messages.php" class="profile-control-right"><span class="fa fa-envelope"></span></a>
            </div>
        </div> */ ?>

        <div class="profile">
            <div class="profile-data">
                <div class="profile-data-name"><b><?=strtoupper($_SESSION['tcs_empname'])?></b></div>
                <div class="profile-data-title"><?=strtoupper($_SESSION['tcs_empsubcore'])?></div>
            </div>
        </div>
    </li>
    <? /* <li class="xn-title">Navigation</li> */ ?>
    <li class="<?php echo ($currentFile=='home.php')?'active':'';?>">
        <a href="home.php"><span class="fa fa-dashboard"></span> <span class="xn-text">Dashboard</span></a>
    </li>
    <li class="xn-openable <?php echo ($currentFile=='request_entry.php' or $currentFile=='request_list.php' or $currentFile=='budget_list.php' or $currentFile=='budget_entry.php' or $currentFile=='waiting_approval.php' or $currentFile=='waiting_query.php' or $currentFile=='other_request_list.php' or $currentFile=='md_pending_approvals.php' or $currentFile=='search-result.php')?'active':'';?>">
        <a href="javascript:void(0)"><span class="fa fa-fw fa-list-alt"></span> <span class="xn-text">Approval Request</span></a>
        <ul>
            <li class="<?php echo ($currentFile=='request_entry.php')?'active':'';?>"><a href="request_entry.php"><span class="fa fa-edit"></span>New Request Entry</a></li>
            <li class="<?php echo ($currentFile=='request_list.php')?'active':'';?>"><a href="request_list.php"><span class="fa fa-table"></span>Request List</a></li>
            <? /* <li class="<?php echo ($currentFile=='budget_list.php')?'active':'';?>"><a href="budget_list.php"><span class="fa fa-money"></span>Budget List</a></li>
            <li class="<?php echo ($currentFile=='other_request_list.php')?'active':'';?>"><a href="#"><span class="fa fa-users"></span>Others Request List</a></li>
            <li class="<?php echo ($currentFile=='md_pending_approvals.php')?'active':'';?>"><a href="#"><span class="fa fa-users"></span>MD Pending Approvals</a></li> */ ?>
            <li class="<?php echo ($currentFile=='waiting_approval.php')?'active':'';?>"><a href="waiting_approval.php"><span class="fa fa-check-square"></span>Waiting for Approval</a></li>
            <li class="<?php echo ($currentFile=='waiting_query.php')?'active':'';?>"><a href="waiting_query.php"><span class="fa fa-question-circle"></span>Waiting With Query</a></li>
            <li class="<?php echo ($currentFile=='search-result.php')?'active':'';?>"><a href="search-result.php"><span class="fa fa-search"></span>Search Result</a></li>
        </ul>
    </li>
    <li class="xn-openable <?php echo ($currentFile=='approved_approvals.php' or $currentFile=='approved_approvals_list.php' or $currentFile=='acknowledge_approvals.php')?'active':'';?>">
        <a href="javascript:void(0)"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Reports</span></a>
        <ul>
            <li class="<?php echo ($_REQUEST['status']=='Approved')?'active':'';?>"><a href="http://www.tcsportal.com/approval-desk/approved_approvals_list.php#/Approved%20Approvals"><span class="fa fa-check-circle-o"></span>Approved Approvals</a></li>
            <li class="<?php echo ($_REQUEST['status']=='Pending')?'active':'';?>"><a href="http://www.tcsportal.com/approval-desk/approved_approvals_list.php#/Pending%20Approvals"><span class="fa fa-exclamation-triangle"></span>Pending Approvals</a></li>
            <li class="<?php echo ($_REQUEST['status']=='Rejected')?'active':'';?>"><a href="http://www.tcsportal.com/approval-desk/approved_approvals_list.php#/Rejected%20Approvals"><span class="fa fa-times-circle"></span>Rejected Approvals</a></li>
            <li class="<?php echo ($_REQUEST['status']=='Internal Verification')?'active':'';?>"><a href="http://www.tcsportal.com/approval-desk/approved_approvals_list.php#/Internal%20Verification%20Approvals"><span class="fa fa-check-circle-o"></span>Internal Verification Approvals</a></li>
            <li class="<?php echo ($currentFile=='acknowledge_approvals.php')?'active':'';?>"><a href="acknowledge_approvals.php"><span class="fa fa-thumbs-o-up"></span>Acknowledge Alternate Approvals</a></li>
        </ul>
    </li>

    <li class="xn-openable <?php echo ($currentFile=='process_requirement_entry.php' or $currentFile=='process_requirement_list.php' or $currentFile=='process_requirement_view.php' or $currentFile=='requirement-search-result.php')?'active':'';?>">
        <a href="javascript:void(0)"><span class="fa fa-bullhorn"></span> <span class="xn-text">Requirement</span></a>
        <ul>
            <li class="<?php echo ($currentFile=='process_requirement_entry.php')?'active':'';?>"><a href="process_requirement_entry.php"><span class="fa fa-edit"></span>Requirement Entry</a></li>
            <li class="<?php echo ($currentFile=='process_requirement_list.php')?'active':'';?>"><a href="process_requirement_list.php"><span class="fa fa-check-square-o"></span>Requirement List</a></li>
        </ul>
		<li class="<?php echo ($currentFile=='duty.php')?'active':'';?>">
            <a href="duty.php"><span class="fa fa-user"></span> <span class="xn-text">Contractors</span></a>
        </li>
    </li>

    <li class="xn-openable <?php echo ($currentFile=='request_entry_fixed.php' or $currentFile=='approval_budget_entry.php' or $currentFile=='budget_entry_grid_1.php' or $currentFile=='request_entry_renewal.php' or $currentFile=='request_list_quote.php')?'active':'';?>">
        <a href="javascript:void(0)"><span class="fa fa-bullhorn"></span> <span class="xn-text">Demo Process</span></a>
        <ul>
            <li class="<?php echo ($currentFile=='budget_entry_grid_1.php')?'active':'';?>"><a href="budget_entry_grid_1.php"><span class="fa fa-edit"></span>Budget Entry</a></li>
            <li class="<?php echo ($currentFile=='budget_approval2.php')?'active':'';?>"><a href="budget_approval2.php"><span class="fa fa-edit"></span>Budget Approval</a></li>
            <li class="<?php echo ($currentFile=='request_entry_renewal.php')?'active':'';?>"><a href="request_entry_renewal.php"><span class="fa fa-edit"></span>Product Rate Fix</a></li>
            <li class="<?php echo ($currentFile=='request_list_quote.php')?'active':'';?>"><a href="request_list_quote.php"><span class="fa fa-edit"></span>Product Rate Fix List</a></li>
            <li class="<?php echo ($currentFile=='request_entry_fixed.php')?'active':'';?>"><a href="request_entry_fixed.php"><span class="fa fa-edit"></span>Fixed Budget</a></li>
        </ul>
    </li>
	
    <? /* <li class="xn-title">Components</li>
    <li class="xn-openable">
        <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">UI Kits</span></a>
        <ul>
            <li><a href="ui-widgets.php"><span class="fa fa-heart"></span> Widgets</a></li>
            <li><a href="ui-elements.php"><span class="fa fa-cogs"></span> Elements</a></li>
            <li><a href="ui-buttons.php"><span class="fa fa-square-o"></span> Buttons</a></li>
            <li><a href="ui-panels.php"><span class="fa fa-pencil-square-o"></span> Panels</a></li>
            <li><a href="ui-icons.php"><span class="fa fa-magic"></span> Icons</a><div class="informer informer-warning">+679</div></li>
            <li><a href="ui-typography.php"><span class="fa fa-pencil"></span> Typography</a></li>
            <li><a href="ui-portlet.php"><span class="fa fa-th"></span> Portlet</a></li>
            <li><a href="ui-sliders.php"><span class="fa fa-arrows-h"></span> Sliders</a></li>
            <li><a href="ui-alerts-popups.php"><span class="fa fa-warning"></span> Alerts & Popups</a></li>
            <li><a href="ui-lists.php"><span class="fa fa-list-ul"></span> Lists</a></li>
            <li><a href="ui-tour.php"><span class="fa fa-random"></span> Tour</a></li>
        </ul>
    </li>
    <li class="xn-openable">
        <a href="#"><span class="fa fa-pencil"></span> <span class="xn-text">Forms</span></a>
        <ul>
            <li>
                <a href="form-layouts-two-column.php"><span class="fa fa-tasks"></span> Form Layouts</a>
                <div class="informer informer-danger">New</div>
                <ul>
                    <li><a href="form-layouts-one-column.php"><span class="fa fa-align-justify"></span> One Column</a></li>
                    <li><a href="form-layouts-two-column.php"><span class="fa fa-th-large"></span> Two Column</a></li>
                    <li><a href="form-layouts-tabbed.php"><span class="fa fa-table"></span> Tabbed</a></li>
                    <li><a href="form-layouts-separated.php"><span class="fa fa-th-list"></span> Separated Rows</a></li>
                </ul>
            </li>
            <li><a href="form-elements.php"><span class="fa fa-file-text-o"></span> Elements</a></li>
            <li><a href="form-validation.php"><span class="fa fa-list-alt"></span> Validation</a></li>
            <li><a href="form-wizards.php"><span class="fa fa-arrow-right"></span> Wizards</a></li>
            <li><a href="form-editors.php"><span class="fa fa-text-width"></span> WYSIWYG Editors</a></li>
            <li><a href="form-file-handling.php"><span class="fa fa-floppy-o"></span> File Handling</a></li>
        </ul>
    </li>
    <li class="xn-openable">
        <a href="tables.php"><span class="fa fa-table"></span> <span class="xn-text">Tables</span></a>
        <ul>
            <li><a href="table-basic.php"><span class="fa fa-align-justify"></span> Basic</a></li>
            <li><a href="table-datatables.php"><span class="fa fa-sort-alpha-desc"></span> Data Tables</a></li>
            <li><a href="table-export.php"><span class="fa fa-download"></span> Export Tables</a></li>
        </ul>
    </li>
    <li class="xn-openable">
        <a href="#"><span class="fa fa-bar-chart-o"></span> <span class="xn-text">Charts</span></a>
        <ul>
            <li><a href="charts-morris.php"><span class="xn-text">Morris</span></a></li>
            <li><a href="charts-nvd3.php"><span class="xn-text">NVD3</span></a></li>
            <li><a href="charts-rickshaw.php"><span class="xn-text">Rickshaw</span></a></li>
            <li><a href="charts-other.php"><span class="xn-text">Other</span></a></li>
        </ul>
    </li>
    <li>
        <a href="maps.php"><span class="fa fa-map-marker"></span> <span class="xn-text">Maps</span></a>
    </li>
    <li class="xn-openable">
        <a href="#"><span class="fa fa-sitemap"></span> <span class="xn-text">Navigation Levels</span></a>
        <ul>
            <li class="xn-openable">
                <a href="#">Second Level</a>
                <ul>
                    <li class="xn-openable">
                        <a href="#">Third Level</a>
                        <ul>
                            <li class="xn-openable">
                                <a href="#">Fourth Level</a>
                                <ul>
                                    <li><a href="#">Fifth Level</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li> */ ?>
</ul>
