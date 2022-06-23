<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Item Code</label>
                    <input type="text" class="form-control" id="itm_txtitmcd">                
                    <button title="Find item" class="btn btn-outline-secondary" type="button" id="itm_btnformoditem"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text" ondblclick="itm_e_update_d1()">Descr. 1</label>                    
                    <input type="text" class="form-control" id="itm_txtitmnm1">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Descr. 2</label>                    
                    <input type="text" class="form-control" id="itm_txtitmnm2">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">SPT No</label>                    
                    <input type="text" class="form-control" id="itm_txtspt">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Type</label>                    
                    <select class="form-select" id="itm_cmbtype">
                        <?=$modell?>                        
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Is Direct ?</label>                    
                    <select class="form-select" id="itm_isdirect">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">HS Code</label>                    
                    <input type="text" class="form-control" id="itm_txt_hscode">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">HS Code (10)</label>                    
                    <input type="text" class="form-control" id="itm_txt_hscode10">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">GR / Pcs</label>                    
                    <input type="text" class="form-control" id="itm_txt_netwg" style="text-align: right">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Pack Weight</label>                    
                    <input type="text" class="form-control" id="itm_txt_grosswg" style="text-align: right">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Box Type</label>                    
                    <input type="text" class="form-control" id="itm_txtitmbox">                    
                    <button title="Find item" class="btn btn-outline-secondary" type="button" id="itm_btnformodbox"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">SPQ</label>                    
                    <input type="text" class="form-control" id="itm_txt_spq" style="text-align: right">
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Max Height</label>                    
                    <input type="text" class="form-control" id="itm_txt_mxheight" style="text-align: right">
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Max Length</label>                    
                    <input type="text" class="form-control" id="itm_txt_mxlength" style="text-align: right">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Color</label>                    
                    <input type="text" class="form-control" id="itm_txt_color" >
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Sheet Qty</label>                    
                    <input type="text" class="form-control" id="itm_txt_shtqty" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Category</label>
                    <input type="text" class="form-control" id="itm_txt_category" >
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="fas fa-pen-to-square"></span> </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="itm_btnShow_modalCategory_eCK()">Batch Update</a></li>                       
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">External Code</label>
                    <input type="text" class="form-control" id="itm_txt_externalcode" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Unit Measurement</label>
                    <select class="form-select" id="itm_cmb_um" >
                        <?=$UMl?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="itm_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="itm_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                    <button title="Synchronize" id="itm_btnsync" class="btn btn-success" ><i class="fas fa-sync"></i> Synchronize</button>
                    <button title="Import Template data to System" class="btn btn-outline-success" id="itm_btn_import"><i class="fas fa-file-import"></i></button>
                </div>                                
            </div>
            <div class="col-md-3 mb-2 text-end">
                <button title="Remove" id="itm_btndelete" class="btn btn-danger btn-sm" onclick="itm_btndelete(this)"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="table-responsive" id="itm_container">
                    <table id="itm_tbldiff" class="table table-bordered border-primary table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" colspan="2">Item Code</th>                                
                                <th class="text-center" rowspan="2">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-success" id="itm_btnsyncall" title="Synchronize all items"><i class="fas fa-sync"></i></button>
                                        <button class="btn btn-secondary" id="itm_btnclose" title="Close table"><i class="fas fa-window-close"></i></button>
                                    </div>                                
                                </th> 
                            </tr>
                            <tr>
                                <th class="text-center">WMS</th>
                                <th class="text-center">Mega</th>
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
<div class="modal fade" id="ITM_MODITM">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Item List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="itm_txtsearch" maxlength="45" onkeypress="itm_txtsearch_eKP(event)" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="itm_srchby" class="form-select">
                            <option value="ic">Item Code</option>
                            <option value="in">Item Name</option>                            
                            <option value="spt">SPT No</option>
                        </select>                  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="lblinfo_tblitm"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="itm_tbl_div">
                        <table id="itm_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Descr. 1</th>
                                    <th class="d-none">Descr. 2</th>
                                    <th>SPT No</th>
                                    <th>Type</th>
                                    <th class="d-none">Indirect</th>
                                    <th>Indirect</th>
                                    <th>HS Code</th>
                                    <th class="d-none">HS Code(10)</th>
                                    <th class="text-end">GR / Pcs</th>
                                    <th class="text-end">Pack Weight</th>
                                    <th >Box</th>
                                    <th class="text-end">SPQ</th>
                                    <th class="text-end d-none">Max Height</th>
                                    <th class="text-end d-none">Max Length</th>
                                    <th >Color</th>
                                    <th >QTY / Sheet</th>
                                    <th >Category</th>
                                    <th >External Code</th>
                                    <th title="Unit of Measurement">UOM</th>
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
<div class="modal fade" id="ITM_IMPORTDATA">
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
                    <div class="input-group input-group-sm">                        
                        <button title="Download a Template File (*.xls File)" id="itm_btn_download" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>                                                
                        <input type="file" id="itm_xlf_new"  class="form-control">                            
                        <button id="itm_btn_startimport" class="btn btn-primary btn-sm">Start Importing</button>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">                
                    <div class="progress">
                        <div id="itm_lblsaveprogress" class="progress-bar progress-bar-success progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only">0</span>
                        </div>
                    </div>
                </div>                
            </div>
        </div>             
      </div>
    </div>
