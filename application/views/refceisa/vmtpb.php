<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Code</label>
                    <input type="text" class="form-control" id="tpbtype_txtid">
                    <button title="Find item" class="btn btn-outline-secondary" type="button" id="tpbtype_btnfortpb"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-9 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Description</label>
                    <input type="text" class="form-control" id="tpbtype_txtnm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="tpbtype_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="tpbtype_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                    <button title="Synchronize" id="tpbtype_btnsync" class="btn btn-success" ><i class="fas fa-sync"></i> Synchronize</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="table-responsive" id="tpbtype_container">
                    <table id="tpbtype_tbldiff" class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" colspan="2">Type List</th>
                                <th class="text-center" rowspan="2">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-success" id="tpbtype_btnsyncall" title="Synchronize all"><i class="fas fa-sync"></i></button>
                                        <button class="btn btn-secondary" id="tpbtype_btnclose" title="Close table"><i class="fas fa-window-close"></i></button>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th class="d-none">aa</th>
                                <th class="text-center">WMS</th>
                                <th class="text-center">CEISA</th>
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
<div class="modal fade" id="TYPE_MODTPB">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Type List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="tpbtype_srchby" class="form-select">
                            <option value="ic">Type</option>
                            <option value="in">Description</option>
                        </select>
                        <input type="text" class="form-control" id="tpbtype_txtsearch" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="tpbtype_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
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
    $("#tpbtype_container").hide();
    $("#tpbtype_btnsync").click(function(){
        $("#tpbtype_container").show();
        $.ajax({
            type: "get",
            url: "<?=base_url('refceisa/MTPB/finddiff')?>",
            dataType: "json",
            success: function (response) {
                var ttlrows = response.length;
                var tohtml = '';
                if(ttlrows>0){
                    $("#tpbtype_btnsyncall").show();
                    for(var i =0;i<ttlrows;i++){
                        tohtml += '<tr>'+
                        '<td class="d-none">'+response[i].ID.trim()+'</td>'+
                        '<td>?</td>'+
                        '<td>'+response[i].DESC.trim()+'</td>'+
                        '<td></td>'+
                        '</tr>';
                    }
                } else {
                    $("#tpbtype_btnsyncall").hide();
                    tohtml += '<tr>'+
                        '<td colspan="4" class="text-center">No difference</td>'+
                        '</tr>';
                }
                $("#tpbtype_tbldiff tbody").html(tohtml);
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#tpbtype_btnclose").click(function(){
        $("#tpbtype_container").hide();
    });
    $("#tpbtype_btnsyncall").click(function(){
        var konfirm = confirm('Are you sure ?');
        var myin = 0;
        if(konfirm){
            var tables = $("#tpbtype_tbldiff tbody");
            tables.find('tr').each(function (i) {
                var $tds = $(this).find('td'),
                    myitem = $tds.eq(0).text(),
                    mydesc = $tds.eq(2).text();
                synch_alltpb(myin, myitem, mydesc);
                myin++;
            });
        }
    });
    function synch_alltpb(pindx, pitem, pdesc){
        $.ajax({
            type: "post",
            url: "<?=base_url('refceisa/MTPB/setsync')?>",
            data: {inx: pindx, initem: pitem, indesc: pdesc},
            dataType: "json",
            success: function (response) {
                var table = $("#tpbtype_tbldiff tbody");
                table.find('tr').eq(response[0].indx).find('td').eq(3).text(response[0].status);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#tpbtype_btnfortpb").click(function(){
        $("#TYPE_MODTPB").modal('show');
    });
    $("#TYPE_MODTPB").on('shown.bs.modal', function(){
        $('#tpbtype_tbl tbody').html('');
        $("#tpbtype_txtsearch").focus();
    });
    $("#tpbtype_srchby").change(function(){
        $("#tpbtype_txtsearch").focus();
    });
    $("#tpbtype_txtsearch").keypress(function (e) {
        if (e.which==13){
            var mkey = $(this).val();
            var msearchby = $("#tpbtype_srchby").val();
            $('#tpbtype_tbl tbody').html('');
            $.ajax({
                type: "get",
                url: "<?=base_url('refceisa/MTPB/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    var tohtmlr ='';
                    for(var i=0;i<ttlrows;i++){
                        tohtmlr += '<tr style="cursor:pointer">'+
                        '<td style="white-space:nowrap">'+response[i].KODE_JENIS_TPB.trim()+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].URAIAN_JENIS_TPB+'</td>'+
                        '</tr>';
                    }
                    $('#tpbtype_tbl tbody').html(tohtmlr);
                }
            });
        }
    });
    $('#tpbtype_tbl tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('table-active') ) {
			$(this).removeClass('table-active');
        } else {
			$('#tpbtype_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        var mitem       = $(this).closest("tr").find('td:eq(0)').text();
        var mitemnm     = $(this).closest("tr").find('td:eq(1)').text();

        $("#tpbtype_txtid").val(mitem);
        $("#tpbtype_txtnm").val(mitemnm);
        $("#TYPE_MODTPB").modal('hide');
    });
    $("#tpbtype_btnnew").click(function(){
        $("#tpbtype_txtid").val('');
        $("#tpbtype_txtnm").val('');
        $("#tpbtype_txtid").focus();
    });
    $("#tpbtype_btnsave").click(function(){
        var konf = confirm("Are you sure ?");
        if(konf){
            var mitmcd      = $("#tpbtype_txtid").val();
            var mitmnm1     = $("#tpbtype_txtnm").val();
            if(mitmcd.trim()==''){
                $("#tpbtype_txtid").focus(); return;
            }
            $.ajax({
                type: "post",
                url: "<?=base_url('refceisa/MTPB/set')?>",
                data: {initmcd: mitmcd, initmnm1: mitmnm1},
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