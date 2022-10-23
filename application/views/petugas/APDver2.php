<style>
/* Hide all steps by default: */
.tabx {
  display: none;
}
</style>
<!-- Extra Header -->
<div class="extraHeader p-0">
    <div class="form-wizard-section overflow-auto mt-1">
        <?
        $i = 1;
        foreach ($masterAPD as $key) {
            echo '
            <a href="#" class="button-item step">
                <strong>'.$i.'</strong>
                <p>'.$key['merk'].'</p>
            </a>
            ';
            $i++;
        }
        ?>
    </div>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active">

    <div class="section mb-2 mt-2 full">
        <div class="wide-block pt-2 pb-2">
            <form action="app-components.html">
                <div class="form-group boxed tabx">
                    <div class="input-wrapper">
                        <label class="label" for="apd">APD step 1</label>
                        <select class="form-control custom-select" id="apd" name="apd">
                            <option value="0">Pilih APD</option>
                            <option value="1">New York City</option>
                            <option value="2">Austin</option>
                            <option value="3">Colorado</option>
                        </select>
                    </div>
                </div>

                <div class="form-group boxed tabx">
                    <div class="input-wrapper">
                        <label class="label" for="apd">APD step 2</label>
                        <select class="form-control custom-select" id="apd" name="apd">
                            <option value="0">Pilih APD</option>
                            <option value="1">New York City</option>
                            <option value="2">Austin</option>
                            <option value="3">Colorado</option>
                        </select>
                    </div>
                </div>
            </form>

            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>