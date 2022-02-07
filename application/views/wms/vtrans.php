<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Plate No</label>                    
                    <input type="text" class="form-control" id="trans_no" maxlength="15">                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <label class="input-group-text">Type</label>                    
                    <input type="text" class="form-control" id="trans_type" maxlength="40">
                </div>
            </div>            
        </div>       
        <div class="row">				
            <div class="col-md-12 mb-3 text-center">                
                <div class="btn-group btn-group-sm">
                    <button title="New" id="trans_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="trans_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div> 
        <div class="row">				
            <div class="col-md-12 mb-1">                
                <table id="trans_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer">
                    <thead class="table-light">
                        <tr>                                                   
                            <th>Plate No</th>
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
<script>
    var tableTRANS ; 
    initTRANSList();
    function initTRANSList(){        
        tableTRANS =  $('#trans_tbl').DataTable({
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("Trans/getall")?>',
                type: 'get'               
            },
            columns:[                
                { "data": 'MSTTRANS_ID'},
                { "data": 'MSTTRANS_TYPE'}                
            ],            
        });             
    }
    $("#trans_btnsave").click(function (e) { 
        e.preventDefault();
        var mid = $("#trans_no").val();
        var mtype = $("#trans_type").val();
        if(mid.trim()==''){
            $("#trans_no").focus();
            return;
        }
        if(mtype.trim()==''){
            $("#trans_type").focus();
            return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('Trans/set')?>",
            data: {inid: mid, intype: mtype},
            dataType: "text",
            success: function (response) {
                alertify.message(response);
                $("#trans_no").val('');
                $("#trans_type").val('');
                $("#trans_no").focus();initTRANSList();
            }, error: function(xhr, xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#trans_btnnew").click(function (e) { 
        e.preventDefault();
        $("#trans_no").val('');
        $("#trans_type").val('');
        $("#trans_no").focus();
    });
    $('#trans_tbl tbody').on( 'click', 'tr', function () { 
		if ($(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#trans_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        var mid =  $(this).closest("tr").find('td:eq(0)').text();
        var mtype =  $(this).closest("tr").find('td:eq(1)').text();
        $("#trans_no").val(mid);
        $("#trans_type").val(mtype);
                                      
    });
</script>