<div style="padding: 10px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Category</label>                    
                    <input type="text" class="form-control" id="cntry_cd">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Name</label>                    
                    <input type="text" class="form-control" id="cntry_nm">
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col mb-3">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="cntry_btnnew"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" id="cntry_btnsave"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <table id="cntry_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer">
                    <thead class="table-light">
                        <tr>                                
                            <th>Code</th>
                            <th>Name</th>                            
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
    var tableCNTRY ; // = $('#rcvcustoms_tbl').DataTable({fixedHeader: true});
    var rcvcustoms_selcol='0';
    var rcvcustoms_seldo='';
    $("#cntry_btnsave").click(function(){
        var mcd = $("#cntry_cd").val();
        var mnm = $("#cntry_nm").val();
        if(mcd.trim()==''){
            $("#cntry_cd").focus();return;            
        }
        if(mnm.trim()==''){
            $("#cntry_nm").focus();return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('LBL/set')?>",
            data: {incd: mcd, innm: mnm},
            dataType: "text",
            success: function (response) {
                alertify.message(response);
                initCountryList();
            }, error : function(xhr,xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });    
    initCountryList();
    function initCountryList(){       
        tableCNTRY =  $('#cntry_tbl').DataTable({
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("LBL/getall")?>',
                type: 'get'  
            },
            columns:[
                { "data": 'MMADE_CD'},
                { "data": 'MMADE_NM'}                         
            ]        
        });             
    }
       
    $('#cntry_tbl tbody').on( 'click', 'tr', function () { 
		if ($(this).hasClass('table-active') ) {			
			$(this).removeClass('table-active');
        } else {                    			
			$('#cntry_tbl tbody tr.table-active').removeClass('table-active');
			$(this).addClass('table-active');
        }
        var mcd =  $(this).closest("tr").find('td:eq(0)').text();$("#cntry_cd").val(mcd);
        var mnm =  $(this).closest("tr").find('td:eq(1)').text();$("#cntry_nm").val(mnm);$("#cntry_cd").prop('readonly', true);
    });
    $("#cntry_btnnew").click(function(){
        $("#cntry_cd").val('');
        $("#cntry_nm").val('');
        $("#cntry_cd").focus();$("#cntry_cd").prop('readonly', false);
    });
</script>