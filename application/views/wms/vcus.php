<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Code</label>                    
                    <input type="text" class="form-control" id="cus_txtcuscd">                    
                    <button title="Find Customer" class="btn btn-outline-secondary" type="button" id="cus_btnformodcus"><i class="fas fa-search"></i></button>                                            
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    
                    <label class="input-group-text">Currency</label>                    
                    <input type="text" class="form-control" id="cus_txtcur" maxlength="4">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    
                        <label class="input-group-text">Name</label>                    
                    <input type="text" class="form-control" id="cus_txtnm">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    
                    <label class="input-group-text">Abbr Name</label>                    
                    <input type="text" class="form-control" id="cus_txtabbrnm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    
                    <label class="input-group-text">TPB Type</label>                    
                    <select id="cus_typetpb" class="form-select">
                        <?php foreach($ltpb_type as $r) {?>
                            <option value="<?=$r['KODE_JENIS_TPB']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    
                        <label class="input-group-text">TPB No.</label>                    
                    <input type="text" class="form-control" id="cus_txttpbno">             
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    
                    <label class="input-group-text">NPWP</label>                    
                    <input type="text" class="form-control" id="cus_txtnpwp">             
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-12 mb-3">
                <div class="input-group">                    
                    <span class="input-group-text">Address</span>                    
                    <textarea class="form-control" id="cus_taaddr" aria-label="Address"></textarea>
                </div>
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-3 text-center">                
                <div class="btn-group btn-group-sm">
                    <button title="New" id="cus_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="cus_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-1">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Consignee</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 order-md-0">
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        
                                        <span class="input-group-text">Name</span>                                        
                                        <input type="text" class="form-control" id="cusconsig_txtname" required >                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button title="New" class="btn btn-primary" type="button" id="cusconsig_btnnew"><i class="fas fa-file"></i></button>
                                        <button title="Save" class="btn btn-primary" type="button" id="cusconsig_btnsave"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="table-responsive">
                                        <table id="cusconsig_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                            <thead class="table-light">
                                                <tr>                                                    
                                                    <th>Name</th>
                                                    <th>...</th>                                                    
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
    </div>
</div>
<div class="modal fade" id="CUS_MODCUS">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Customer List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="cus_txtsearch" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        
                            <span class="input-group-text" >Search by</span>                        
                        <select id="cus_srchby" class="form-select">
                            <option value="cd">Code</option>
                            <option value="nm">Name</option>                            
                            <option value="ab">Abbr Name</option>
                            <option value="ad">Address</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge bg-info" id="lblinfo_tblcus"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="cus_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>
                                    <th>Abbr Name</th>
                                    <th>Address</th>
                                    <th class="d-none">TPB Type ID</th>
                                    <th>TPB Type</th>
                                    <th>NPWP</th>
                                    <th>TPB No.</th>
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
    var scr_cus_oldnm = '';
    var cus_selcol = 0;
    $("#cus_btnnew").click(function(){
        $("#cus_txtcuscd").val('');
        $("#cus_txtcus").val('');
        $("#cus_txtnm").val('');
        $("#cus_txtabbrnm").val('');
        $("#cus_taaddr").val('');        
        $("#cus_txtcuscd").prop('readonly', false);
        $("#cus_txtcuscd").focus();
    });
    $('#cus_tbl tbody').on( 'click', 'tr', function () { 
		if ( $(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#cus_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }                
		let mcd     = $(this).closest("tr").find('td:eq(0)').text();
        let mcur    = $(this).closest("tr").find('td:eq(1)').text();
        let mnm     = $(this).closest("tr").find('td:eq(2)').text();
        let mabbr   = $(this).closest("tr").find('td:eq(3)').text();
        let maddr   = $(this).closest("tr").find('td:eq(4)').text();
        let mtpb_id   = $(this).closest("tr").find('td:eq(5)').text();
        let mnpwp   = $(this).closest("tr").find('td:eq(7)').text();
        let mtpb_no   = $(this).closest("tr").find('td:eq(8)').text();
        $("#cus_txtcuscd").val(mcd);
        $("#cus_txtcur").val(mcur);
        $("#cus_txtnm").val(mnm);
        $("#cus_txtabbrnm").val(mabbr);
        $("#cus_taaddr").val(maddr);
        document.getElementById('cus_typetpb').value=mtpb_id;
        document.getElementById("cus_txtnpwp").value=mnpwp;
        document.getElementById("cus_txttpbno").value=mtpb_no;
        $("#CUS_MODCUS").modal('hide');
        $("#cus_txtcuscd").prop('readonly', true);
        cus_getconsignee(mcd);
    });
    $("#cus_btnformodcus").click(function(){
        $("#CUS_MODCUS").modal('show');
    });
    $("#CUS_MODCUS").on('shown.bs.modal', function(){
        $("#cus_txtsearch").focus();
    });
    $("#cus_srchby").change(function(){
        $("#cus_txtsearch").focus();
    });
    $("#cus_txtsearch").keypress(function (e) { 
        if (e.which==13){
            var mkey = $(this).val();
            var msearchby = $("#cus_srchby").val();
            $("#lblinfo_tblcus").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTCUS/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    var tohtml ='';
                    for(var i=0;i<ttlrows;i++){
                        
                        tohtml += '<tr style="cursor:pointer">'+
                        '<td style="white-space:nowrap">'+response[i].MCUS_CUSCD.trim()+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MCUS_CURCD+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MCUS_CUSNM+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MCUS_ABBRV+'</td>'+
                        '<td>'+response[i].MCUS_ADDR1+'</td>'+
                        '<td class="d-none">'+response[i].MCUS_TPBTYPE+'</td>'+
                        '<td>'+response[i].URAIAN_JENIS_TPB+'</td>'+
                        '<td>'+response[i].MCUS_TAXREG+'</td>'+
                        '<td>'+response[i].MCUS_TPBNO+'</td>'+
                        '</tr>';
                    }
                    $("#lblinfo_tblcus").text("");
                    $('#cus_tbl tbody').html(tohtml);
                }
            });
        }
    });
    $("#cus_btnsave").click(function(){
        let konf = confirm("Are you sure ?");        
        if(konf){
            let mcd     = $("#cus_txtcuscd").val();
            let mcur    = $("#cus_txtcur").val();
            let mnm     = $("#cus_txtnm").val();
            let mabbr   = $("#cus_txtabbrnm").val();
            let maddr   = $("#cus_taaddr").val();
            let mtpb_id   = $("#cus_typetpb").val();
            let mtpbno = document.getElementById("cus_txttpbno").value;
            if(mcd.trim()==''){
                $("#cus_txtcuscd").focus();
                return;
            }
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTCUS/set')?>",
                data: {incd: mcd, incur: mcur, innm: mnm, inabbr: mabbr, inaddr: maddr, intpbid: mtpb_id,
                intpbno : mtpbno },
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    function cus_getconsignee(ppar){
        $("#cusconsig_tbl tbody").html('');
        $.ajax({
            type: "get",
            url: "<?=base_url('MCNSGN/getbyparent')?>",
            data: {inpar: ppar},
            dataType: "json",
            success: function (response) {
                var ttlr = response.length;
                var tohtml = '';
                for(let i=0;i<ttlr;i++){
                    tohtml += '<tr>'+
                    '<td>'+response[i].MCNSGN_NM+'</td>'+
                    '<td><i class="fas fa-trash text-warning"></i></td>'+
                    '</tr>';
                }
                $("#cusconsig_tbl tbody").html(tohtml);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    $("#cusconsig_btnsave").click(function (e) { 
        e.preventDefault();
        var ccd = $("#cus_txtcuscd").val();
        var mnm = $("#cusconsig_txtname").val();
        if (ccd.trim()==''){$("#cus_txtcuscd").focus();return;}
        $.ajax({
            type: "post",
            url: "<?=base_url('MCNSGN/set')?>",
            data: {incd: ccd, innm: mnm, innm_old: scr_cus_oldnm},
            dataType: "text",
            success: function (response) {
                alertify.message(response);
                cus_getconsignee($("#cus_txtcuscd").val());
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });

    $('#cusconsig_tbl tbody').on( 'click', 'tr', function () { 
		if ( $(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#cusconsig_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }                
		var mnm     = $(this).closest("tr").find('td:eq(0)').text();  
        scr_cus_oldnm = mnm;     
        $("#cusconsig_txtname").val(mnm);
        if(cus_selcol==1){
            var konf = confirm('Are you sure want to delete ?');
            if(konf){
                var cid = $("#cus_txtcuscd").val();
                $.ajax({
                    type: "get",
                    url: "<?=base_url('MCNSGN/remove')?>",
                    data: {inid:cid, innm: mnm },
                    dataType: "text",
                    success: function (response) {
                        alertify.message(response);cus_getconsignee($("#cus_txtcuscd").val());
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    });
    $('#cusconsig_tbl tbody').on( 'click', 'td', function () {            
        cus_selcol = $(this).index();         
    });
    $("#cusconsig_txtname").change(function () {
        if($(this).val()==''){
            scr_cus_oldnm = '';
        }
    });
    $("#cusconsig_btnnew").click(function (e) { 
        e.preventDefault();
        $("#cusconsig_txtname").val('');
        scr_cus_oldnm='';
        $("#cusconsig_txtname").focus();
    });
</script>