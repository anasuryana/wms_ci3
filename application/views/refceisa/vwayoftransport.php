<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Way Code</label>
                    <input type="text" class="form-control" id="wtrans_txtcd">
                    <button title="Find item" class="btn btn-outline-secondary" type="button" id="wtrans_btnformod"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-8 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Description</label>
                    <input type="text" class="form-control" id="wtrans_txtdcr">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="wtrans_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="wtrans_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="WTRNS_MOD">
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
                        <select id="wtrans_srchby" class="form-select">
                            <option value="cd">Code</option>
                            <option value="in">Description</option>
                        </select>
                        <input type="text" class="form-control" id="wtrans_txtsearch" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>           
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="wtrans_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Way Code</th>
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
    $("#wtrans_btnformod").click(function(){
        $("#WTRNS_MOD").modal('show');
    });
    $("#WTRNS_MOD").on('shown.bs.modal', function(){
        $('#wtrans_tbl tbody').html('');
        $("#wtrans_txtsearch").focus();
    });
    $("#wtrans_srchby").change(function(){
        $("#wtrans_txtsearch").focus();
    });
    $("#wtrans_txtsearch").keypress(function (e) {
        if (e.which==13){
            var mkey = $(this).val();
            var msearchby = $("#wtrans_srchby").val();
            $('#wtrans_tbl tbody').html('');
            $.ajax({
                type: "get",
                url: "<?=base_url('refceisa/ZWAYTRANS/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    var tohtmlr ='';
                    if(ttlrows>0){
                        for(var i=0;i<ttlrows;i++){
                            tohtmlr += '<tr style="cursor:pointer">'+
                            '<td style="white-space:nowrap">'+response[i].KODE_CARA_ANGKUT+'</td>'+
                            '<td style="white-space:nowrap">'+response[i].URAIAN_CARA_ANGKUT+'</td>'+
                            '</tr>';
                        }
                    } else {
                        tohtmlr ='<tr><td colspan="3" class="text-center">Sorry, data not found...</td></tr>';
                    }
                    $('#wtrans_tbl tbody').html(tohtmlr);
                }
            });
        }
    });
    $('#wtrans_tbl tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('table-active') ) {
			$(this).removeClass('table-active');
        } else {
			$('#wtrans_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        var mcd       = $(this).closest("tr").find('td:eq(0)').text();
        var mtype     = $(this).closest("tr").find('td:eq(1)').text();

        $("#wtrans_txtcd").val(mcd);
        $("#wtrans_txtdcr").val(mtype);
        $("#WTRNS_MOD").modal('hide');
    });
    $("#wtrans_btnnew").click(function(){
        $("#wtrans_txtcd").val('');
        $("#wtrans_txtdcr").val('');
        $("#wtrans_txtcd").focus();
    });
    $("#wtrans_btnsave").click(function(){
        var konf = confirm("Are you sure ?");
        if(konf){
            var mdoccode    = $("#wtrans_txtcd").val();
            var mdcr        = $("#wtrans_txtdcr").val();
            if(mdoccode.trim()==''){
                $("#wtrans_txtid").focus(); return;
            }
            $.ajax({
                type: "post",
                url: "<?=base_url('refceisa/ZWAYTRANS/set')?>",
                data: {initmcd: mdoccode, indesc: mdcr},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                    $( "#wtrans_btnnew" ).trigger( "click" );
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
</script>