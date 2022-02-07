<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">   
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Document No.</span>
                    <input type="text" class="form-control" id="retfg_inc_txt_docno" required readonly>
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    <button class="btn btn-outline-primary"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <input id="retfg_inc_cmb_bg" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("ITH/get_bs_group")?>', label: 'Business Group', editable: false
                    , onSelect: function(p1){                        
                        retfg_inc_e_getcustomer(p1.id);
                        retfg_inc_e_getfg(p1.id);
                        retfg_inc_e_getlocation(p1.id);
                    }">
            </div>
            <div class="col-md-4 mb-1">
                <input id="retfg_inc_cmb_customer" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("RCV/get_customer")?>', label: 'Customer', editable: false
                    , onSelect: function(p1){ 
                        retfg_inc_e_getfg(p1.id);
                        retfg_inc_e_getlocation(p1.id);
                    }">
            </div>
            <div class="col-md-4 mb-1">
                <input id="retfg_inc_cmb_loc" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("RCV/get_strlocation")?>', label: 'Plant', editable: false
                    ">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <input id="retfg_inc_cmb_wh" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("MSTLOC/get_return_fg_warehouse")?>', label: 'Warehouse', editable: false">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="card">
                <div class="card-header">
                    Item Detail
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <input id="retfg_inc_cmb_itemcode" class="easyui-combobox" name="dept" style="width:100%;" 
                                    data-options="valueField:'id',textField:'text',url:'<?=base_url("RCV/get_fg")?>', label: 'Item Code :', limitToList: true
                                    , onSelect: function(p1){                        
                                        document.getElementById('retfg_inc_itemname').value = p1.description;
                                        document.getElementById('retfg_inc_itemunit').value = p1.um;
                                        $('#retfg_inc_txt_qty').numberbox('textbox').focus(); 
                                    }">
                                    <input type="hidden" id="retfg_inc_itemname">
                                    <input type="hidden" id="retfg_inc_itemunit">
                            </div>
                            <div class="col-md-3 mb-1">                
                                <input class="easyui-numberbox" id="retfg_inc_txt_qty" value="0" data-options="label:'QTY',precision:0,groupSeparator:',',width:'100%'" style="text-align: right;">
                            </div>
                            <div class="col-md-3 mb-1">
                                <input class="easyui-numberbox" id="retfg_inc_txt_price" value="0" data-options="label:'Price /unit',precision:2,groupSeparator:',',width:'100%'" style="text-align: right;">
                            </div>
                            <div class="col-md-3 mb-1">                
                                <input class="easyui-textbox"  id="retfg_inc_txt_remark" label="Remark" style="width:100%;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-primary" id="retfg_inc_btn_add" onclick="retfg_inc_btn_add_e_click()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <div class="table-responsive" id="retfg_inc_divku">
                                    <table id="retfg_inc_tbl" class="table table-striped table-bordered table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>                                                 
                                                <th >Item Id</th>
                                                <th >Item Name</th>
                                                <th class="text-end">Qty</th>
                                                <th title="Unite Measurement">UM</th>
                                                <th class="text-end">Price</th>
                                                <th >Remark</th>
                                                
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
<div id="retfg_inc_context_menu" class="easyui-menu" style="width:120px;">         
    <div data-options="iconCls:'icon-cancel'" onclick="retfg_inc_e_cancelitem()">Cancel</div>     
</div>
<input type="hidden" id="retfg_inc_g_id">
<script>
    function retfg_inc_btn_add_e_click(){
        let itemcode = $('#retfg_inc_cmb_itemcode').combobox('getValue');
        let qty = $('#retfg_inc_txt_qty').numberbox('getValue');
        let price = $('#retfg_inc_txt_price').numberbox('getValue');
        let remark = $('#retfg_inc_txt_remark').textbox('getValue');
        let itemname = document.getElementById('retfg_inc_itemname').value;
        let itemunit = document.getElementById('retfg_inc_itemunit').value;
        if(itemcode==""){
            alertify.message('Item Code could not be empty');
            $('#retfg_inc_cmb_itemcode').combobox('textbox').focus()
            return;
        }
        if(qty<=0){
            alertify.message('qty could not be zero');
            $('#retfg_inc_txt_qty').numberbox('textbox').focus()
            return;
        }
        if(price<=0){
            alertify.message('price could not be zero');
            $('#retfg_inc_txt_price').numberbox('textbox').focus()
            return;
        }
        let mtbl = document.getElementById('retfg_inc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = mtbl.getElementsByTagName('tr');
        let newrow, newcell, newText;

        newrow = tableku2.insertRow(-1);                                                                
        newcell = newrow.insertCell(0);
        newText = document.createTextNode(itemcode);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(1);
        newText = document.createTextNode(itemname);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(2);
        newcell.style.cssText = 'text-align: right;';
        newText = document.createTextNode(numeral(qty).format(','));
        newcell.appendChild(newText);
        newcell = newrow.insertCell(3);
        newText = document.createTextNode(itemunit);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(4);
        newcell.style.cssText = 'text-align: right;';
        newText = document.createTextNode(numeral(price).value('0,0.00'));
        newcell.appendChild(newText);
        newcell = newrow.insertCell(5);
        newText = document.createTextNode(remark);
        newcell.appendChild(newText);
        newcell.oncontextmenu = function(event){
                                event.preventDefault();                                
                                document.getElementById('retfg_inc_g_id').value = event.target.parentElement.rowIndex;
                                $('#retfg_inc_context_menu').menu('show', {
                                    left: event.pageX,
                                    top: event.pageY
                                });
                            };
    }

    function retfg_inc_e_cancelitem(){
        let mid = document.getElementById('retfg_inc_g_id').value;
        let thetable = document.getElementById('retfg_inc_tbl');
        let itemcode = thetable.rows[mid].cells[0].innerText;
        console.log(itemcode);
        thetable.deleteRow(mid);        
    }

    function retfg_inc_e_getlocation(pcust){
        let bg = $('#retfg_inc_cmb_bg').combobox('getValue');
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/get_strlocation')?>",
            data: {inbg: bg, incust: pcust },
            dataType: "json",
            success: function (response) {
                $('#retfg_inc_cmb_loc').combobox('loadData', response); 
                $('#retfg_inc_cmb_loc').combobox('clear'); 
                $('#retfg_inc_cmb_loc').combobox('textbox').focus(); 
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function retfg_inc_e_getcustomer(pbg){
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/get_customer')?>",
            data: {inbg: pbg },
            dataType: "json",
            success: function (response) {
                $('#retfg_inc_cmb_customer').combobox('loadData', response); 
                $('#retfg_inc_cmb_customer').combobox('clear'); 
                $('#retfg_inc_cmb_customer').combobox('textbox').focus(); 
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function retfg_inc_e_getfg(pcust){
        let bg = $('#retfg_inc_cmb_bg').combobox('getValue');
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/get_fg')?>",
            data: {inbg: bg, incust: pcust },
            dataType: "json",
            success: function (response) {
                $('#retfg_inc_cmb_itemcode').combobox('loadData', response); 
                $('#retfg_inc_cmb_itemcode').combobox('clear'); 
                $('#retfg_inc_cmb_itemcode').combobox('textbox').focus(); 
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
</script>