<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Policy</th>
                                <th class="align-middle">Security Setting</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Account lockout duration</td>
                                <td class="pwpol_main" id="pwpol_main_duration" style="cursor:pointer" onclick="pwpol_e_show_duration_dialog(this)"></td>
                            </tr>
                            <tr>
                                <td>Account lockout threshold</td>
                                <td class="pwpol_main" id="pwpol_main_threshold" style="cursor:pointer" onclick="pwpol_e_show_threshold_dialog(this)"></td>
                            </tr>
                            <tr>
                                <td>Allow Administrator account lockout</td>
                                <td class="pwpol_main" id="pwpol_main_allow_admin" style="cursor:pointer" onclick="pwpol_e_show_allow_admin_dialog(this)"></td>
                            </tr>
                            <tr>
                                <td>Reset account lockout counter after</td>
                                <td class="pwpol_main" id="pwpol_main_reset" style="cursor:pointer" onclick="pwpol_main_e_show_reset_dialog(this)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pwpol_duration_mod">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Account lockout duration Properties</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Account is locked out for</span>
                        <input type="number" class="form-control" id="pwpol_txt_duration" maxlength="3" required placeholder="...">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="pwpol_btn_duration" onclick="pwpol_btn_duration_eClick(this)">OK</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="pwpol_max_age_mod">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Maximum password age Properties</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Password will expire in</span>
                        <input type="number" class="form-control" id="pwpol_txt_max_age" maxlength="4" required placeholder="...">
                        <span class="input-group-text" >days</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="pwpol_btn_max_age" onclick="pwpol_btn_max_age_eClick(this)">OK</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="pwpol_length_mod">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Manimum password length Properties</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Password must be at least</span>
                        <input type="number" class="form-control" id="pwpol_txt_length" maxlength="2" required placeholder="...">
                        <span class="input-group-text" >characters</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="pwpol_btn_length" onclick="pwpol_btn_length_eClick(this)">OK</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="pwpol_complexity_mod">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Password must meet complexity requirements Properties</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Password must be at least</span>
                        <input type="number" class="form-control" id="pwpol_txt_length" maxlength="2" required placeholder="...">
                        <span class="input-group-text" >characters</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="pwpol_btn_length" onclick="pwpol_btn_length_eClick(this)">OK</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<script>
    function pwpol_e_show_duration_dialog(){
        $("#pwpol_duration_mod").modal('show')
    }
</script>