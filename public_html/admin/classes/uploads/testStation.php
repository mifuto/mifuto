<?php
include_once("config.php");
$accessPermission = "";

include_once("tpl/dashboard-top.tpl.php");
$mId="clusterM";
?>

<script src="js/kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<style>
    #attachedFileUpload{
        height: 0px;
        z-index: 1111;
        width: 100%;
        overflow: hidden;
    }
    #attachedFileUploadlabel{
        font-size: 14px !important;
        font-weight: 500 !important;
    }

    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
        background: #f8f8f8;
        color: rgba(98, 98, 98, 0.62);
    }

   

   
</style>


<div class="content clearfix">
    <div class="panel panel-transparent">
        <div class="panel-body p-b-0">

            <div class="panel panel-default panel-savings m-b-0">
                <div class="panel-body padding-0">
                    <!-- Starts Supporting system popup Modal -->
                    <div class="modal-header p-b-10 p-t-5 text-left" style="background: #cccccc;border-top-left-radius: 3px;border-top-right-radius: 3px;">
                        <h4 class="modal-title semi-bold" style="font-size:19px;">
                            Auto Discovery
                        </h4>
                    </div>

                    

                    <div class="modal-body p-t-15" >
                        <form class="form-group" id="divFrom">
                            <div class="row m-b-0">
                                <div class="col-xs-12 col-sm-12 col-md-12 support-content-item">
                            
                                    <div class="row m-t-0">
                                        
                                            <div class="col-md-2">
                                                <div class="form-group required " id="divSIPADD">
                                                    <label>Start IP Address</label>
                                                    <input type="hidden" value="" id="Ids" name="Ids" />
                                                    <input type="text" class="form-control" autofocus id="SIPADD" required="" placeholder= "Enter start IP address" onkeyup="removeErrorAlert('divSIPADD');"  name="SIPADD">
                                                    <span class="text-danger sp_error hide"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group required" id="divSTIPADD">
                                                    <label>Stop IP Address </font></label>
                                                    <input type="text" class="form-control" vlaue="" id="STIPADD" name="STIPADD"  onkeyup="removeErrorAlert('divSTIPADD');" placeholder="Enter start IP address"/>
                                                    <span class="text-danger hide"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
												 <div class="form-group" style="margin-top: 0px;">
													 <label>Apply mode</label>
                                                <select  data-init-plugin="select2" id="selClearType" style="width: 100%;">
                                                    <option value="DontClearData" selected>Don't clear data</option>
                                                    <option value="ClearData" >Clear data</option>
                                                </select>
												</div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" id="divbtn"><br>
                                                    <input type="hidden" id="btnStatus" name="btnStatus" value=""/>
                                                        <button type="button" style="margin-top: 6px;" class="btn btn-cons btn-info btn-sm" onclick="saveTestStationSetup();"><span></span> </button>
                                                </div>
                                            </div>

                                            <div class="col-md-2" align="left" style="margin-top: 20px;" >
                                                <div class="form-group" style="height:50px">
                                                    <h5>
                                                        <a class="text-black" onclick="reload(true);"> <i class="fa fa-refresh"></i>&nbsp; Reload</a>
                                                    </h5>
                                                
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group" id="divbtn" style="margin-top: 5px;"><br>
                                                    <a class="btn btn-danger btn-cons btn-sm"
                                                        onclick="return __ClearTag.removeConfirm();">
                                                        Clear tag
                                                    </a>
                                                </div>
                                            </div>
                                    </div>


                                    <div class="row m-t-0">
                                            <div class="col-md-4">
                                                <div class="form-group" id="divTestState">
                                                    <h5><span style="font-weight: 400;padding-left: 2px">Test State</span> : 
                                                    <label class="badge badge-light disable " id="DISTS" style="font-size: medium;">N/A</label>
                                                    </h5>
                                                </div>

                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group" id="divCreateBy">
                                                    <h5><span style="font-weight: 400;padding-left: 2px">Last Update </span>: 
                                                    <label style="font-size: medium !important;font-weight: 400 !important;">N/A</label>
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group" id="Totalunitsconnected">
                                                    <h5><span style="font-weight: 400;padding-left: 2px">Total units connected</span> : 
                                                    <label style="font-size: medium !important;font-weight: 400 !important;">N/A</label>
                                                    </h5>
                                                </div>
                                            </div>

                                    </div>
                                    <div class="row m-t-0">
                                        <div class="col-md-4">
                                           
                                        </div>
                                                <div class="col-md-4">
                                                    <div class="form-group" style="margin-top: 16px;">

                                                        <div class="progress" style="height:20px" id="progress">
                                                            <div class="progress-bar" style="background-color:green !important;border-radius: 10px" id="myProgress"><label>0%</label></div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-4"></div>

                                                
                                    </div>

                                    <div class="row m-t-0">
                                                <div class="col-md-12">
                                                    <div>
                                                        
                                                        <table id="testStationTable" class="table table-striped" cellspacing="0" width="100%">
                                                            <thead class="bg-white">
                                                                <tr>
                                                                    <th>IPAddress</th>
                                                                    <th class="p-l-20">Connected</th>
                                                                    <th class="p-l-20">Description</th>
                                                                    <th class="p-l-20">LastUpdate</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL CONFIRMATION ALERT -->
