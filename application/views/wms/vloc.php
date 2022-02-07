<div style="padding:5px" >
    <div class="container-xxl">
        <div class="row">
            <div class="col-sm-4 mb-1 pr-1 pl-1">                                          
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Location</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 order-md-0">
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Code</span>                                        		
                                        <input type="text" class="form-control" id="loc_txtid_wh" required readonly >                                        
                                        <button class="btn btn-outline-secondary" type="button" id="loc_btn_findwh"><i class="fas fa-search"></i></button>                                        
                                    </div>
                                </div>						
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Name</span>                                        					
                                        <input type="text" class="form-control" id="loc_txt_name_wh" required readonly>								
                                    </div>
                                </div>
                            </div>                            						
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="btn-group btn-group-sm">
                                        <button title="Add new" class="btn btn-outline-primary" type="button" id="loc_btn_newwh"><i class="fas fa-file"></i></button>
                                        <button title="Save" class="btn btn-primary" type="button" id="loc_btn_savewh"><i class="fas fa-save"></i></button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
            <div class="col-sm-8 pr-1 pl-1">
                <div class="card mb-9 shadow-sm">			
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Rack</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 order-md-0 pl-0 pr-0">
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Rack</span>                                        
                                        <input type="hidden" id="loc_txtrack_id">
                                        <input type="text" class="form-control" id="loc_txtrack_rack" required onkeyup="loc_autorackno()">                                        
                                    </div>
                                </div>	
                                <div class="col-md-4 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">No</span>                                        				
                                        <input type="text" class="form-control" id="loc_txtrack_no" required onkeyup="loc_autorackno()">								
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Category</span>                                        					
                                        <input type="text" class="form-control" id="loc_txtrack_cat" required onkeyup="loc_autorackno()">								
                                    </div>
                                </div>					
                            </div>  
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Rack Code</span>                                        					
                                        <input type="text" class="form-control" id="loc_txtrackcd" required >								
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="input-group input-group-sm">                                        
                                        <span class="input-group-text">Order No</span>                                        					
                                        <input type="text" class="form-control" id="loc_orderno" required >								
                                    </div>
                                </div>
                            </div>                         
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="btn-group btn-group-sm">
                                        <button title="New" class="btn btn-outline-primary" type="button" id="loc_btnrack_new"><i class="fas fa-file"></i></button>                                        
                                        <button title="Save rack data" class="btn btn-primary" type="button" id="loc_btnrack_save"><i class="fas fa-save"></i></button>                                        
                                        <button title="Import data" class="btn btn-outline-success" type="button" id="loc_btn_import"><i class="fas fa-file-import"></i></button>
                                    </div>                                    
                                </div>
                                <div class="col-md-6 mb-3 text-right">                                   
                                    <button title="Show All Rack" class="btn btn-outline-success btn-sm" type="button" id="loc_btn_showall">Show All</button>                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <table id="loc_tblrack" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                        <thead class="table-light">
                                            <tr>	
                                                <th>Warehouse</th>
                                                <th>Rack Code</th>
                                                <th>Location</th>
                                                <th>Rack</th>
                                                <th>Rack No</th>                            
                                                <th>Category</th>												
                                                <th>Order No</th>
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
            </div>
    </div>				                  	
</div>
<div class="modal fade" id="LOC_WH">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Location List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="loc_txt_searchnm" maxlength="15" required placeholder="Location...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="lblinfo_tblloc"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="loc_tblsearch" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Location</th>                                
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
    </div>
</div>
<div class="modal fade" id="LOC_IMPORTDATA">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Import data</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group">                        
                        <button title="Download a Template File (*.xls File)" id="loc_btn_download" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>                                                
                        <input type="file" id="loc_xlf_new"  class="form-control">
                        <button id="loc_btn_startimport" class="btn btn-primary btn-sm">Start Importing</button>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="loc_tbl_import" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Rack</th>
                                    <th>No</th>
                                    <th>Category</th>
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
    </div>
