<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Document Code</label>
                    <input type="text" class="form-control" id="prpsdelv_txtcd">
                    <button title="Find item" class="btn btn-outline-secondary" type="button" id="prpsdelv_btnformod"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Purpose Code</label>
                    <input type="text" class="form-control" id="prpsdelv_seltype">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Description</label>
                    <input type="text" class="form-control" id="prpsdelv_txtdcr">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="prpsdelv_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="prpsdelv_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                    <button title="Synchronize" id="prpsdelv_btnsync" class="btn btn-success" ><i class="fas fa-sync"></i> Synchronize</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="table-responsive" id="prpsdelv_container">
                    <table id="prpsdelv_tbldiff" class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" colspan="3">WMS</th>
                                <th class="text-center" colspan="3">CEISA</th>
                                <th class="text-center" ></th>
                            </tr>
                            <tr>
                                <th class="text-center" >Document Code</th>
                                <th class="text-center" >Purpose Code</th>
                                <th class="text-center" >Description</th>
                                <th class="text-center" >Document Code</th>
                                <th class="text-center" >Purpose Code</th>
                                <th class="text-center" >Description</th>
                                <th class="text-center" >
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-success" id="prpsdelv_btnsyncall" title="Synchronize all"><i class="fas fa-sync"></i></button>
                                        <button class="btn btn-secondary" id="prpsdelv_btnclose" title="Close table"><i class="fas fa-window-close"></i></button>
                                    </div>
                                </th>
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
<div class="modal fade" id="PRPSDLV_MOD">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search by</span>
                        <select id="prpsdelv_srchby" class="form-select">
                            <option value="cd">Code</option>
                            <option value="in">Description</option>
                        </select>
                        <input type="text" class="form-control" id="prpsdelv_txtsearch" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="prpsdelv_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Code</th>
                                    <th>Purpose Code</th>
                                    <th>Description</th>
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
    $("#prpsdelv_container").hide();
    $("#prpsdelv_btnsync").click(function(){
        $("#prpsdelv_container").show();
        $.ajax({
            type: "get",
            url: "<?=base_url('refceisa/MPurposeDLV/finddiff')?>",
            dataType: "json",
            success: function (response) {
                var ttlrows = response.length;
                var tohtml = '';
                if(ttlrows>0){
                    $("#prpsdelv_btnsyncall").show();
                    for(var i =0;i<ttlrows;i++){
                        tohtml += '<tr>'+
                        '<td>?</td>'+
                        '<td>?</td>'+
                        '<td>?</td>'+
                        '<td>'+response[i].ID+'</td>'+
                        '<td>'+response[i].CD+'</td>'+
                        '<td>'+response[i].DESC+'</td>'+
                        '<td></td>'+
                        '</tr>';
                    }
                } else {
                    $("#prpsdelv_btnsyncall").hide();
                    tohtml += '<tr>'+
                        '<td colspan="7" class="text-center">No difference</td>'+
                        '</tr>';
                }
                $("#prpsdelv_tbldiff tbody").html(tohtml);
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#prpsdelv_btnclose").click(function(){
        $("#prpsdelv_container").hide();
    });
    $("#prpsdelv_btnsyncall").click(function(){
        var konfirm = confirm('Are you sure ?');
        var myin = 0;
        if(konfirm){
            var tables = $("#prpsdelv_tbldiff tbody");
            tables.find('tr').each(function (i) {
                var $tds = $(this).find('td'),
                    mydoccd = $tds.eq(3).text(),
                    myprpscd = $tds.eq(4).text(),
                    mydescr = $tds.eq(5).text();
                synch_alltpb(myin, mydoccd, myprpscd ,mydescr);
                myin++;
            });
        }
    });
    function synch_alltpb(pindx, pdoccd,pprpscd, pdesc){
        $.ajax({
            type: "post",
            url: "<?=base_url('refceisa/MPurposeDLV/setsync')?>",
            data: {inx: pindx, indoccd: pdoccd, inprpscd: pprpscd , indesc: pdesc},
            dataType: "json",
            success: function (response) {
                var table = $("#prpsdelv_tbldiff tbody");
                table.find('tr').eq(response[0].indx).find('td').eq(6).text(response[0].status);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#prpsdelv_btnformod").click(function(){
        $("#PRPSDLV_MOD").modal('show');
    });
    $("#PRPSDLV_MOD").on('shown.bs.modal', function(){
        $('#prpsdelv_tbl tbody').html('');
        $("#prpsdelv_txtsearch").focus();
    });
    $("#prpsdelv_srchby").change(function(){
        $("#prpsdelv_txtsearch").focus();
    });
    $("#prpsdelv_txtsearch").keypress(function (e) {
        if (e.which==13){
            var mkey = $(this).val();
            var msearchby = $("#prpsdelv_srchby").val();
            $('#prpsdelv_tbl tbody').html('');
            $.ajax({
                type: "get",
                url: "<?=base_url('refceisa/MPurposeDLV/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    var tohtmlr ='';
                    if(ttlrows>0){
                        for(var i=0;i<ttlrows;i++){
                            tohtmlr += '<tr style="cursor:pointer">'+
                            '<td style="white-space:nowrap">'+response[i].KODE_DOKUMEN+'</td>'+
                            '<td style="white-space:nowrap">'+response[i].KODE_TUJUAN_PENGIRIMAN+'</td>'+
                            '<td style="white-space:nowrap">'+response[i].URAIAN_TUJUAN_PENGIRIMAN+'</td>'+
                            '</tr>';
                        }
                    } else {
                        tohtmlr ='<tr><td colspan="3" class="text-center">Sorry, data not found...</td></tr>';
                    }
                    $('#prpsdelv_tbl tbody').html(tohtmlr);
                }
            });
        }
    });
    $('#prpsdelv_tbl tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('table-active') ) {
			$(this).removeClass('table-active');
        } else {
			$('#prpsdelv_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        var mcd       = $(this).closest("tr").find('td:eq(0)').text();
        var mtype     = $(this).closest("tr").find('td:eq(1)').text();
        var mdescrip    = $(this).closest("tr").find('td:eq(2)').text();

        $("#prpsdelv_txtcd").val(mcd);
        $("#prpsdelv_seltype").val(mtype);
        $("#prpsdelv_txtdcr").val(mdescrip);
        $("#PRPSDLV_MOD").modal('hide');
    });
    $("#prpsdelv_btnnew").click(function(){
        $("#prpsdelv_txtcd").val('');
        $("#prpsdelv_seltype").val('');
        $("#prpsdelv_txtdcr").val('');
        $("#prpsdelv_txtcd").focus();
    });
    $("#prpsdelv_btnsave").click(function(){
        var konf = confirm("Are you sure ?");
        if(konf){
            var mdoccode      = $("#prpsdelv_txtcd").val();
            var mpurposecode  = $("#prpsdelv_seltype").val();
            var mdcr        = $("#prpsdelv_txtdcr").val();
            if(mdoccode.trim()==''){
                $("#prpsdelv_txtid").focus(); return;
            }
            $.ajax({
                type: "post",
                url: "<?=base_url('refceisa/MPurposeDLV/set')?>",
                data: {initmcd: mdoccode, inprpscode: mpurposecode, indesc: mdcr},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
</script>