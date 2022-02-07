<style>

thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }   
</style>
<div style="padding: 10px">
    <div class="container-fluid">                     
        <div class="row" id="discrep_stack1">            
            <div class="col-md-12 mb-1 text-center">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Assy Code</span>
                    <input type="text" class="form-control" id="rsointost_txt_assy">
                    <button class="btn btn-primary" type="button" id="rsointost_btn_gen" onclick="rsointost_e_btnrefresh()"><i class="fas fa-search"></i></button>
                </div>                
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rsointost_divku">
                    <table id="rsointost_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle">Assy Code</th>
                                <th rowspan="2" class="align-middle">Assy Name</th>
                                <th rowspan="2" class="align-middle">Document</th>
                                <th colspan="2" class="text-center">Qty</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center">Req.</th>
                                <th class="text-center">Ost.</th>
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