</div>
<div class="modal fade" id="ITM_MODBOX">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Box List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="itm_txtsearchbox" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="lblinfo_tblbox"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="itm_divku">
                        <table id="itm_tblbox" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Box</th>
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

<div class="modal fade" id="ITM_MODCATEGORY">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Update Category</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="itm_bu_categorydiv">
                        <div id="itm_bu_ss_category"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" id="itm_bu_btn_save" onclick="itm_bu_btn_save_eCK()"><span class="fas fa-save"></span> </button>
        </div>
      </div>
    </div>
</div>
<script>    
    var itm_ttlxls = 0;
    var itm_ttlxls_savd = 0;
    var itm_old_itmcd = ''
    function itm_btndelete(p) {
        const itmid = document.getElementById('itm_txtitmcd')
        if(itmid.value.trim().length==0) {
            itmid.focus()
            return
        }
        if(confirm('Are you sure ?')) {
            p.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('MSTITM/remove')?>",
                data: {itm: itmid.value.trim()},
                dataType: "json",
                success: function (response) {
                    p.disabled = false
                    alertify.message(response.status[0].msg)
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    p.disabled = false
                }
            })
        }
    }
    $("#itm_btnsave").click(function(){
        if(confirm("Are you sure ?")){
            const mitmcd      = $("#itm_txtitmcd").val();
            const mitmnm1     = $("#itm_txtitmnm1").val();
            const mitmnm2     = $("#itm_txtitmnm2").val();
            const mitmspt     = $("#itm_txtspt").val();
            const mitmtyp     = $("#itm_cmbtype").val();
            const misdirect   = $("#itm_isdirect").val();
            const mhscode     = $("#itm_txt_hscode").val();
            const mhscodet    = $("#itm_txt_hscode10").val();
            const mnet    = $("#itm_txt_netwg").val();
            const mgrs    = $("#itm_txt_grosswg").val();
            let mcolor = document.getElementById('itm_txt_color').value;
            let mbox  = document.getElementById('itm_txtitmbox').value;
            let mspq  = document.getElementById('itm_txt_spq').value;
            let mheight  = document.getElementById('itm_txt_mxheight').value;
            let mlength  = document.getElementById('itm_txt_mxlength').value;
            let mshtqty = document.getElementById('itm_txt_shtqty').value;
            let mcategory = document.getElementById('itm_txt_category').value;
            let mitmcd_Ext = document.getElementById('itm_txt_externalcode').value;
            let mstkuom = document.getElementById('itm_cmb_um').value
            if(mitmcd.trim()==''){
                alertify.warning('Item code is required')
                $("#itm_txtitmcd").focus()
                return
            }
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTITM/set')?>",
                data: {initmcd: mitmcd, initmnm1: mitmnm1, initmnm2: mitmnm2, inspt: mitmspt, intype: mitmtyp,
                inhscode: mhscode, inhscodet: mhscodet,inisdirect : misdirect, innetwg: mnet, ingrswg: mgrs ,
                inbox: mbox, inspq: mspq, inheight: mheight, inlength: mlength, incolor: mcolor, inshtqty: mshtqty,
                    incategory: mcategory, mitmcd_Ext: mitmcd_Ext, mstkuom: mstkuom, initmcd_old: itm_old_itmcd
                },
                dataType: "JSON",
                success: function (response) {
                    alertify.message(response.status[0].msg)
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    function synch_all(pindx, pitem){
        $.ajax({
            type: "post",
            url: "<?=base_url('MSTITM/setsync')?>",
            data: {inx: pindx, initem: pitem},
            dataType: "json",
            success: function (response) {
                var table = $("#itm_tbldiff tbody");
                table.find('tr').eq(response[0].indx).find('td').eq(2).text(response[0].status);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function itm_e_update_d1(){
        $.ajax({
            type: "get",
            url: "<?=base_url('MSTITM/sync_item_description1')?>",            
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd==1){
                    alertify.success(response.status[0].msg);
                } else {
                    alertify.message(response.status[0].msg);
                }
            },error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#itm_btnsyncall").click(function(){
        var konfirm = confirm('Are you sure ?');
        var myin = 0;
        if(konfirm){
            var tables = $("#itm_tbldiff tbody");
            tables.find('tr').each(function (i) {
                var $tds = $(this).find('td'),
                    myitem = $tds.eq(1).text();
                synch_all(myin, myitem);
                myin++;
            });
        }
    });
    $("#itm_container").hide();
    $("#itm_btnclose").click(function(){
        $("#itm_container").hide();
    });
    $("#itm_btnsync").click(function(){
        $("#itm_container").show();
        $.ajax({
            type: "get",
            url: "<?=base_url('MSTITM/finddiff')?>",            
            dataType: "json",
            success: function (response) {
                var ttlrows = response.length;
                var tohtml = '';
                if(ttlrows>0){
                    $("#itm_btnsyncall").show();
                    for(var i =0;i<ttlrows;i++){
                        tohtml += '<tr>'+
                        '<td></td>'+
                        '<td>'+response[i].MITM_ITMCD.trim()+'</td>'+
                        '<td></td>'+
                        '</tr>';
                    }
                } else {
                    $("#itm_btnsyncall").hide();
                    tohtml += '<tr>'+
                        '<td colspan="3" class="text-center">No difference</td>'+
                        '</tr>';
                }                
                $("#itm_tbldiff tbody").html(tohtml);
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#itm_btnnew").click(function(){
        $("#itm_txtitmcd").val('');
        $("#itm_txtitmnm1").val('');
        $("#itm_txtitmnm2").val('');
        $("#itm_txtspt").val('');$("#itm_txtitmcd").focus();
        $("#itm_container").hide();
        $("#itm_txtitmcd").prop('readonly', false);
    });
    $("#itm_srchby").change(function(){
        $("#itm_txtsearch").focus();
    });
    $("#itm_btnformoditem").click(function(){
        $("#ITM_MODITM").modal('show');$("#itm_container").hide();
    });

    function itm_getdetail(pdata) {
        itm_old_itmcd = pdata.MITM_ITMCD
        $("#itm_isdirect").val(pdata.MITM_INDIRMT)
        $("#itm_txtitmcd").prop('readonly', (pdata.MITM_MODEL=='6' ? false : true))
        $("#itm_txtitmcd").val(pdata.MITM_ITMCD)
        $("#itm_txt_externalcode").val(pdata.MITM_ITMCDCUS)
        $("#itm_txtitmnm1").val(pdata.MITM_ITMD1)
        $("#itm_txtitmnm2").val(pdata.MITM_ITMD2)
        $("#itm_txtspt").val(pdata.MITM_SPTNO)
        $("#itm_txt_hscode").val(pdata.MITM_HSCD)
        $("#itm_txt_hscode10").val(pdata.MITM_HSCODET)
        document.getElementById('itm_txt_netwg').value=pdata.MITM_NWG
        document.getElementById('itm_txt_grosswg').value=pdata.MITM_GWG        
        $("#itm_cmbtype").val(pdata.MITM_MODEL);
        document.getElementById('itm_txtitmbox').value=pdata.MITM_BOXTYPE
        document.getElementById('itm_txt_spq').value=pdata.MITM_SPQ
        document.getElementById('itm_txt_mxheight').value=pdata.MITM_MXHEIGHT
        document.getElementById('itm_txt_mxlength').value=pdata.MITM_MXLENGTH
        document.getElementById('itm_txt_color').value=pdata.MITM_LBLCLR
        document.getElementById('itm_txt_shtqty').value=pdata.MITM_SHTQTY
        document.getElementById('itm_txt_category').value=pdata.MITM_NCAT
        document.getElementById('itm_cmb_um').value=pdata.MITM_STKUOM

        $("#ITM_MODITM").modal('hide');
        document.getElementById('itm_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
    }
    
    $('#itm_tblbox tbody').on( 'click', 'tr', function () { 
		if ( $(this).hasClass('table-active') ) {
			$(this).removeClass('table-active');
        } else {
			$('#itm_tblbox tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        let mbox       = $(this).closest("tr").find('td:eq(0)').text();
        document.getElementById('itm_txtitmbox').value=mbox;
        $("#ITM_MODBOX").modal('hide');
    });
    $("#ITM_MODITM").on('shown.bs.modal', function(){
        $("#itm_txtsearch").focus();
    });
    $("#ITM_MODBOX").on('shown.bs.modal', function(){
        $("#itm_txtsearchbox").focus();
    });

    function itm_txtsearch_eKP(e){
        if (e.key=='Enter'){
            const mkey = document.getElementById('itm_txtsearch').value
            const msearchby = $("#itm_srchby").val();
            $("#lblinfo_tblitm").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTITM/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    const ttlrows = response.length                    
                    let mydes = document.getElementById("itm_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("itm_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                
                    let tabell = myfrag.getElementById("itm_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';                    
                    let misdirect = '';
                    let mnetwg , mgrswg;
                    for(let i=0;i<ttlrows;i++){                       
                        if(response[i].MITM_INDIRMT){
                            if(response[i].MITM_INDIRMT.trim()=='Y'){
                                misdirect = 'Yes';
                            } else {
                                misdirect = 'No';
                            }
                        } else {
                            misdirect = '-';
                        }
                        mnetwg = response[i].MITM_NWG;
                        if(mnetwg.charAt(0)=='.'){
                            mnetwg = "0"+mnetwg;
                        }
                        mgrswg = response[i].MITM_GWG;
                        if(mgrswg.charAt(0)=='.'){
                            mgrswg = "0"+mgrswg;
                        }
                        newrow = tableku2.insertRow(-1)                        
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = 'white-space:nowrap;cursor:pointer'
                        
                        newcell.onclick = function(){                            
                            itm_getdetail({
                                MITM_ITMCD : response[i].MITM_ITMCD
                                ,MITM_ITMD1 : response[i].MITM_ITMD1
                                ,MITM_ITMD2 : response[i].MITM_ITMD2
                                ,MITM_SPTNO : response[i].MITM_SPTNO
                                ,MITM_MODEL : response[i].MITM_MODEL
                                ,MITM_INDIRMT : response[i].MITM_INDIRMT
                                ,MITM_HSCD : response[i].MITM_HSCD
                                ,MITM_HSCODET : response[i].MITM_HSCODET
                                ,MITM_NWG : response[i].MITM_NWG
                                ,MITM_GWG : response[i].MITM_GWG
                                ,MITM_BOXTYPE : response[i].MITM_BOXTYPE
                                ,MITM_SPQ : response[i].MITM_SPQ
                                ,MITM_MXHEIGHT : response[i].MITM_MXHEIGHT
                                ,MITM_MXLENGTH : response[i].MITM_MXLENGTH
                                ,MITM_LBLCLR : response[i].MITM_LBLCLR
                                ,MITM_SHTQTY : response[i].MITM_SHTQTY
                                ,MITM_NCAT : response[i].MITM_NCAT
                                ,MITM_ITMCDCUS : response[i].MITM_ITMCDCUS
                                ,MITM_STKUOM : response[i].MITM_STKUOM
                            })
                        }
                        newcell.innerHTML = response[i].MITM_ITMCD.trim()
                        newcell = newrow.insertCell(1)
                        newcell.style.cssText = 'white-space:nowrap'
                        newcell.innerHTML = response[i].MITM_ITMD1
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response[i].MITM_ITMD2
                        newcell = newrow.insertCell(3)
                        newcell.style.cssText = 'white-space:nowrap'
                        newcell.innerHTML = response[i].MITM_SPTNO
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response[i].MMDL_NM
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response[i].MITM_INDIRMT
                        newcell = newrow.insertCell(6)
                        newcell.innerHTML = misdirect
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = response[i].MITM_HSCD
                        newcell = newrow.insertCell(8)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response[i].MITM_HSCODET
                        newcell = newrow.insertCell(9)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = mnetwg
                        newcell = newrow.insertCell(10)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = mgrswg
                        newcell = newrow.insertCell(11)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response[i].MITM_BOXTYPE
                        newcell = newrow.insertCell(12)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response[i].MITM_SPQ).format('0,0')
                        newcell = newrow.insertCell(13)
                        newcell.classList.add('text-end', 'd-none')
                        newcell.innerHTML = numeral(response[i].MITM_MXHEIGHT).format('0,0')
                        newcell = newrow.insertCell(14)
                        newcell.classList.add('text-end','d-none')
                        newcell.innerHTML = numeral(response[i].MITM_MXLENGTH).format('0,0')
                        newcell = newrow.insertCell(15)                        
                        newcell.innerHTML = response[i].MITM_LBLCLR
                        newcell = newrow.insertCell(16)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response[i].MITM_SHTQTY).format('0,0')
                        newcell = newrow.insertCell(17)
                        newcell.innerHTML = response[i].MITM_NCAT
                        newcell = newrow.insertCell(18)
                        newcell.innerHTML = response[i].MITM_ITMCDCUS
                        newcell = newrow.insertCell(19)
                        newcell.title = 'Unit of Measurement'
                        newcell.innerHTML = response[i].MITM_STKUOM
                    }
                    $("#lblinfo_tblitm").text("");
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }
            });
        }
    }   
    $("#itm_btn_import").click(function (e) { 
        e.preventDefault();
        $("#ITM_IMPORTDATA").modal('show');
    });
    $("#itm_btn_download").click(function (e) { 
        e.preventDefault();
        $.ajax({
            type: "get",
            url: "<?=base_url('MSTITM/getlinkitemtemplate')?>",
            dataType: "text",                       
            success:function(response) {          
                window.open(response, '_blank');
                alertify.message("<i>Start downloading...</i>");
            },
            error:function(xhr,ajaxOptions, throwError) {
                alert(throwError);
            }
        });
    });
    $("#itm_btn_startimport").click(function (e) { 
        e.preventDefault();
        if (document.getElementById('itm_xlf_new').files.length==0){
            alert('please select file to upload');
        } else {	
            document.getElementById('itm_btn_startimport').disabled=true;
            var fileUpload = $("#itm_xlf_new")[0]; 
            //Validate whether File is valid Excel file.
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {                
                    if (typeof (FileReader) != "undefined") {
                        var reader = new FileReader();
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) {
                            console.log('saya perambaan selain IE');
                            reader.onload = function (e) {
                                itm_ProcessExcel(e.target.result);
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
                                itm_ProcessExcel(data);
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

    function itm_ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];			
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
		

		
        var tohtml = '';
        //Add the data rows from Excel file.
        itm_ttlxls =excelRows.length;       
        itm_ttlxls_savd=0;
        for (let i = 0; i < excelRows.length; i++) {
            let mitem      = excelRows[i].ITMID;
            let mitemnm    = excelRows[i].ITMNM;
            let msprtno    = excelRows[i].ITMSPRNO;
            let mum        = excelRows[i].UM;
            let misdirect  = excelRows[i].MITM_INDIRMT;
            let mhscode    = excelRows[i].HSCD;
            let mhscodet   = excelRows[i].HSCODET;
            let mmodel     = excelRows[i].MITM_MODEL;
            let mnet     = excelRows[i].MITM_NETWG;
            let mgrs    = excelRows[i].MITM_GROSSWG;
            let mboxtype   = excelRows[i].MITM_BOXTYPE;
            let mspq   = excelRows[i].MITM_SPQ;
            let mmxheight   = excelRows[i].MITM_MXHEIGHT;
            let mmxlength   = excelRows[i].MITM_MXLENGTH;
            let mshtqty   = excelRows[i].MITM_SHTQTY;
            let mlblclr   = excelRows[i].MITM_LBLCLR;
            
            
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTITM/import')?>",
                data: {initem: mitem, innm: mitemnm, insprtno: msprtno, inum: mum, inisdirect: misdirect, inhscd: mhscode, inhscdt: mhscodet,
                inmodel: mmodel, innet: mnet, ingrs: mgrs ,inboxtype: mboxtype, inspq: mspq, inmxheight: mmxheight, inmxlength: mmxlength,
                inshtqty : mshtqty,incolor:mlblclr,inrowid: i},
                dataType: "json",
                success: function (response) {
                    itm_ttlxls_savd++;
                    let dis = parseInt(((itm_ttlxls_savd)/itm_ttlxls)*100) + "%";
                    document.getElementById('itm_lblsaveprogress').style.width = dis;
                    document.getElementById('itm_lblsaveprogress').innerText = dis;                                       
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
            
        }         
	};
    $("#itm_btnformodbox").click(function (e) { 
        $("#ITM_MODBOX").modal('show');        
    });
    $("#itm_txtsearchbox").keypress(function (e) { 
        if(e.which==13){
            let mkeys = document.getElementById('itm_txtsearchbox').value;
            $("#itm_tblbox tbody").empty();
            document.getElementById('lblinfo_tblbox').innerHTML = 'Please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTITM/getboxlist')?>",
                data: {inkeys: mkeys},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let todis = '';
                    if(ttlrows>0){
                        for(let i=0;i<ttlrows;i++){
                            todis += '<tr>'+
                            '<td>'+response[i].MITM_BOXTYPE+'</td>'+
                            '</tr>';
                        }
                        document.getElementById('lblinfo_tblbox').innerHTML = ttlrows + ' row(s) found';
                    } else {
                        todis = '<tr>'+
                            '<td>no history found</td>'+
                            '</tr>';
                        document.getElementById('lblinfo_tblbox').innerHTML = '';
                    }
                    $("#itm_tblbox tbody").html(todis);
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#itm_divku").css('height', $(window).height()*50/100);

    function itm_btnShow_modalCategory_eCK(){
        $("#ITM_MODCATEGORY").modal('show')
    }
    var itmdata = [
       [''],
       [''],      
    ];
    var itm_bu_ss_category = jspreadsheet(document.getElementById('itm_bu_ss_category'), {
        data:itmdata,
        columns: [
            {
                type: 'text',
                title:'Item Code',
                width:200,            
            },
            {
                type: 'text',
                title:'Category',
                width:200,            
            } 
        ]   
    });

    function itm_bu_btn_save_eCK(){
        let datanya_nonitem = itm_bu_ss_category.getData()
        let ttlrows = datanya_nonitem.length
        for(let i=0; i<ttlrows; i++){
            $.ajax({
                type: "POST",
                url: "<?=base_url('MSTITM/updatencat')?>",
                data: {i: i, item_code :datanya_nonitem[i][0], category: datanya_nonitem[i][1]},
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd===1){
                        itm_bu_ss_category.setStyle('A'+(response.status[0].reff+1), 'background-color', '#91ffb4')
                        itm_bu_ss_category.setStyle('B'+(response.status[0].reff+1), 'background-color', '#91ffb4')
                    }
                }, error: function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            })            
        }
    }
</script>