</div>
<script>
var locd_isnew = true;
var locd_oldrackcd = '';
$("#loc_btn_startimport").click(function(){
    if (document.getElementById('loc_xlf_new').files.length==0){
        alert('please select file to upload');
    } else {			
        var fileUpload = $("#loc_xlf_new")[0]; 
        //Validate whether File is valid Excel file.
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if (regex.test(fileUpload.value.toLowerCase())) {
                console.log('bisa');
                if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            console.log('saya perambaan selain IE');
                            reader.onload = function (e) {
                                loc_ProcessExcel(e.target.result);
                            };
                            reader.readAsBinaryString(fileUpload.files[0]);
                        } else {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                        data += String.fromCharCode(bytes[i]);
                                }
                                loc_ProcessExcel(data);
                            };
                            reader.readAsArrayBuffer(fileUpload.files[0]);
                        }
                } else {
                        alert("This browser does not support HTML5.");
                }
        } else {
                alert("Please upload a valid Excel file.");
        }
    }
});
    function loc_ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];			
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
		

		var dvExcel = $("#loc_tbl_import tbody");
        let tohtml = '';
        //Add the data rows from Excel file.
        for (let i = 0; i < excelRows.length; i++) {
            tohtml += '<tr style="cursor:pointer">'+
            '<td>'+excelRows[i].RAK+'</td>'+
            '<td>'+excelRows[i].NO+'</td>'+
            '<td>'+excelRows[i].CAT+'</td>'+
            '</tr>';										
        }
        for (let i = 0; i < excelRows.length; i++) {
            let mgrp = excelRows[i].GROUP;
            let mrack = excelRows[i].RAK;
            let mrackno = excelRows[i].NO;
            let mrackcat = excelRows[i].CAT;
            let mrackcd = excelRows[i].RAKCD;   
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTLOC/import')?>",
                data: {ingroup: mgrp, inrack: mrack, inno: mrackno, incat: mrackcat, inrow : i, inrackcd : mrackcd},
                dataType: "json",
                success: function (response) {
                    let table = $("#loc_tbl_import tbody");
                    table.find('tr').eq(response[0].indx).find('td').eq(2).text(response[0].status);
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
        dvExcel.html(tohtml);        
	};
$("#loc_btn_import").click(function(){
    $("#LOC_IMPORTDATA").modal('show'); $("#loc_tbl_import tbody").html('');
});
$("#loc_btnrack_new").click(function(){
    var mgrp = $("#loc_txtid_wh").val();
    if(mgrp==''){
        $("#loc_btn_findwh").focus();
        alertify.warning('select location first !');
        return;
    }
    $("#loc_txtrack_rack").val('');$("#loc_txtrack_no").val('');$("#loc_txtrack_cat").val('');$("#loc_txtrack_rack").focus();
    document.getElementById('loc_orderno').value='';
    locd_isnew = true;
});
initLoc('getbygroup');
function initLoc(p1){  
    var mgid = $("#loc_txtid_wh").val();
    $('#loc_tblrack thead tr').clone(true).appendTo('#loc_tblrack thead' );
    $('#loc_tblrack thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" value="" class="form-control form-control-sm" placeholder="Search '+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( tableLOC.column(i).search() !== this.value ) {
                tableLOC
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );
    tableLOC =  $('#loc_tblrack').DataTable({
        orderCellsTop: true,       
        destroy: true,
        scrollX: true,
        scrollY: true,
        ajax: {
            url : '<?=base_url("MSTLOC/")?>'+p1,
            type: 'get',
            data: {ingrp: mgid}
        },
        columns:[
            { "data": 'MSTLOC_GRP'},
            { "data": 'MSTLOC_CD'},
            { "data": 'MSTLOCG_NM'},
            { "data": 'MSTLOC_RACK'},
            { "data": 'MSTLOC_NO'},
            { "data": 'MSTLOC_CAT' },                                       
            { "data": 'MSTLOC_ORDER' }                                       
        ]        
    });             
}
$("#loc_txtrack_no").keypress(function (e) { 
    if(e.which==13){$("#loc_txtrack_cat").focus();}
});
$("#loc_txt_name_wh").keypress(function (e) { 
    if(e.which==13){
        $("#loc_btn_savewh").click();
    }
});
$("#loc_txtrack_cat").keypress(function (e) { 
    if(e.which==13){
        $("#loc_btnrack_save").click();
    }
});
$("#loc_btnrack_save").click(function(){
    var rackid = $("#loc_txtrack_id").val();
    var mgrp = $("#loc_txtid_wh").val();
    if(mgrp==''){
        $("#loc_txtid_wh").focus();
        alertify.warning('Please select Location first !');
        return;
    }
    var mrack  =$("#loc_txtrack_rack").val();
    if(mrack==''){
        $("#loc_txtrack_rack").focus();
        alertify.warning('Please select <b>Rack</b> first !');
        return;
    }
    var mrackno  =$("#loc_txtrack_no").val();
    if(mrackno==''){
        $("#loc_txtrack_no").focus();
        alertify.warning('Please select <b>Rack No</b> first !');
        return;
    }
    var mrackcat  =$("#loc_txtrack_cat").val();    
    var mrackcd  =$("#loc_txtrackcd").val();
    if(mrackcd==''){
        $("#loc_txtrackcd").focus();
        alertify.warning('Please select <b>Category</b> first !');
        return;
    }
    let morderno = document.getElementById('loc_orderno').value;
    if(morderno.trim()==''){
        morderno='0';
    }
    $.ajax({
        type: "post",
        url: "<?=base_url('MSTLOC/setloc_d')?>",
        data: {inid:rackid, ingroup: mgrp, inrack: mrack, inno: mrackno, incat: mrackcat, inisnew: locd_isnew,
        inrackcd:  mrackcd, inoldrack: locd_oldrackcd, inorderno: morderno},
        dataType: "text",
        success: function (response) {
            alertify.message(response);
            $("#loc_txtrack_rack").val('');$("#loc_txtrack_no").val('');
            $("#loc_txtrack_cat").val('');initLoc('getbygroup');
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });        
});
$('#loc_tblsearch tbody').on( 'click', 'tr', function () {     
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#loc_tblsearch tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    var mid =  $(this).closest("tr").find('td:eq(0)').text();
    var mnm =  $(this).closest("tr").find('td:eq(1)').text();
    $("#loc_txtid_wh").val(mid);
    $("#loc_txt_name_wh").val(mnm);
    $("#LOC_WH").modal('hide');
    $("#loc_txt_name_wh").prop('readonly', false);
    $("#loc_txt_name_wh").focus();initLoc('getbygroup');
});
$('#loc_tblrack tbody').on( 'click', 'tr', function () {     
    if ($(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {
        $('#loc_tblrack tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    var mid         = $(this).closest("tr").find('td:eq(0)').text();
    var mrackcd     = $(this).closest("tr").find('td:eq(1)').text();
    var mrack       = $(this).closest("tr").find('td:eq(3)').text();
    var mrackno     = $(this).closest("tr").find('td:eq(4)').text();
    var mrackcat    = $(this).closest("tr").find('td:eq(5)').text();
    var morderno    = $(this).closest("tr").find('td:eq(6)').text();
    $("#loc_txtrack_rack").val(mrack);
    $("#loc_txtrack_no").val(mrackno);
    $("#loc_txtrack_cat").val(mrackcat);
    $("#loc_txtrack_id").val(mid);
    $("#loc_txtrackcd").val(mrackcd);
    document.getElementById('loc_orderno').value=morderno;
    locd_oldrackcd = mrackcd;
    locd_isnew = false;
});
$("#loc_btn_showall").click(function(){
    initLoc('getall');
});
$("#LOC_WH").on('shown.bs.modal', function(){
    $("#loc_txt_searchnm").focus();
});
$("#loc_txt_searchnm").keypress(function (e) { 
    if(e.which==13){
        var mkeys = $(this).val();
        $("#loc_tblsearch tbody").html('<tr><td colspan="2">Not found</td></tr>');
        $.ajax({
            type: "get",
            url: "<?=base_url('MSTLOC/search')?>",
            data: {insearch: mkeys},
            dataType: "json",
            success: function (response) {
                var ttlrows =  response.length;
                var tohtml = '';
                for(var i=0;i<ttlrows;i++){
                    tohtml += '<tr style="cursor:pointer">'+
                    '<td>'+response[i].MSTLOCG_ID+'</td>'+
                    '<td>'+response[i].MSTLOCG_NM+'</td>'+         
                    '</tr>';
                }
                $("#loc_tblsearch tbody").html(tohtml);
            }
        });
    }
});
$("#loc_btn_findwh").click(function(){
    $("#loc_tblsearch tbody").html('');
    $("#LOC_WH").modal('show');
});
$("#loc_btn_newwh").click(function(){
    $("#loc_txtid_wh").val('');
    $("#loc_txt_name_wh").val('');
    $("#loc_txtid_wh").prop('readonly', false);
    $("#loc_txt_name_wh").prop('readonly',false);
    $("#loc_txtid_wh").focus();
    initLoc('getbygroup');
});
$("#loc_btn_savewh").click(function(){
    var locnm = $("#loc_txt_name_wh").val();
    var locid = $("#loc_txtid_wh").val();
    if(locnm.trim()==''){
        $("#loc_txt_name_wh").focus();
        return;
    }
    $.ajax({
        type: "post",
        url: "<?=base_url('MSTLOC/setloc')?>",
        data: {innm: locnm, inid:locid},
        dataType: "text",
        success: function (response) {
            alertify.message(response);
            $("#loc_txt_name_wh").val('');
            $("#loc_txtid_wh").val('');$("#loc_txtid_wh").focus();
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });
});
$("#loc_txtid_wh").keypress(function (e) { 
    if(e.which==13){
        $("#loc_txt_name_wh").focus();
    }
});
$("#loc_btn_download").click(function (e) { 
    e.preventDefault();
    $.ajax({
        type: "get",
        url: "<?=base_url('MSTLOC/getlinkitemtemplate')?>",
        dataType: "text",                       
        success:function(response) {          
            window.open(response, '_blank');
            alertify.message("<i>Please wait...</i>");
        },
        error:function(xhr,ajaxOptions, throwError) {
            alert(throwError);
        }
    });
});


function loc_autorackno(){
    let jrackno = $("#loc_txtrack_rack").val();
    let jno     = $("#loc_txtrack_no").val();
    let jcat    = $("#loc_txtrack_cat").val();
    let jrackcd = jrackno + jno + jcat;
    $("#loc_txtrackcd").val(jrackcd);
}
</script>