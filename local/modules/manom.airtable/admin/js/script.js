$(document).ready(function()
{
  $(document).on('click', '.js-airtable-add-section-input', function(e)
  {
    e.preventDefault();

    let html = '<tr><td><input type="text" name="sections[]" value="" size="30" maxlength="255"></td></tr>';
    $('.js-airtable-add-section-input-tr').before(html);
  });

  $(document).on('click', '.js-airtable-add-service_fields-input', function(e)
  {
    e.preventDefault();

    let html = '<tr><td><input type="text" name="service_fields[]" value="" size="30" maxlength="255"></td></tr>';
    $('.js-airtable-add-service_fields-input-tr').before(html);
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

  let linkProperties = {};
  if ($('input').is('[name=link-properties]')) {
    linkProperties = jQuery.parseJSON($('input[name=link-properties]').val());
  }

  $(document).on('click', '.js-airtable-add-link', function(e)
  {
    e.preventDefault();

    let html = '<tr class="js-airtable-delete-link-tr"><td width="45%">';

    html += '<input name="airtable[]" type="text" value="" size="50">';
    html += '</td><td width="45%">';
    html += '<select name="bitrix[]">';
    html += '<option value=""></option>';

    $.each(linkProperties, function(index, value)
    {
      html += '<option value="' + value.code + '">' + value.name + ' (' + value.code + ')</option>';
    });

    html += '</select>';
    html += '</td><td>';
    html += '<input type="hidden" name="id[]" value="">';
    html += '<button class="js-airtable-delete-link" data-id="">Удалить</button>';
    html += '</td></tr>';

    $('.js-airtable-add-link-tr').before(html);
  });

  $(document).on('click', '.js-airtable-delete-link', function(e)
  {
    e.preventDefault();

    let self = $(this),
      id = self.data('id');

    if (id === '') {
      self.parents('.js-airtable-delete-link-tr').remove();
    } else {
      $.ajax({
        type: 'POST',
        url: $('form[name=airtable-link]').data('action'),
        data: {
          action: 'deleteLink',
          id: id,
        },
        beforeSend: function()
        {
          BX.showWait();
        },
        success: function(data)
        {
          data = jQuery.parseJSON(data);

          if (data.error) {

          } else {
            self.parents('.js-airtable-delete-link-tr').remove();
          }

          BX.closeWait();
        },
        error: function(error)
        {
          BX.closeWait();
        },
      });
    }
  });

    $(document).on('click', '.js-airtable-missing-properties-action', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: $(this).data('action'),
            data: {},
            beforeSend: function () {
                BX.showWait();
            },
            success: function (data) {
                data = jQuery.parseJSON(data);
                var $resultBlock = $(".js-airtable-missing-properties");
                var $resultDesc = $(".js-airtable-missing-properties-desc");
                if (data.length <= 0) {
                    $resultDesc.html("");
                    $resultBlock.html("").append("<p style='margin: 0'>Полей нет</p>");
                } else {
                    $resultBlock.html("");
                    $resultDesc.html("").append("<p>" +
                        "Следующие поля не имеют привязки к свойствам. <br>" +
                        "При следующим импорте, для них будут созданы свойства автоматически. <br>" +
                        "Вы можете добавить некоторые из них в служебные, и они будут проигнорированы." +
                        "</p>");
                    for (var i = 0; i < data.length; i++) {
                        $resultBlock.append("<p style='margin: 0'>" + data[i] + "</p>")
                    }
                }

                BX.closeWait();
            },
            error: function (error) {
                BX.closeWait();
            },
        });
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
      $('.js-airtable-success').hide();
      $('.js-airtable-errors').html('');
      $('.js-airtable-error').hide();
      $('.js-airtable-message').html('').hide();
      $('.js-airtable-warnings').html('').hide();
      $('.js-airtable-info').show();
      BX.showWait();
    },
    success: function(data)
    {
      data = jQuery.parseJSON(data);

      if (data.error) {
        $('.js-airtable-errors').html('<p>' + data.message + '</p>');
        $('.js-airtable-error').show();
      } else {
        if (data.warnings) {
          $('.js-airtable-warnings').html(data.warnings).show();
        }
        if (data.message) {
          $('.js-airtable-message').html(data.message).show();
        }
        $('.js-airtable-success').show();
      }

      form.find('input[type=submit]').prop('disabled', false);
      $('.js-airtable-info').hide();
      BX.closeWait();
    },
    error: function(error)
    {
      form.find('input[type=submit]').prop('disabled', false);
      $('.js-airtable-info').hide();
      $('.js-airtable-errors').html('<p>' + error + '</p>');
      $('.js-airtable-error').show();
      BX.closeWait();
    },
  });
}
