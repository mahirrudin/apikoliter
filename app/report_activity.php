<?php

include_once ('class/function.report.php');
$contenturl = $_SERVER['SCRIPT_NAME'].'?activity';

if($appuseraccess == "System Administrator") {

include ('forbidden.php');

} elseif ($appuseraccess == "IT Manager") {

?>

                <div class="row">

                    <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Aktifitas System Administrator
                        </header>
                        <div class="panel-body">

                        <button type="button" name="btn-daily" class="btn btn-default" data-toggle="modal" data-target="#DailyModal"><i class="fa fa-calendar"></i> Daily Report</button>
                        <button type="button" name="btn-monthly" class="btn btn-default" data-toggle="modal" data-target="#MonthlyModal"><i class="fa fa-calendar"></i> Monthly Report</button>
                        <button type="button" name="btn-custom" class="btn btn-default" data-toggle="modal" data-target="#CustomModal"><i class="fa fa-calendar"></i> Custom Report</button>

                            <form name="report_monitoring" class="form-inline" role="form" method="post">
                                <div class="form-group">

                                        <!-- Modal Daily report-->
                                            <div class="modal fade" id="DailyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">Daily Report</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="form-group ">
                                                                    <label class="control-label col-md-4">Daily</label>

                                                                    <div class="col-md-8">
                                                                        <input name="date-daily" data-date-format="yyyy-mm-dd" class="form-control dpd1" size="16" type="text" value=""/>
                                                                        <span class="help-block">Select Daily</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="btn-report" class="btn btn-success"><i class="fa fa-calendar"></i> Show it </button>
                                                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Modal Daily report-->
                                        
                                        <!-- Modal Monthly report-->
                                            <div class="modal fade" id="MonthlyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">Monthly Report</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Monthly</label>

                                                                    <div class="col-md-8">
                                                                        <div data-date-minviewmode="months" data-date-viewmode="years" data-date-format="yyyy-mm" class="input-append date dpMonths">
                                                                            <input data-date-format="yyyy-mm" name="date-monthly" type="text" readonly="" value="" size="16" class="form-control">
                                                                            <span class="input-group-btn add-on">
                                                                                <button class="btn btn-primary" type="button"><i class="fa fa-calendar"></i></button>
                                                                            </span>
                                                                        </div>
                                                                        <span class="help-block">Select Monthly</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="btn-report" class="btn btn-success"><i class="fa fa-calendar"></i> Show it </button>
                                                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Modal Monthly report-->
                                        
                                        <!-- Modal custom report-->
                                            <div class="modal fade" id="CustomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title">Custom Report</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Date Range</label>

                                                                    <div class="col-md-8">
                                                                        <div class="input-group input-large">
                                                                            <input data-date-format="yyyy-mm-dd" type="text" class="form-control dpd1" name="date-from">
                                                                            <span class="input-group-addon">To</span>
                                                                            <input data-date-format="yyyy-mm-dd" type="text" class="form-control dpd2" name="date-to">
                                                                        </div>
                                                                        <span class="help-block">Select date range</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="submit" name="btn-report" class="btn btn-success"><i class="fa fa-calendar"></i> Show it </button>
                                                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Modal custom report-->
                                </div>
                            </form>
                        </div>
                    </section>
                    </div>

                </div>

                <?php if(isset($_POST['btn-report'])) { 

                    if(!empty($_POST['date-daily'])) {
                        $rtime = $_POST['date-daily']." 00:00:00";
                        $rtimeto = $_POST['date-daily']." 23:59:59";   

                    } elseif (!empty($_POST['date-monthly'])) {
                        $rtime = $_POST['date-monthly']."-01 00:00:00";
                        $rtimeto = $_POST['date-monthly']."-31 23:59:59";
                    
                    } elseif (!empty($_POST['date-from']) && !empty($_POST['date-to'])) {
                        $rtime = $_POST['date-from']." 00:00:00";
                        $rtimeto = $_POST['date-to']." 23:59:59";

                    } else {

                    }

                ?>
                <div class="row">

                    <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <table class="table responsive-data-table data-table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Hari</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Aktifitas</th>
                                    <th>Server</th>
                                    <th>Service</th>
                                    <th>Config</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $query = "SELECT
                                            activities.usrname,
                                            (SELECT dayname(activities.actdate)) AS actday,
                                            (SELECT date(activities.actdate)) AS acttgl,
                                            (SELECT time(activities.actdate)) AS actwkt,
                                            activities.actname,
                                            activities.svrname,
                                            activities.svcname,
                                            activities.cfgid,
                                            activities.cfgdir,
                                            activities.cfgname
                                        FROM
                                            activities
                                        WHERE
                                            activities.actdate BETWEEN '{$rtime}'
                                        AND '{$rtimeto}'
                                        ORDER BY
                                            activities.actdate DESC";

                                    $sql = $dbconnection->query($query);
                                    $no = 1;

                                    while($rowactivity=$sql->fetch_array()) {
                                        $datetime = date_create($rowactivity['acttgl']);
                                        $rowactivity['tgl'] = date_format($datetime,"d M Y");

                                ?>
                                    <tr>
                                        <td><?php echo $no++;?></td>
                                        <td><?php echo $rowactivity['usrname']; ?></td>
                                        <td><?php echo dayid($rowactivity['actday']); ?></td>
                                        <td><?php echo $rowactivity['tgl']; ?></td>
                                        <td><?php echo $rowactivity['actwkt']; ?></td>
                                        <td><?php echo $rowactivity['actname']; ?></td>
                                        <td><?php echo $rowactivity['svrname']; ?></td>
                                        <td><?php
                                            if ($rowactivity['svcname'] != null ){
                                            echo $rowactivity['svcname']; 
                                            } ?>        
                                        </td>
                                        <td><?php
                                            if ($rowactivity['cfgid'] != null ){
                                            echo $rowactivity['cfgdir'].$rowactivity['cfgname']; 
                                            } ?>        
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                </table>
                        </div>
                    </section>
                    </div>

                </div>
                <?php } ?>

<?php 
    } else {

        include ('forbidden.php');
        
    }
?> 