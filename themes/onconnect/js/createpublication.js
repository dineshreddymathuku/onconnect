(function ($, Drupal) {
  Drupal.behaviors.createpublication = {
    attach: function (context, settings) {
        jQuery('.paragraphs-subform .field--type-list-string .js-form-item select').addClass('optionvalue');
        jQuery('.paragraphs-subform .field--name-field-resource-link').css('display','none');
        jQuery('.paragraphs-subform .field--name-field-upload-file').css('display','none');
        jQuery(".optionvalue").change(function () {
        event.preventDefault();
        // var str = "";
        if(jQuery(this).val() === 'file') {
            jQuery('.paragraphs-subform .field--name-field-resource-link').css('display','none');
            jQuery('.paragraphs-subform .field--name-field-upload-file').css('display','block');
            
        }
        if(jQuery(this).val() === 'link') {
            jQuery('.paragraphs-subform .field--name-field-upload-file').css('display','none');
            jQuery('.paragraphs-subform .field--name-field-resource-link').css('display','block');
        }
        jQuery(".optionvalue option:selected").each(function () {
            if(jQuery(this).val() === '_none') {
                jQuery('.paragraphs-subform .field--name-field-resource-link').css('display','none');
                jQuery('.paragraphs-subform .field--name-field-upload-file').css('display','none');
            }
            if(jQuery(this).val() === 'file') {
                jQuery('.paragraphs-subform .field--name-field-resource-link').css('display','none');
                jQuery('.paragraphs-subform .field--name-field-upload-file').css('display','block');
                
            }
            if(jQuery(this).val() === 'link') {
                jQuery('.paragraphs-subform .field--name-field-upload-file').css('display','none');
                jQuery('.paragraphs-subform .field--name-field-resource-link').css('display','block');
            }
        });
        }); 
    }
  };
})(jQuery, Drupal);