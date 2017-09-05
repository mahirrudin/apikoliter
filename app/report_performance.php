<?php

include_once ('class/function.report.php');
$contenturl = $_SERVER['SCRIPT_NAME'].'?performance';

if($appuseraccess == "System Administrator" || $appuseraccess == "IT Manager") {

?>
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Report Performa Server
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
                                                                        <span class="help-block"> </span>
                                                                    </div>

                                                                    <label class="control-label col-md-4">Select Server</label>
                                                                    <div class="col-md-6">
                                                                        <select name="daily-svrid" required class="form-control">
                                                                            <option>Pilih server...</option>
                                                                        <?php
                                                                            $sql = $dbconnection->query("SELECT servers.servers_id AS txtidsvr, servers.servers_name AS txtnmsvr FROM servers WHERE servers.servers_delete = 0"); 
                                                                            while($rowsvrdata=$sql->fetch_array())
                                                                                {   ?>
                                                                            <option value="<?php echo $rowsvrdata['txtidsvr']; ?>"><?php echo $rowsvrdata['txtnmsvr']; ?></option>
                                                                        <?php   }   ?>
                                                                        </select>
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
                                                                            <span class="input-group-btn add-on"></span>
                                                                        </div>
                                                                        <span class="help-block"> </span>
                                                                    </div>

                                                                    <label class="control-label col-md-4">Select Server</label>
                                                                    <div class="col-md-6">
                                                                        <select name="monthly-svrid" required class="form-control">
                                                                            <option>Pilih server...</option>
                                                                        <?php
                                                                            $sql = $dbconnection->query("SELECT servers.servers_id AS txtidsvr, servers.servers_name AS txtnmsvr FROM servers WHERE servers.servers_delete = 0"); 
                                                                            while($rowsvrdata=$sql->fetch_array())
                                                                                {   ?>
                                                                            <option value="<?php echo $rowsvrdata['txtidsvr']; ?>"><?php echo $rowsvrdata['txtnmsvr']; ?></option>
                                                                        <?php   }   ?>
                                                                        </select>
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

                                                                    <div class="col-md-6">
                                                                        <div class="input-group input-large">
                                                                            <input data-date-format="yyyy-mm-dd" type="text" class="form-control dpd1" name="date-from">
                                                                            <span class="input-group-addon">To</span>
                                                                            <input data-date-format="yyyy-mm-dd" type="text" class="form-control dpd2" name="date-to">
                                                                        </div>
                                                                        <span class="help-block"> </span>
                                                                    </div>

                                                                    <label class="control-label col-md-4">Select Server</label>
                                                                    <div class="col-md-6">
                                                                        <select name="custom-svrid" required class="form-control">
                                                                            <option>Pilih server...</option>
                                                                        <?php
                                                                            $sql = $dbconnection->query("SELECT servers.servers_id AS txtidsvr, servers.servers_name AS txtnmsvr FROM servers WHERE servers.servers_delete = 0"); 
                                                                            while($rowsvrdata=$sql->fetch_array())
                                                                                {   ?>
                                                                            <option value="<?php echo $rowsvrdata['txtidsvr']; ?>"><?php echo $rowsvrdata['txtnmsvr']; ?></option>
                                                                        <?php   }   ?>
                                                                        </select>
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

                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading head-border">
                                Realtime Statistic Server
                                <span class="tools pull-right">
                                    <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close fa fa-times" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body" <?php if(isset($_POST['btn-report'])){ echo 'style="display: none"'; } ?> >
                            <!-- tabel status monitoring -->
                                <div class="col-lg-12">
                                <section class="panel">
                                <table class="table responsive-data-table data-table">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Server</th>
                                        <th>Hostname</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>PSU</th>
                                        <th>CPU</th>
                                        <th>RAM</th>
                                        <th>HDD</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $sql = $dbconnection->query("SELECT
                                        monitoring.svrname,
                                        monitoring.svrip,
                                        monitoring.statsdate,
                                        monitoring.statsdown,
                                        monitoring.cpuusage,
                                        monitoring.ramtotal,
                                        monitoring.ramusage,
                                        monitoring.hddtotal,
                                        monitoring.hddusage
                                    FROM
                                        monitoring
                                    GROUP BY
                                    monitoring.statsdate
                                    ORDER BY
                                    monitoring.statsdate DESC");

                                    $no = 1;

                                    while($stats=$sql->fetch_array()) {
                                        $datetime = date_create($stats['statsdate']);
                                        $stats['tgl'] = date_format($datetime,"d M Y");
                                        $stats['wkt'] = date_format($datetime,"H:i:s");
                                    if($stats['hddtotal'] > 0 && $stats['hddtotal'] > 0 ){
                                        $stats['ram'] = $stats['ramusage'] / $stats['ramtotal'] * 100;
                                        $stats['hdd'] = $stats['hddusage'] / $stats['hddtotal'] * 100;
                                    }else{
                                        $stats['ram'] = 0;
                                        $stats['hdd'] = 0;
                                    }

                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $stats['svrname']; ?></td>
                                        <td><?php echo $stats['svrip']; ?></td>
                                        <td><?php echo $stats['tgl']; ?></td>
                                        <td><?php echo $stats['wkt']; ?></td>
                                        <td><?php
                                                if($stats['statsdown'] == 0 ){ 
                                                    echo '<button class="btn btn-xs btn-success"><i class="fa fa-check"></i></button>';
                                                }else{
                                                    echo '<button class="btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i></button>';
                                                }
                                            ?>    
                                        </td>
                                        <td><?php echo $stats['cpuusage']; ?> %</td>
                                        <td><?php echo number_format($stats['ram'],2); ?> %</td>
                                        <td><?php echo number_format($stats['hdd'],2); ?> %</td>
                                    </tr>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>

            <?php if(isset($_POST['btn-report'])) { 

                    if(!empty($_POST['daily-svrid']) && !empty($_POST['date-daily'])) {
                        $rtime = $_POST['date-daily']." 00:00:00";
                        $rtimeto = $_POST['date-daily']." 23:59:59";
                        $svrid = $_POST['daily-svrid'];         

                    } elseif (!empty($_POST['monthly-svrid']) && !empty($_POST['date-monthly'])) {
                        $rtime = $_POST['date-monthly']."-01 00:00:00";
                        $rtimeto = $_POST['date-monthly']."-31 23:59:59";
                        $svrid = $_POST['monthly-svrid'];
                    
                    } elseif (!empty($_POST['custom-svrid']) && !empty($_POST['date-from']) && !empty($_POST['date-to'])) {
                        $rtime = $_POST['date-from']." 00:00:00";
                        $rtimeto = $_POST['date-to']." 23:59:59";
                        $svrid = $_POST['custom-svrid'];

                    } else {

                        $alertmsg = '"Pastikan semua data terisi!"';
                        include ('alert/failed.php');

                    }

                $sql = $dbconnection->prepare("SELECT
                        monitoring.svrname,
                        monitoring.svrip,
                        monitoring.svrkernel,
                        monitoring.svrprocie,
                        monitoring.svrdistro,
                        monitoring.svrdistrover,
                        monitoring.statsdate,
                        monitoring.ramtotal,
                        monitoring.hddtotal,
                        Avg(monitoring.cpuusage) AS cpuusg,
                        Avg(monitoring.ramusage) / Avg(monitoring.ramtotal) * 100 AS ramusg,
                        Avg(monitoring.hddusage) / Avg(monitoring.hddtotal) * 100 AS hddusg
                    FROM
                        monitoring
                    WHERE
                        monitoring.svrid = ?
                    AND monitoring.ramtotal <> '0'
                    AND monitoring.hddtotal <> '0'
                    AND monitoring.statsdate BETWEEN ?
                    AND ?");
                $sql->bind_param('iss', $svrid, $rtime, $rtimeto);
                $sql->execute();
                $sql->store_result();
                $sql->bind_result($svrname, $svrip, $svrkernel, $svrprocie, $distroname, $distrover, $sdate, $ram, $hdd, $cpuusg, $ramusg, $hddusg);
                $sql->fetch();

                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel invoice">
                        <header class="panel-heading head-border">
                                <span class="tools pull-right">
                                    <a class="fa fa-repeat box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close fa fa-times" href="javascript:;"></a>
                                </span>
                            </header>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="form-heading">Laporan Performa Server</h2>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <center><?php 
                                        $datetime = date_create($rtime);
                                        $datetimeto = date_create($rtimeto);
                                        echo "Dari ".date_format($datetime, 'd-M-Y')." Ke ".date_format($datetimeto, 'd-M-Y') 
                                        ?></center>
                                    </div>
                                </div>

                                <br/>
                                <br/>
                                <br/>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <strong>Dibuat Oleh </strong>
                                        <table class="table table-hover">
                                            <tbody>
                                            <tr>
                                                <td>Nama</td>
                                                <td><?php echo strtoupper($appusername); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td><?php echo $appuseraccess; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-xs-4">
                                    </div>
                                    <div class="col-xs-4">
                                        <strong>Informasi Server</strong>
                                        <table class="table table-hover">
                                            <tbody>
                                            <tr>
                                                <td>Nama Server</td>
                                                <td><?php echo strtoupper($svrname); ?></td>
                                            </tr>
                                            <tr>
                                                <td>IP Server</td>
                                                <td><?php echo $svrip; ?></td>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <br/>

                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Processor</th>
                                        <th class="hidden-xs">Linux Distro</th>
                                        <th>Linux Kernel</th>
                                        <th>Memory</th>
                                        <th>Hardisk</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><?php echo $svrprocie; ?></td>
                                        <td class="hidden-xs"><?php echo $distroname." ".$distrover; ?></td>
                                        <td><?php echo $svrkernel; ?></td>
                                        <td><?php
                                            $rams = $ram/1024;
                                            echo number_format($rams,0)." MB"; ?>
                                        </td>
                                        <td><?php 
                                            $hdds = $hdd/1024;
                                            echo number_format($hdds,0)." MB"; ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br/>
                                <br/>

                                <div class="row">
                                    <div class="col-xs-8">
                                        <h4>SARAN</h4>
                                        <ul class="list-unstyled">
                                            <?php advices($cpuusg, $ramusg, $hddusg); ?>
                                        </ul>
                                    </div>
                                    <div class="col-xs-4">
                                        <table class="table table-hover">
                                            <tbody>
                                            <tr>
                                                <td>Penggunaan CPU</td>
                                                <td><?php echo number_format($cpuusg,2)." %"; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Penggunaan RAM</td>
                                                <td><?php echo number_format($ramusg,2)." %"; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Penggunaan Hardisk</td>
                                                <td><?php echo number_format($hddusg,2)." %"; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <br/>
                                <br/>
                                <br/>
                                <!---
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <a class="btn btn-default" onclick="javascript:window.print();"><i class="fa fa-print"></i> Print Laporan</a>
                                        </div>
                                    </div>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>

<?php

} else {

    include ('forbidden.php');
    
}

?> 