<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Code</label>                    
                    <input type="text" class="form-control" id="sup_txtsupcd">                    
                    <button title="Find Supplier" class="btn btn-outline-secondary" type="button" id="sup_btnformodsup"><i class="fas fa-search"></i></button>                                            
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Currency</label>                    
                    <input type="text" class="form-control" id="sup_txtcur" maxlength="4">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Name</label>                    
                    <input type="text" class="form-control" id="sup_txtnm">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Abbr Name</label>                    
                    <input type="text" class="form-control" id="sup_txtabbrnm">
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-12 mb-1">
                <div class="input-group">                    
                    <span class="input-group-text">Address</span>                    
                    <textarea class="form-control" id="sup_taaddr" aria-label="Address"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Phone</label>
                    <input type="text" class="form-control" id="sup_txtphone">
                </div>                
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Fax</label>
                    <input type="text" class="form-control" id="sup_txtfax">
                </div>
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-3 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="sup_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="sup_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>                                
            </div>
        </div>        
    </div>
    
</div>
<div class="modal fade" id="SUP_MODSUP">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Supplier List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>                        
                        <input type="text" class="form-control" id="sup_txtsearch" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="sup_srchby" class="form-select">
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
                    <span class="badge bg-info" id="lblinfo_tblsup"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="sup_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Supplier Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>
                                    <th>Abbr Name</th>
                                    <th>Address</th>
                                    <th class="d-none">Phone</th>
                                    <th class="d-none">Fax</th>
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
    $("#sup_btnnew").click(function(){
        $("#sup_txtsupcd").val('');
        $("#sup_txtcur").val('');
        $("#sup_txtnm").val('');
        $("#sup_txtabbrnm").val('');
        $("#sup_taaddr").val('');        
        $("#sup_txtsupcd").prop('readonly', false);
        document.getElementById('sup_txtphone').value = ''
        document.getElementById('sup_txtfax').value = ''
        $("#sup_txtsupcd").focus()
    });
    $('#sup_tbl tbody').on( 'click', 'tr', function () { 
		if ( $(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#sup_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        let mcd     = $(this).closest("tr").find('td:eq(0)').text();
        let mcur    = $(this).closest("tr").find('td:eq(1)').text();
        let mnm     = $(this).closest("tr").find('td:eq(2)').text();
        let mabbr   = $(this).closest("tr").find('td:eq(3)').text();
        let maddr   = $(this).closest("tr").find('td:eq(4)').text();
        let phone   = $(this).closest("tr").find('td:eq(5)').text();
        let fax   = $(this).closest("tr").find('td:eq(6)').text();
        $("#sup_txtsupcd").val(mcd);
        $("#sup_txtcur").val(mcur);
        $("#sup_txtnm").val(mnm);
        $("#sup_txtabbrnm").val(mabbr);
        $("#sup_taaddr").val(maddr);
        document.getElementById('sup_txtphone').value = phone
        document.getElementById('sup_txtfax').value = fax
        $("#SUP_MODSUP").modal('hide');
        $("#sup_txtsupcd").prop('readonly', true);
    });
    $("#sup_btnformodsup").click(function(){
        $("#SUP_MODSUP").modal('show');
    });
    $("#SUP_MODSUP").on('shown.bs.modal', function(){
        $("#sup_txtsearch").focus();
    });
    $("#sup_srchby").change(function(){
        $("#sup_txtsearch").focus();
    });
    $("#sup_txtsearch").keypress(function (e) { 
        if (e.which==13){
            var mkey = $(this).val();
            var msearchby = $("#sup_srchby").val();
            $("#lblinfo_tblsup").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTSUP/search')?>",
                data: {cid : mkey, csrchby: msearchby},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    var tohtml ='';
                    for(var i=0;i<ttlrows;i++){
                        
                        tohtml += '<tr style="cursor:pointer">'+
                        '<td style="white-space:nowrap">'+response[i].MSUP_SUPCD.trim()+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MSUP_SUPCR+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MSUP_SUPNM+'</td>'+
                        '<td style="white-space:nowrap">'+response[i].MSUP_ABBRV+'</td>'+
                        '<td>'+response[i].MSUP_ADDR1+'</td>'+
                        '<td class="d-none">'+response[i].MSUP_TELNO+'</td>'+
                        '<td class="d-none">'+response[i].MSUP_FAXNO+'</td>'+                        
                        '</tr>';
                    }
                    $("#lblinfo_tblsup").text("");
                    $('#sup_tbl tbody').html(tohtml);
                }
            });
        }
    });
    $("#sup_btnsave").click(function(){        
        if(confirm("Are you sure ?")){
            let mcd     = $("#sup_txtsupcd").val();
            let mcur    = $("#sup_txtcur").val();
            let mnm     = $("#sup_txtnm").val();
            let mabbr   = $("#sup_txtabbrnm").val();
            let maddr   = $("#sup_taaddr").val();
            let mphone   = $("#sup_txtphone").val();
            let mfax   = $("#sup_txtfax").val();
            if(mcd.trim()==''){$("#sup_txtsupcd").focus() ;return;}
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTSUP/set')?>",
                data: {incd: mcd, incur: mcur, innm: mnm, inabbr: mabbr, inaddr: maddr,mphone: mphone, mfax: mfax },
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }
    });
</script>