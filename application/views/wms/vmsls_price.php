<div style="padding: 10px">
	<div class="col-md-12 order-md-1">       
        <div class="row">				
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Item Code</span>
                    </div>
                    <input type="text" class="form-control" id="slsprc_txt_itemcd" required readonly>
                    <div class="input-group-append">
                        <button title="Find Item" class="btn btn-outline-secondary" type="button" id="slsprc_btnfinditem"><i class="fas fa-search"></i></button>                        
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Descr. 1</span>
                    </div>
                    <input type="text" class="form-control" id="slsprc_txt_itmdsc1" required readonly>                   
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Descr. 2</span>
                    </div>
                    <input type="text" class="form-control" id="slsprc_txt_itmdsc2" required readonly>                   
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >SPT No</span>
                    </div>
                    <input type="text" class="form-control" id="slsprc_txt_spt" required readonly>                   
                </div>
            </div>                  
        </div>
        <div class="row">            
            <div class="col-md-6 mb-1">
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Customer</span>
                    </div>
                    <select class="form-control" id="slsprc_sel_cus" data-style="btn-info">
                        <option value="-">-</option>
                        <?php                         
                        foreach($lcus as $r){
                            ?>
                            <option value="<?=trim($r->MCUS_CUSCD)?>"><?='['.trim($r->MCUS_CURCD).'] '.$r->MCUS_CUSNM?></option>
                            <?php
                        }                        
                        ?>
                    </select>        
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Price</span>
                    </div>
                    <input type="text" class="form-control" id="slsprc_txt_prc" >
                </div>
            </div>                
        </div>
        <div class="row">            
            <div class="col-md-12 mb-3"> 
                <div class="btn-group btn-group-sm">
                    <button title="Save" class="btn btn-primary" id="slsprc_btn_add"><i class="fas fa-save"></i></button>                    
                    <button title="Import Template data to System" class="btn btn-outline-success" id="slsprc_btn_import"><i class="fas fa-file-import"></i></button>
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-0">
                <div class="table-responsive">
                    <table id="slsprc_tbl" class="table table-sm table-striped table-bordered" style="width:100%;cursor:pointer">
                        <thead class="thead-light">
                            <tr>                                
                                <th class="d-none">Customer</th>
                                <th>Customer</th>
                                <th>Currency</th>
                                <th class="text-right">Price</th>                                
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
<div class="modal fade" id="slsprc_MODITM">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Item List</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="slsprc_txtsearch" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search by</span>
                        </div>
                        <select id="slsprc_srchby" class="form-control">
                            <option value="ic">Item Code</option>
                            <option value="in">Item Name</option>                            
                            <option value="spt">SPT No</option>
                        </select>                  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge badge-info" id="lblinfo_tblslsprc"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="slsprc_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Descr. 1</th>
                                    <th>Descr. 2</th>
                                    <th>SPT No</th>
                                    <th>Type</th>
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
<div class="modal fade" id="slsprc_IMPORTDATA">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Import data</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button title="Download a Template File (*.xls File)" id="slsprc_btn_download" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>
                        </div>
                        <div class="custom-file">
                            <input type="file" id="slsprc_xlf_new"  class="custom-file-input">
                            <label class="custom-file-label" for="slsprc_xlf_new">Choose file</label>
                        </div>
                        <div class="input-group-append">                            
                            <button id="slsprc_btn_startimport" class="btn btn-primary btn-sm">Start Importing</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="slsprc_tbl_import" class="table table-hover table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Customer</th>
                                    <th class="text-right">Price</th>
                                    <th>Info</th>
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
var slsprc_colidx = 0;
$("#slsprc_btnfinditem").click(function(){
    $("#slsprc_MODITM").modal('show');
});
$("#slsprc_MODITM").on('shown.bs.modal', function(){
    $("#slsprc_txtsearch").focus();
});
$("#slsprc_txtsearch").keypress(function(e){
    if(e.which==13){
        var mkey = $(this).val();
        var msearchby = $("#slsprc_srchby").val();
        $("#lblinfo_tblslsprc").text("Please wait...");
        $.ajax({
            type: "get",
            url: "<?=base_url('MSTITM/searchfg')?>",
            data: {cid : mkey, csrchby: msearchby},
            dataType: "json",
            success: function (response) {
                var ttlrows = response.length;
                var tohtml ='';
                var mtype = '';
                for(var i=0;i<ttlrows;i++){
                    if(response[i].MITM_MODEL.trim()=='1'){
                        mtype = 'FG';
                    } else {
                        mtype = 'RM';
                    }
                    tohtml += '<tr style="cursor:pointer">'+
                    '<td style="white-space:nowrap">'+response[i].MITM_ITMCD.trim()+'</td>'+
                    '<td style="white-space:nowrap">'+response[i].MITM_ITMD1+'</td>'+
                    '<td>'+response[i].MITM_ITMD2+'</td>'+
                    '<td style="white-space:nowrap">'+response[i].MITM_SPTNO+'</td>'+
                    '<td>'+mtype+'</td>'+
                    '</tr>';
                }
                $("#lblinfo_tblslsprc").text("");
                $('#slsprc_tblitm tbody').html(tohtml);
            }
        });
    }
});
$('#slsprc_tblitm tbody').on( 'click', 'tr', function () { 
    if ( $(this).hasClass('table-active') ) {
        $(this).removeClass('table-active');
    } else {
        $('#slsprc_tblitm tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }
    var mitem       = $(this).closest("tr").find('td:eq(0)').text();
    var mitemnm     = $(this).closest("tr").find('td:eq(1)').text();
    var mitemnm2    = $(this).closest("tr").find('td:eq(2)').text();
    var mspt        = $(this).closest("tr").find('td:eq(3)').text();
    
        
    $("#slsprc_txt_itemcd").val(mitem); $("#slsprc_txt_itmdsc1").val(mitemnm);
    $("#slsprc_txt_itmdsc2").val(mitemnm2);$("#slsprc_txt_spt").val(mspt);    
    $("#slsprc_MODITM").modal('hide');
    slsprc_findprice();
});

$('#slsprc_tbl tbody').on( 'click', 'td', function () { 
    slsprc_colidx = $(this).index();    
});
$('#slsprc_tbl tbody').on( 'click', 'tr', function () {
    let mcus  = $(this).closest("tr").find('td:eq(0)').text();
    let mprice = $(this).closest("tr").find('td:eq(3)').text();   
    $('#slsprc_sel_cus').selectpicker('val', mcus.trim());

    document.getElementById('slsprc_txt_prc').value=mprice;
});

function slsprc_findprice(){
    var mitem = $("#slsprc_txt_itemcd").val();
    $.ajax({
        type: "get",
        url: "<?=base_url('MSLSPRC/getbyitemcd')?>",
        data: {incd: mitem},
        dataType: "json",
        success: function (response) {
            let ttlrows = response.length;
            let tohtml = '';
            let mprc = '';
            for(let i=0;i<ttlrows;i++){
                mprc = response[i].MSLSPRICE_PRICE;
                if (mprc.substr(0,1)=='.'){
                    mprc='0'+mprc;
                }
                tohtml += '<tr>'+                
                '<td class="d-none">'+response[i].MSLSPRICE_CUSCD+'</td>'+
                '<td>'+response[i].MCUS_CUSNM+'</td>'+
                '<td>'+response[i].MCUS_CURCD+'</td>'+
                '<td class="text-right">'+mprc+'</td>'+
                '</tr>';
            }
            $("#slsprc_tbl tbody").html(tohtml);
        }
    });
}
$("#slsprc_btn_add").click(function (e) { 
    e.preventDefault();
    let mitm = $("#slsprc_txt_itemcd").val();    
    let mcus = $("#slsprc_sel_cus").val();
    let mprc = document.getElementById('slsprc_txt_prc').value;
    if(mitm==''){
        $("#slsprc_txt_itemcd").focus();return;
    }        
    if(mcus=='-'){
        alertify.warning('select customer first');
        $("#slsprc_sel_cus").focus();
        return;
    }    
    if(isNaN(mprc.trim())){alertify.warning('Invalid number'); document.getElementById('slsprc_txt_prc').focus(); return;}    
    $.ajax({
        type: "post",
        url: "<?=base_url('MSLSPRC/set')?>",
        data: {initem: mitm,incus: mcus, inprc: mprc },
        dataType: "text",
        success: function (response) {
            alertify.message(response);  slsprc_findprice();          
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
});

$("#slsprc_sel_cus").selectpicker({liveSearch: true});
$("#slsprc_sel_cus").selectpicker('render');
$("#slsprc_sel_cus").selectpicker('refresh');
$('#slsprc_sel_cus').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    $("#slsprc_txt_prc").focus();
});
$("#slsprc_btn_download").click(function (e) { 
    e.preventDefault();
    $.ajax({
        type: "get",
        url: "<?=base_url('MSLSPRC/getlinkitemtemplate')?>",
        dataType: "text",                       
        success:function(response) {          
            window.open(response, '_blank');
            alertify.message("<i>Downloading...</i>");
        },
        error:function(xhr,ajaxOptions, throwError) {
            alert(throwError);
        }
    });
});
$("#slsprc_btn_import").click(function (e) { 
    e.preventDefault();
    $("#slsprc_IMPORTDATA").modal('show');
});
$("#slsprc_btn_startimport").click(function (e) { 
    e.preventDefault();
    if (document.getElementById('slsprc_xlf_new').files.length==0){
        alert('please select file to upload');
    } else {			
        var fileUpload = $("#slsprc_xlf_new")[0]; 
        //Validate whether File is valid Excel file.
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if (regex.test(fileUpload.value.toLowerCase())) {                
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        console.log('saya perambaan selain IE');
                        reader.onload = function (e) {
                            mslsprice_ProcessExcel(e.target.result);
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
                            mslsprice_ProcessExcel(data);
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

    function mslsprice_ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];			
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
		

		var dvExcel = $("#slsprc_tbl_import tbody");
        var tohtml = '';
        //Add the data rows from Excel file.
        for (var i = 0; i < excelRows.length; i++) {            
            tohtml += '<tr style="cursor:pointer">'+
            '<td>'+excelRows[i].ITEMCD+'</td>'+
            '<td>'+excelRows[i].CUSTOMER+'</td>'+
            '<td class="text-right">'+excelRows[i].PRICE+'</td>'+
            '<td>Please wait...</td>'+
            '</tr>';										
        }
        for (var i = 0; i < excelRows.length; i++) {
            var mitem   = excelRows[i].ITEMCD;
            var mcus    = excelRows[i].CUSTOMER;
            var mprc   = excelRows[i].PRICE;
            $.ajax({
                type: "post",
                url: "<?=base_url('MSLSPRC/import')?>",
                data: {initem: mitem, incus: mcus, inprc: mprc, inrowid: i},
                dataType: "json",
                success: function (response) {
                    var table = $("#slsprc_tbl_import tbody");
                    table.find('tr').eq(response[0].indx).find('td').eq(3).text(response[0].status);
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
        dvExcel.html(tohtml);        
	};
</script>