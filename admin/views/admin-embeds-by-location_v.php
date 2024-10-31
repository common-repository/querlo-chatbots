<style>
    #row_0 .options {
        display: none;
    }
</style>
<div id="rows">
    <?php
/** @var array $_ */
if (!empty($option["locations"])) {
  foreach ($option["locations"] as $i => $location) {
    ?>
          <div class="row" id="row_<?= $i ?>">
              <div class="inputWrp">
                  <label for="<?php echo $_['slug']; ?>-embeds_by_location_regex[locations][]">Address:</label>
                  <input type="text"
                         name="<?php echo $_['slug']; ?>-settings[locations][]"
                         id="location-<?php echo $i; ?>"
                         value="<?php echo esc_attr($location); ?>"
                         class="regular-text"/>
                  <p class="info">a URL or part of a URL where you want this embed code to show. Use * for all site</p>
              </div>
              <div class="textareaWrp">
                  <label for="<?php echo $_['slug']; ?>-embeds_by_location_regex[embeds][]">Embed code:</label>
                  <textarea type="text"
                            placeholder="Paste your Querlo embed code here"
                            name="<?php echo $_['slug']; ?>-settings[embeds][]"
                            id="embed-<?php echo $i; ?>"
                            class="regular-text"><?php echo esc_attr($option['embeds'][$i]); ?></textarea>
              </div>
              
              <div class="options">
                  <span class="dashicons dashicons-trash del" data-id="<?= $i ?>"></span>
              </div>
          </div>
    <?php
  }
}
?>
</div>
<div class="addWrp">
    <button id="add" type="button"><span class="dashicons dashicons-plus-alt"></span> Add another Embed Code</button>
</div>
<script>
  jQuery(document).ready(function($){

    let itemsNum = <?= sizeOf($option["locations"]) ?>;

    function delRow(e) {
      let id = $(e)[0].currentTarget.dataset.id;
      console.log($(e), $(e)[0].currentTarget.dataset.id);
      if (itemsNum > 1) {
        $('#row_' + id).remove();
      } else {
        $('#rows .row location-0').val('');
        $('#rows .row embed-0').val('');
      }
    }
    
    $('.options .del').on('click', delRow);
    
    $('#add').on('click', () => {
      itemsNum++;
      let newRow = $('#rows .row').last().clone();
      let newInputs = newRow.find("input, textarea, .del");
      newRow.attr('id', 'row_' + itemsNum);
      newRow.find("input").attr('id', 'location-' + itemsNum).val('');
      newRow.find("textarea").attr('id', 'embed-' + itemsNum).val('');
      newRow.find(".del").attr('data-id', itemsNum).on('click', delRow);
      $('#rows').append(newRow);
    });
    
    $('#closeCallTo').on('click', () => {
      $('#callToAction').remove();
    });
    
  });
  

</script>
