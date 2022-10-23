<!-- App Capsule -->
<div id="appCapsule">

<div class="error-page">
    <div class="icon-box text-danger">
        <ion-icon name="alert-circle"></ion-icon>
    </div>
    <h1 class="title">Error 403, Forbidden Access</h1>
    <div class="text mb-5">
        <? echo $message; ?>
    </div>

    <div class="fixed-footer">
        <div class="row text-center">
            <div class="col-12">
                <a href="<? echo base_url().$controller; ?>/home" class="btn btn-secondary btn-lg">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

</div>
<!-- * App Capsule -->