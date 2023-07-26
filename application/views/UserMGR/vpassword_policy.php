<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Policy</th>
                                <th class="align-middle">Policy Setting</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Enforce password history</td>
                                <td class="pwpol_main" id="pwpol_main_history" style="cursor:pointer" onclick="pwpol_e_show_history_dialog(this)"></td>
                            </tr>
                            <tr>
                                <td>Maximum password age</td>
                                <td class="pwpol_main" id="pwpol_main_max_age" style="cursor:pointer" onclick="pwpol_e_show_max_age_dialog(this)"></td>
                            </tr>
                            <tr>
                                <td>Minimum password length</td>
                                <td class="pwpol_main" id="pwpol_main_min_length" style="cursor:pointer" onclick="pwpol_e_show_min_length_dialog(this)"></td>
                            </tr>
                            <tr>
                                <td>Password must meet complexity requirements</td>
                                <td class="pwpol_main" id="pwpol_main_complex" style="cursor:pointer" onclick="pwpol_main_complex_show_dialog(this)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pwpol_history_mod">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Enforce password history Properties</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Keep password history for</span>
                        <input type="number" class="form-control" id="pwpol_txt_history" maxlength="3" required placeholder="...">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col text-end">
                        <button type="button" class="btn btn-primary btn-sm" id="pwpol_btn_history" onclick="pwpol_btn_history_eClick(this)">OK</button>
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
    function pwpol_e_init_data()
    {
        const m_elements = document.getElementsByClassName('pwpol_main')
        for(let item of m_elements)
        {
            item.innerHTML = 'Please wait'
        }
        $.ajax({
            url: "<?=base_url('User/password_policy')?>",
            dataType: "json",
            success: function (response)
            {
                pwpol_main_history.innerHTML = `${response.data[0].PWPOL_HISTORY} passwords remembered`
                pwpol_txt_history.value = response.data[0].PWPOL_HISTORY
                pwpol_main_max_age.innerHTML = `${response.data[0].PWPOL_MAXAGE} days`
                pwpol_txt_max_age.value = response.data[0].PWPOL_MAXAGE
                pwpol_main_min_length.innerHTML = `${response.data[0].PWPOL_LENGTH} characters`
                pwpol_txt_length.value = response.data[0].PWPOL_LENGTH
                pwpol_main_complex.innerHTML = 'Disabled'
            }, error: function(xhr,xopt,xthrow)
            {
                for(let item of m_elements)
                {
                    item.innerHTML = 'Could not get data from the server'
                }
            }
        })
    }
    pwpol_e_init_data()
    function pwpol_e_show_history_dialog(elem)
    {
        if(!elem.innerText.includes('Please'))
        {
            $("#pwpol_history_mod").modal('show')
        } else {
            alertify.warning('Please wait')
        }
    }

    function pwpol_e_show_max_age_dialog(elem)
    {
        if(!elem.innerText.includes('Please'))
        {
            $("#pwpol_max_age_mod").modal('show')
        } else {
            alertify.warning('Please wait')
        }
    }

    function pwpol_btn_history_eClick(elem)
    {
        const mvalue = pwpol_txt_history.value
        if(isNaN(mvalue))
        {
            alertify.message('value should be numerical')
            return
        }
        elem.innerHTML = 'Please wait'
        $.ajax({
            type: "POST",
            url: "<?=base_url('User/set_password_policy')?>",
            data: {key : 'history', value: mvalue},
            dataType: "JSON",
            success: function (response) {
                elem.innerHTML = 'OK'
                pwpol_main_history.innerHTML = `${mvalue} passwords remembered`
                $("#pwpol_history_mod").modal('hide')
            }, error: function(xhr,xopt,xthrow)
            {
                elem.innerHTML = 'OK'
                $("#pwpol_history_mod").modal('hide')
            }
        });
    }
    function pwpol_btn_max_age_eClick(elem)
    {
        const mvalue = pwpol_txt_max_age.value
        if(isNaN(mvalue))
        {
            alertify.message('value should be numerical')
            return
        }
        elem.innerHTML = 'Please wait'
        $.ajax({
            type: "POST",
            url: "<?=base_url('User/set_password_policy')?>",
            data: {key : 'max_age', value: mvalue},
            dataType: "JSON",
            success: function (response) {
                elem.innerHTML = 'OK'
                pwpol_main_max_age.innerHTML = `${mvalue} days`
                $("#pwpol_max_age_mod").modal('hide')
            }, error: function(xhr,xopt,xthrow)
            {
                elem.innerHTML = 'OK'
                $("#pwpol_max_age_mod").modal('hide')
            }
        });
    }
    function pwpol_e_show_min_length_dialog(elem)
    {
        if(!elem.innerText.includes('Please'))
        {
            $("#pwpol_length_mod").modal('show')
        } else {
            alertify.warning('Please wait')
        }
    }

    function pwpol_btn_length_eClick(elem)
    {
        const mvalue = pwpol_txt_length.value
        if(isNaN(mvalue))
        {
            alertify.message('value should be numerical')
            return
        }
        elem.innerHTML = 'Please wait'
        $.ajax({
            type: "POST",
            url: "<?=base_url('User/set_password_policy')?>",
            data: {key : 'min_length', value: mvalue},
            dataType: "JSON",
            success: function (response) {
                elem.innerHTML = 'OK'
                pwpol_main_min_length.innerHTML = `${mvalue} characters`
                $("#pwpol_length_mod").modal('hide')
            }, error: function(xhr,xopt,xthrow)
            {
                elem.innerHTML = 'OK'
                $("#pwpol_length_mod").modal('hide')
            }
        });
    }

    function pwpol_main_complex_show_dialog(){

    }
</script>