<div class="modal fade slide-up disable-scroll" id="modalClearCnfirmation" tabindex="-1" role="dialog"
    aria-hidden="false">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content-wrapper">
            <div class="modal-content mdal-custom">
                <div class="modal-body p-b-10">
                    <p class="bold fs-20 p-t-20">
                        Do you wish to Start Autodiscovery ?
                        This might take up To 30 minutes to discover all connected devices.
                    </p>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success pull-right m-r-10"
                        onclick="__ClearTag.clear_tag();">OK</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- END CONFIRMATION ALERT -->


<!-- END PAGE CONTENT -->
<?php include_once("tpl/footer.tpl.php"); ?>

<script type="text/javascript">
    var teststationstate = "";
    var totalLogs = 0;
    var intialStage = 1;

    $(document).ready(function()	{
        getTestStationData(true);
        getTableData();
       setInterval(reload, 10000);
    });

    function removeConfirm(){
        $("#modalClearCnfirmation").modal("show");
    }

    function countIp(ip1, ip2){
        let diff = 0;
        const aIp1 = ip1.split(".");
        const aIp2 = ip2.split(".");
        if (aIp1.length !== 4 || aIp2.length !== 4) {
            return "Invalid IPs: incorrect format";
        }
        for (x = 0; x < 4; x++) {
            console.log(aIp1[x],"=",aIp2[x]);
            if (
                isNaN(aIp1[x]) || isNaN(aIp2[x])
                || aIp1[x] < 0 || aIp1[x] > 255
                || aIp2[x] < 0 || aIp2[x] > 255
            ) {
                return "Invalid IPs: incorrect values"
            }
           
            diff += (aIp1[x] - aIp2[x]);
            if ( x ==  3) diff -= 1;    // negative diff value. abs taken later
            if ( x != 3 )  diff *= (255 * (3-x));
            
        }
        return Math.abs(diff);
    };

    function getProgressBarData(){
        if(teststationstate == ""){
            $("#myProgress label").html("0%");
            document.getElementById("myProgress").style.width = "0%";
        }else{
            var $StartIPAddress = $("#SIPADD").val();
            var $StopIPAddress = $("#STIPADD").val();

            var CountIP = countIp($StartIPAddress,$StopIPAddress);
            var pertentageofbar = Math.round((totalLogs/CountIP)*100);
			
            $("#myProgress label").html(pertentageofbar+"%");
            document.getElementById("myProgress").style.width = pertentageofbar+"%";
        }
    }
    
    function reload(loader=false){
		console.log(teststationstate);
        if(loader){
            getTestStationData(loader);
            getTableData();
            getProgressBarData();
        }else{
            if(teststationstate == "" || teststationstate =="Scheduled" || teststationstate == "In-Progress"){
                getTableData();
                getTestStationData(loader);
                getProgressBarData();
            }
        }
        
       
    }
    
    function getTableData(){
        $.ajax ({
			type:'POST',
				url:'ajax.php',
				data: { 'c':'TestStation', 'a':'fetchAllLogs'},
				dataType:'json',
				success: function(response){
					
					var dataSet=[];
					
					if ( response.status == 1 )	{
						jQuery.each(response.data, function(i, val) {
							console.log(timezoneStr);
							var lrt = moment.tz(val.LastUpdate,timezoneStr).fromNow();
							if ( val.LastUpdate == null )	lrt = "";
							if ( val.Connected=='Y' ) dataSet.push(Array(val.IPAddress,val.Connected,val.Description,val.LastUpdate));
						});
					}
					
					$('#testStationTable').DataTable( {
						
						data: dataSet,
						"paging":   true,
						"ordering": true,
						"aaSorting": [],
						"info":     false,
						"destroy": true,
                        "searching": true,
                        "bLengthChange": true,
                        "lengthMenu": [ 25, 50, 100 ],
						columns: [
							{ title: "IPAddress" },

                            { 'data': null, "render": 
                                function (item) {  
                                    if(item[1] == 'N') return "No"
                                    else return "Yes" ; 
                                } 
                            },

							{ title: "Description" },
							{ 'data': null, "render": 
                                function (item) {  
                                    return moment(item[3],"YYYY-MM-DD HH:mm:ss ").format("MM-DD-YYYY HH:mm:ss "); 
                                } 
                            },
						],
                        "oLanguage": {
                            "sEmptyTable": "No Log Entries"
                        }
					} );
				},
				error: function(r){
					console.log(r);
				}
		});
    }

    function getTestStationData(loader=false)
    {
        var $data = {
            c: 'TestStation',
            a: 'GetTestStationData',
        };
        $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: $data,
            dataType: 'json',
            beforeSend: function () {
                if ( loader) __showLoadingAnimation();
            },
            success: function (res) {
                
                if(res.status == "1")	{	
                    
                    var TestStation = res.testStationData;
                    $("#divTestState label").html(TestStation["TestState"]);
                    teststationstate = TestStation["TestState"];
                    totalLogs = TestStation["TotalLogs"];
                    $("#Totalunitsconnected label").html(TestStation["TotalLogsY"]);

                    //alert(TestStation["selClearType"]);
                    $("#selClearType").val(TestStation["selClearType"]).change();



                    if(TestStation["TestState"] == "Stopped"){
                        $("#divbtn span").html("Start");
                        $("#btnStatus").val("Stop");
                        $("#DISTS").removeClass("badge-light");
                        $("#DISTS").removeClass("badge-success");
                        $("#DISTS").addClass("badge-danger");
                        
                    }else{
                        $("#divbtn span").html("Stop");
                        $("#btnStatus").val("Start");

                        if(TestStation["TestState"]=="Failed"){
                            $("#DISTS").removeClass("badge-light");
                            $("#DISTS").removeClass("badge-success");
                            $("#DISTS").addClass("badge-danger");
                        }else{
                            $("#DISTS").removeClass("badge-light");
                            $("#DISTS").removeClass("badge-danger");
                            $("#DISTS").addClass("badge-success");
                        
                        }
                    }

                    if(TestStation["TestState"] == "Completed" || TestStation["TestState"] == "Failed"){
                        $("#divbtn span").html("Start");
                        $("#btnStatus").val("Stop");
                    }

                    if(loader){
                        $("#SIPADD").val(TestStation["StartIPAddress"]);
                        $("#STIPADD").val(TestStation["StopIPAddress"]);
                        $("#Ids").val(TestStation["Ids"]);
                    }

                    $("#divCreateBy label").html(moment(TestStation["CreateBy"],"YYYY-MM-DD HH:mm:ss ").format("MM-DD-YYYY HH:mm:ss ")); 
                    //$("#divCreateBy label").html(moment.tz(TestStation["LastUpdate"],timezoneStr).fromNow());
                    if(intialStage == 1){
                        reload(false);
                        intialStage = 0;
                    }
                    getProgressBarData();

                }
                else {
                    $("#divbtn span").html("Start");
                    $("#btnStatus").val("Start");
                }
                if ( loader) __hideLoadingAnimation();
                
            },
            complete: function () {
                if ( loader) __hideLoadingAnimation();
            },
            error: function (r) {
                 if ( loader) __hideLoadingAnimation();
                $createAlert({status : "fail",title : "Failed",text :r});
            }
        });

    }

