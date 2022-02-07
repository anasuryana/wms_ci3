<div style="padding: 10px" >
	<div class="container-fluid">        
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document</label>
                    <input type="text" class="form-control" id="rpt_mconadrf_txt_doc" readonly>
                    <button class="btn btn-primary" id="rpt_mconadrf_btnmod" onclick="rpt_mconadrf_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Contract Date</label>                    
                    <input type="text" class="form-control" id="rpt_mconadrf_txt_date" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Due Date</label>
                    <input type="text" class="form-control" id="rpt_mconadrf_txt_duedate" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="rpt_mconadrf_tbl_div">
                    <table id="rpt_mconadrf_tbl" class="table table-sm table-striped table-bordered table-hover caption-top" style="width:100%;font-size:75%">
                        <caption>DATA MATERIAL</caption>
                        <thead class="table-light">
                            <tr>
                                <th class="text-center align-middle">NO.</th>                                
                                <th class="text-center align-middle">KODE BARANG</th>
                                <th class="text-center align-middle">URAIAN JENIS BARANG</th>
                                <th class="text-center align-middle">JUMLAH</th>
                                <th class="text-center align-middle">SATUAN</th>
                            </tr>                            
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="rpt_mconadrf_tbl_fg_div">
                    <table id="rpt_mconadrf_tbl_fg" class="table table-sm table-striped table-bordered table-hover caption-top" style="width:100%;font-size:75%">
                        <caption>DATA FINISHED GOODS</caption>
                        <thead class="table-light">
                            <tr>
                                <th class="text-center align-middle">NO.</th>
                                <th class="text-center align-middle">HS CODE</th>
                                <th class="text-center align-middle">KODE BARANG</th>
                                <th class="text-center align-middle">URAIAN JENIS BARANG</th>
                                <th class="text-center align-middle">JUMLAH</th>
                                <th class="text-center align-middle">SATUAN</th>                                
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