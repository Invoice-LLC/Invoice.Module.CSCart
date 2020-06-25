{* $Id: invoice1.tpl  $cas *}

<div class="control-group">
    <label class="control-label" for="invoice_login">Login</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][invoice_login]" id="invoice_login" value="{$processor_params.invoice_login}" class="input-text" size="60" />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="invoice_api_key">API Key</label>
    <div class="controls">
        <input type="text" name="payment_data[processor_params][invoice_api_key]" id="invoice_api_key" value="{$processor_params.invoice_api_key}" class="input-text" size="60" />
    </div>
</div>
