<link rel="stylesheet" type="text/css" href="<?=plugin_dir_url()?>dh_schedule/styles/main.css?v=0.08" />
<link rel="stylesheet" type="text/css" href="<?=plugin_dir_url()?>dh_schedule/styles/jquery.FlowupLabels.min.css" />
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?=plugin_dir_url()?>dh_schedule/scripts/jquery.FlowupLabels.min.js"></script>
<script type="text/javascript" src="<?=plugin_dir_url()?>dh_schedule/scripts/main.js?v=0.16"></script>
<div id="setting-header" class="section">
    <h1>Dancehub Schedule Settings</h1>
</div>
<form class="FlowupLabels">
    <div class="section fl_wrap">
        <label class="fl_label" for="public_key">API Public Key</label>
        <input class="fl_input" name="public_key" type="text" value="<?=get_option('public_key')?>" />
    </div>

    <div class="section fl_wrap">
        <label class="fl_label" for="secret_key">API Secret Key</label>
        <input class="fl_input" name="secret_key" type="text" value="<?=get_option('secret_key')?>" />
    </div>

    <div class="section fl_wrap">
        <label class="fl_label" for="endpoint">API End Point</label>
        <input name="endpoint" class="fl_input" type="text" value="<?=get_option('endpoint')?>" />
    </div>
</form>

<div class="section">
    <?php if(empty(get_option('auth_token'))) { ?>
        <button id="btnLoginWithDancehub">Login with Dancehub</button>
    <?php } else { ?>
        <button id="btnLoginWithDancehub">Logout</button>
    <?php } ?>
</div>

<div class="section">
    <button id="btnSaveSettings">Save Settings</button>
</div>

<input name="auth_token" type="hidden" value="<?=get_option('auth_token')?>" />