<style>
    #add {
        cursor: pointer;
    }
    .row {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        background: #fff
    }
    textarea {
        min-height: 120px;
        width: 100%;
    }
    .row input {
        width: 100%
    }
    .row label {
        font-size: 11px;
        font-weight: bold;
    }
    .expertWrp {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .row > div {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding-right: 20px
    }
    .row .options {
        flex: 0;
        padding: 0
    }
    .info {
        padding: 2px 4px;
        background: #27ae60;
        color: #fff;
        font-size: 11px !important;
    }
    #callToAction {
        border: 2px solid #3498db;
        border-radius: 5px;
        padding: 10px 10px 25px 10px;
        background: #ecf0f1;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        position: relative;
        margin-right: 10px
    }
    #closeCallTo {
        position: absolute;
        top: 10px;
        right: 10px
    }
    .actionbutt {
        background: #3498db;
        border-radius: 3px;
        color: #fff;
        padding: 10px 20px;
        font-size: 20px;
        text-decoration: none;
        transition: .2s all;
    }
    .actionbutt:hover {
        padding: 13px 25px;
        color: #fff
    }
    .icon {
        color: #3498db;
        font-size: 40px;
        display: block;
        width: 40px;
        height: 30px;
    }
</style>

<h1><?php
  echo esc_html(get_admin_page_title()); ?>
</h1>
<div id="callToAction">
    <img alt="Querlo" src="https://static.Querlo.com/public/assets/logo-querlo.svg" />
    <h2>Build your <b>free</b> chatbot on Querlo.</h2>
    <a href="https://www.querlo.com/register?campaignid=wp-plugin-v12" target="_blank" class="actionbutt">
        <span class="dashicons dashicons-smiley"></span>
        Get it now!
    </a>
    <button type="button" id="closeCallTo"><span class="dashicons dashicons-no-alt"></span></button>
</div>
<form action="options.php" method="post">
  <?php
  /** @var array $_ */
  settings_fields ($_['slug'] . '-settings');
  do_settings_sections ($_['slug'] . '-admin-main-page');
  submit_button ();
  ?>
</form>

