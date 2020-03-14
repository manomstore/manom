$(document).ready(function()
{
  $(document).on('click', '.js-add-section-input', function(e)
  {
    e.preventDefault();

    $('.js-add-section-input-tr').before('<tr><td><input type="text" name="sections[]" value="" size="30" maxlength="255"></td></tr>');
  });
});