function removeErrorAlert(id){
	$("#"+id).removeClass("has-error");
	$("#"+id+" span").html("");
	$("#"+id+" span").addClass("hide");
}

function removeErrorAlert(divID){
	$("#"+divID).removeClass("has-error");
	$("#"+divID+" span").addClass("hide");
	$("#"+divID+" span").html("");
}

    function validateIps(ip1,ip2){
        const aIp1= ip1.split(".");
        const aIp2 = ip2.split(".");

        if( parseInt(aIp1[0]) <= parseInt(aIp2[0]) ){
            if( parseInt(aIp1[1]) <= parseInt(aIp2[1]) ){
                if( parseInt(aIp1[2]) <= parseInt(aIp2[2]) ){
                    if( parseInt(aIp1[3]) <= parseInt(aIp2[3]) ){
                        return true;
                    }else return false;
                }else return false;
            }else return false;
        }else return false;       
    }
    
 
function saveTestStationSetup()
{
    var $StartIPAddress = $("#SIPADD").val();
    var $StopIPAddress = $("#STIPADD").val();
    var $btnStatus = $("#btnStatus").val();
    var $Ids =  $("#Ids").val();
    var $selClearType = $("#selClearType").val();
    
    
    if($StartIPAddress == "" || !validateIPAddress($StartIPAddress) ){
        $("#divSIPADD").addClass("has-error");
        $("#divSIPADD span").html("Please enter a valid IP Address.");
        $("#divSIPADD span").removeClass("hide");
        setTimeout(function() { 
            $("#divSIPADD").focus();
        }, 200);
        return false;
    }
   
    if($StopIPAddress == "" ||  !validateIPAddress($StopIPAddress) ){
        $("#divSTIPADD").addClass("has-error");
        $("#divSTIPADD span").html("Please enter a valid IP Address.");
        $("#divSTIPADD span").removeClass("hide");
        setTimeout(function() { 
            $("#divSTIPADD").focus();
        }, 200);
        return false;
    }

    if( $StopIPAddress == $StartIPAddress ){
        $("#divSTIPADD").addClass("has-error");
        $("#divSTIPADD span").html("Stop IP Address is equcal to Start IP Address.");
        $("#divSTIPADD span").removeClass("hide");
        setTimeout(function() { 
            $("#divSTIPADD").focus();
        }, 200);
        return false;
    }


    if(!validateIps($StartIPAddress,$StopIPAddress)){
        $("#divSTIPADD").addClass("has-error");
        $("#divSTIPADD span").html("Different IP Address range.");
        $("#divSTIPADD span").removeClass("hide");
        setTimeout(function() { 
            $("#divSTIPADD").focus();
        }, 200);
        return false;
    }

   
    var $data = {
        c: 'TestStation',
        a: 'SaveTestStationSetup',
        StartIPAddress: $StartIPAddress,
        StopIPAddress: $StopIPAddress,
        btnStatus:$btnStatus,
		Ids:$Ids,
        selClearType:$selClearType,
         'reqType': 'save'
    };

    $.ajax({
        type: 'POST',
        url: '<?php echo BASE_PATH; ?>ajax.php',
        data: $data,
        dataType: 'json',
        beforeSend: function () {
            __showLoadingAnimation();
        },
        success: function (res) {
            __hideLoadingAnimation();
            if(res.status == 1){	
                reload(true);
            }
            else {
                $createAlert({status : "fail",title : "Failed",text :"Unable to save Auto Discovery setup."});
                
            }
            
        },
        complete: function () {
            __hideLoadingAnimation();
        },
        error: function (r) {
            __hideLoadingAnimation();
            $createAlert({status : "fail",title : "Failed",text :r});
        }
    });
}

function validateIPAddress(ipaddress)
{
	var pattern = /\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/;
	return pattern.test(ipaddress);
}
</script>
