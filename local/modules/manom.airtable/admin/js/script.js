$(document).ready(function()
{
  $(document).on('click', '.js-airtable-add-section-input', function(e)
  {
    e.preventDefault();

    let html = '<tr><td><input type="text" name="sections[]" value="" size="30" maxlength="255"></td></tr>';
    $('.js-airtable-add-section-input-tr').before(html);
  });

  $(document).on('click', '.js-airtable-import-all', function(e)
  {
    e.preventDefault();

    let form = $('form[name=airtable-import]'),
      options = {
        'action': 'all',
      };

    executeRequest(form, options);
  });

  $(document).on('click', '.js-airtable-import-sections', function(e)
  {
    e.preventDefault();

    let form = $('form[name=airtable-import]'),
      sections = [];

    form.find('input[name=sections]:checked').each(function(index)
    {
      sections.push($(this).val());
    });

    let options = {
      'action': 'sections',
      'sections': sections,
    };

    executeRequest(form, options);
  });

  $(document).on('click', '.js-airtable-import-element', function(e)
  {
    e.preventDefault();

    let form = $('form[name=airtable-import]'),
      options = {
        'action': 'element',
        'xmlId': form.find('input[name=xmlId]').val(),
      };

    executeRequest(form, options);
  });
});

function executeRequest(form, options)
{
  $.ajax({
    type: 'POST',
    url: form.attr('action'),
    data: options,
    beforeSend: function()
    {
      form.find('input[type=submit]').prop('disabled', true);
      $('.js-airtable-import-success').hide();
      $('.js-airtable-import-errors').html('');
      $('.js-airtable-import-error').hide();
      $('.js-airtable-import-info').show();
      BX.showWait();
    },
    success: function(data)
    {
      data = jQuery.parseJSON(data);

      if (data.error) {
        $('.js-airtable-import-errors').html('<p>' + data.message + '</p>');
        $('.js-airtable-import-error').show();
      } else {
        $('.js-airtable-import-success').show();
      }

      form.find('input[type=submit]').prop('disabled', false);
      $('.js-airtable-import-info').hide();
      BX.closeWait();
    },
    error: function(error)
    {
      form.find('input[type=submit]').prop('disabled', false);
      $('.js-airtable-import-info').hide();
      $('.js-airtable-import-errors').html('<p>' + error + '</p>');
      $('.js-airtable-import-error').show();
      BX.closeWait();
    },
  });
}
