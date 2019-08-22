$(document).ready ()->
  $.fn.updateCartHeader()
  if $(document).find('#btnSubmitOrder').is('div')
    submitForm()
  # setTimeout(()->
  #   $.fn.checkPropParams()
  # , 5000)
  $(document).on 'click', '#cart_btn_open_login', ()->
    $(document).find('.top-sign .top-sign__on').click()
  $(document).find('form[is-search-form] input[type="text"]').prop('required', true)
  # $(document).on 'click', 'form[is-search-form] input[type="submit"]', (e)->
  #   e.preventDefault()
  #   return false
  $(document).on 'click', '.shopcart-nav1 label', ()->
    if !$(document).find('#'+$(@).attr('for')).hasClass('slide-disable')
      console.log $(@).position().left
      $(document).find('.layout_cart_menu').animate {width: $(@).position().left+'px'}, 500

  $(document).on 'click', '#btnNextSlide', ()->
    inputCount = $(document).find('.shopcart-nav1 input').length
    slideNum = 1
    $(document).find('.shopcart-nav1 input[type="radio"]').each ()->
      if $(@).prop('checked')
        slideNum = $(@).attr('data-num')

      console.log slideNum
      # if parseInt(slideNum) == 2
      #   console.log '#shopcart-tab'+(parseInt(slideNum)+1)
      #   if !$(document).find('.shopcart-nav1 input#shopcart-tab'+(parseInt(slideNum)+1)+'').hasClass('slide-disable')
      #     console.log '1 #shopcart-tab'+(parseInt(slideNum)+1)
      #     $(document).find('.shopcart-sidebar__delivery').removeClass('dsb-hidden')
      if !--inputCount
        if parseInt(slideNum) == 1
          $(document).find('.shopcart-nav1 input#shopcart-tab'+(parseInt(slideNum)+1)+'').removeClass('slide-disable')
          $(document).find('.shopcart-nav1 label[for="shopcart-tab'+(parseInt(slideNum)+1)+'"]').click()
          if (parseInt(slideNum)+1) == 4
            $(document).find('#btnSubmitOrder').removeClass('hidden')
            $(document).find('#btnNextSlide').addClass('hidden')
        else if parseInt(slideNum) == 2
          if $(document).find('#sci-contact-tab1').prop('checked')
            sBlock = $(document).find('#sci-contact-content1')
          else
            sBlock = $(document).find('#sci-contact-content2')

          $count = sBlock.find('input').length
          $formIsValid = true
          sBlock.find('input').each ()->
            if !$(@).val() and $(@).prop('required')
              $formIsValid = false

            if !--$count
              if $formIsValid
                $(document).find('.shopcart-nav1 input#shopcart-tab'+(parseInt(slideNum)+1)+'').removeClass('slide-disable')
                $(document).find('.shopcart-nav1 label[for="shopcart-tab'+(parseInt(slideNum)+1)+'"]').click()
                if (parseInt(slideNum)+1) == 4
                  $(document).find('#btnSubmitOrder').removeClass('hidden')
                  $(document).find('#btnNextSlide').addClass('hidden')
              else
                $.fn.setPushUp("Не заполнены поля", "Поля обязательные к заполнению небыли заполнены",false,"message",false,5000)
        else if parseInt(slideNum) == 3
          $formIsValid = true
          if $(document).find('#sci-delivery-tab1').prop('checked') or $(document).find('#sci-delivery-tab3').prop('checked') or $(document).find('#sci-delivery-tab4').prop('checked') or $(document).find('#sci-delivery-tab5').prop('checked') or $(document).find('#sci-delivery-tab6').prop('checked')
            if !$(document).find('#so_city_val').val()
              $formIsValid = false

            if !$(document).find('#sci-delivery-street').val()
              $formIsValid = false

            if !$(document).find('#sci-delivery-building').val()
              $formIsValid = false

            if !$(document).find('#sci-delivery-apartment').val()
              $formIsValid = false
          else
            if !$(document).find('#so_city_alt_val').val()
              $formIsValid = false

          if $formIsValid
            $(document).find('.shopcart-nav1 input#shopcart-tab'+(parseInt(slideNum)+1)+'').removeClass('slide-disable')
            $(document).find('.shopcart-nav1 label[for="shopcart-tab'+(parseInt(slideNum)+1)+'"]').click()
            if (parseInt(slideNum)+1) == 4
              $(document).find('#btnSubmitOrder').removeClass('hidden')
              $(document).find('#btnNextSlide').addClass('hidden')
          else
            $.fn.setPushUp("Не заполнены поля", "Поля обязательные к заполнению небыли заполнены",false,"message",false,5000)
      if parseInt(slideNum) == 2
        console.log '#shopcart-tab'+(parseInt(slideNum)+1)
        if !$(document).find('.shopcart-nav1 input#shopcart-tab'+(parseInt(slideNum)+1)+'').hasClass('slide-disable')
          console.log '1 #shopcart-tab'+(parseInt(slideNum)+1)
          $(document).find('.shopcart-sidebar__delivery').removeClass('dsb-hidden')


  $(document).on 'click', '.shopcart-nav1 label', ()->
    slideNum = $(document).find('.shopcart-nav1 input[id="'+$(@).attr('for')+'"]').attr('data-num')
    if parseInt(slideNum) == 4
      $(document).find('#btnSubmitOrder').removeClass('hidden')
      $(document).find('#btnNextSlide').addClass('hidden')
    else
      $(document).find('#btnSubmitOrder').addClass('hidden')
      $(document).find('#btnNextSlide').removeClass('hidden')

  $(document).on 'submit', '#popap-call form', ()->
    $this = $(@)


    name = $(@).find('input[name="name"]').val()
    phone = $(@).find('input[name="phone"]').val()
    form_id = $(@).find('input[name="form_id"]').val()
    if name and phone and form_id
      $.ajax {
        url: '/ajax/add_cb.php'
        type: 'POST'
        data: {name: name, phone: phone, form_id: form_id}
        success: (data)->
          $this.find('.form_msg').css('display', 'block')
          $this.find('.form_msg').html('Ваша заявка принятя. Наши менеджеры свяжутся с вами, ожидайте.')
          $this.find('button, label, input').css('display', 'none')
      }
    else
      $this.find('.form_msg').css('display', 'block')
      $this.find('.form_msg').html('Заполните все поля для отправки.')
    return false
  $(document).on 'submit', '#popap-buy-one-click form', ()->
    $this = $(@)


    name = $(@).find('input[name="name"]').val()
    phone = $(@).find('input[name="phone"]').val()
    form_id = $(@).find('input[name="form_id"]').val()
    prod_id = $(@).find('input[name="prod_id"]').val()
    prod_name = $(@).find('input[name="prod_name"]').val()
    email = $(@).find('input[name="email"]').val()
    if name and phone and form_id and email and prod_id and prod_name
      $.ajax {
        url: '/ajax/add_cb.php'
        type: 'POST'
        data: {name: name, phone: phone, form_id: form_id, prod_id: prod_id, prod_name: prod_name, email: email}
        success: (data)->
          $this.find('.form_msg').css('display', 'block')
          $this.find('.form_msg').html('Ваша заявка принятя. Наши менеджеры свяжутся с вами в течении 15 минут.')
          $this.find('button, label, input').css('display', 'none')
      }
    else
      $this.find('.form_msg').css('display', 'block')
      $this.find('.form_msg').html('Заполните все поля для отправки.')
    return false
  $(document).on 'submit', '#popap-buy-one-click-cart form', ()->
    $this = $(@)


    name = $(@).find('input[name="name"]').val()
    phone = $(@).find('input[name="phone"]').val()
    form_id = $(@).find('input[name="form_id"]').val()
    email = $(@).find('input[name="email"]').val()
    if name and phone and form_id and email
      $.ajax {
        url: '/ajax/add_cb.php'
        type: 'POST'
        data: {name: name, phone: phone, form_id: form_id, email: email}
        success: (data)->
          $this.find('.form_msg').css('display', 'block')
          $this.find('.form_msg').html('Ваша заявка принятя. Наши менеджеры свяжутся с вами в течении 15 минут.')
          $this.find('button, label, input').css('display', 'none')
          setTimeout ()->
            $.fn.refreshCart()
          , 3000
      }
    else
      $this.find('.form_msg').css('display', 'block')
      $this.find('.form_msg').html('Заполните все поля для отправки.')
    return false
  if $('#slider-range-alt').is('span')
    $minPrice = parseInt($('#price-start-alt').attr('min'))
    $maxPrice = parseInt($('#price-start-alt').attr('max'))
    if $minPrice and $maxPrice
      $('#slider-range-alt').slider
        range: true
        min: $minPrice
        max: $maxPrice
        step: 100
        values: [
          $minPrice
          $maxPrice
        ]
        slide: (event, ui) ->
          $('#price-start-alt').val ui.values[0]
          $('#price-end-alt').val ui.values[1]
          $(document).find('input[name="'+$('#price-end-alt').attr('data-name')+'"]').prop('checked', false)
          return
      $('#price-start-alt').val $('#slider-range-alt').slider('values', 0)
      $('#price-end-alt').val $('#slider-range-alt').slider('values', 1)
      # подстройка ползунка под введенные значения от 0 до 200000
      $('#price-start-alt').change ->
        $(document).find('input[name="'+$(@).attr('data-name')+'"]').prop('checked', false)
        inputStart = $(this).val()
        if inputStart > $maxPrice
          inputStart = $maxPrice
        if inputStart < $minPrice
          inputStart = $minPrice
        $('#slider-range-alt').slider 'values', 0, inputStart
        $(this).val inputStart
        return
      $('#price-end-alt').change ->
        $(document).find('input[name="'+$(@).attr('data-name')+'"]').prop('checked', false)
        inputEnd = $(this).val()
        if inputEnd > $maxPrice
          inputEnd = $maxPrice
        if inputEnd < $minPrice
          inputEnd = $minPrice
        $('#slider-range-alt').slider 'values', 1, inputEnd
        $(this).val inputEnd
        return
  $(document).on 'click', '.offer_prop_item', ()->
    propCode = $(@).attr('data-prop-code')
    propID = $(@).attr('data-prop-id')
    title = $(@).attr('data-title')
    itemID = $(@).attr('data-id')
    if !$(@).hasClass('propDisabled')
      if $(@).hasClass('active')
        $(document).find('.offers_prop[data-code="'+propCode+'"] .offer_prop_item').removeClass('active')
        $(document).find('.offers_prop[data-code="'+propCode+'"] .prop_title>span').html('Не выбрано')
      else
        $(document).find('.offers_prop[data-code="'+propCode+'"] .offer_prop_item').removeClass('active')
        $(document).find('.offers_prop[data-code="'+propCode+'"] .prop_title>span').html(title)
        $(@).addClass('active')

      $.fn.checkPropParams()

  $(document).on 'change', 'input.catalog-filter__checkbox', ()->
    if !$(@).hasClass('catalogPrice')
      dataValue = $(@).val()
      dataValueTitle = $(@).attr('data-value')
      dataPropTitle = $(@).attr('data-title')
      dataName = $(@).attr('name')
      if $(@).prop('checked')
        elementFilter = '<div class="cb-filter__param" data-id="'+dataName+'">'
        elementFilter += ''+dataPropTitle+dataValueTitle
        elementFilter += '<input type="hidden" name="'+dataName+'" value="'+dataValue+'">'
        elementFilter += '<span>×</span>'
        elementFilter += '</div>'
        $(document).find(".cb-filter").prepend($(elementFilter))
      else
        $(document).find('.cb-filter__param[data-id="'+dataName+'"]').remove()
    else
      dataPropTitle = $(@).attr('data-title')
      dataMinName = $(@).attr('data-name-min')
      dataMaxName = $(@).attr('data-name-max')
      dataMinValue = $(document).find('.catalog-filter input[name="'+dataMinName+'"]').val()
      dataMaxValue = $(document).find('.catalog-filter input[name="'+dataMaxName+'"]').val()
      $(document).find('.cb-filter__param[data-id="'+dataMinName+dataMaxName+'"]').remove()
      if $(@).prop('checked')
        elementFilter = '<div class="cb-filter__param cb-filter__param-hidden" data-id="'+dataMinName+dataMaxName+'">'
        elementFilter += dataPropTitle+'от: '+dataMinValue+" до: "+dataMaxValue
        elementFilter += '<input type="hidden" name="'+dataMinName+'" value="'+dataMinValue+'">'
        elementFilter += '<input type="hidden" name="'+dataMaxName+'" value="'+dataMaxValue+'">'
        elementFilter += '<span>×</span>'
        elementFilter += '</div>'
        $(document).find(".cb-filter").prepend($(elementFilter))
    $.fn.ajaxLoadCatalog()

  $clearAll = false
  $(document).on 'click', '.cb-filter__param>span', ()->
    $el = $(@).parent('div')
    $(document).find('.catalog-filter input[name="'+$el.attr('data-id')+'"]').prop("checked", false)
    $el.remove();
    if $clearAll == false
      $.fn.ajaxLoadCatalog()

  $(document).on 'click', '.cb-filter__clear', ()->
    $clearAll = true
    $(document).find('.cb-filter__param>span').click()
    $clearAll = false
    $.fn.ajaxLoadCatalog()

  $(document).on 'click', '.ajaxPageNav .cb-nav-pagination__item a', (e)->
    e.preventDefault()
    $parentEl = $(@).parent('div')
    if !$parentEl.hasClass('active')
      $(document).find('.ajaxPageNav .cb-nav-pagination__item').removeClass('active')
      $parentEl.addClass('active')

    $.fn.ajaxLoadCatalog()

    return false
  $(document).on 'mouseenter', '.product-card', ()->
    $(this).children('.product-card__img').slick
      arrows: true
      dots: false
      infinite: true
      speed: 1000
    $(this).children('.p-nav-top').fadeIn 200
  $(document).on 'mouseleave', '.product-card', ()->
    $(this).children('.product-card__img').slick 'unslick'
    $(this).children('.p-nav-top').fadeOut 200
    return

  $(document).on 'change', 'select[name="countOnPage"], select[name="sort_by"]', ()->
    $.fn.ajaxLoadCatalog()

  $(document).on 'click', '.addToCartBtn', ()->
    if !$(@).hasClass('addToCartBtn_dis')
      $this = $(@)
      $(@).addClass('addToCartBtn_dis')
      productID = $(@).attr('data-id')
      if productID
        $.ajax {
          url: '/ajax/add_to_cart.php'
          type: 'POST'
          data: {PRODUCT_ID: productID, METHOD_CART: 'add', AJAX_MIN_CART: 'Y'}
          success: (data) ->
            $.fn.setPushUp("Товар добавлен", "Товар был успешно добавлен в вашу корзину",false,"message",false,5000)
            $this.removeClass('addToCartBtn_dis')
            if $this.hasClass('product-sidebar__button')
              $this.addClass('dsb-hidden')
              $this.after('<a class="product-sidebar__button goToFcnCart" href="/cart/" data-id="'+productID+'">В корзину</a>')
            # else if $this.hasClass('cb-line-bottom__buy')
            #   $this.after('<button class="cb-line-bottom__buy goToFcnCart" href="/cart/" data-id="'+productID+'">В корзину</button>')
            # else if $this.hasClass('cb-line-bottom__buy')
            #   $this.after('<button class="cb-line-bottom__buy goToFcnCart" href="/cart/" data-id="'+productID+'">В корзину</button>')
            $.fn.updateMiniCart(data)
            if $this.hasClass('addToCartBtn_inCart')
              $.fn.refreshCart()
        }
  $(document).on 'click', '.preview-prod-bottom__button-cart', ()->
    $cartItemID = $(@).attr('data-cart-item')
    # $this = $(@)
    if $cartItemID
      $(document).find('#mini_cart_header .preview-prod[data-cart-item="'+$cartItemID+'"]').remove()
      $.ajax {
        url: '/ajax/add_to_cart.php'
        type: 'POST'
        data: {PRODUCT_ID: $cartItemID, METHOD_CART: 'delete', AJAX_MIN_CART: 'Y'}
        success: (data) ->
          # $this.removeClass('addToCartBtn_dis')
          $.fn.setPushUp("Товар удален", "Товар был удален из вашей корзины",false,"message",false,5000)
          $.fn.updateMiniCart(data)
      }
  $(document).on 'click', '.preview-prod-bottom__button-favorite', ()->
    $cartItemID = $(@).attr('data-cart-item')
    # $this = $(@)
    if $cartItemID
      $(document).find('#mini_favorite_header .preview-prod[data-cart-item="'+$cartItemID+'"]').remove()
      $.ajax {
        type: 'POST'
        url: '/ajax/ajax_func.php'
        data: {change_favorite_list: 'Y', product_id: $cartItemID, AJAX_MIN_FAVORITE: 'Y'}
        success: (data) ->
          # $this.removeClass('addToCartBtn_dis')
          $.fn.setPushUp("Товар удален", "Товар был удален из избраных товаров",false,"message",false,5000)
          $.fn.updateMiniFavorite(data)
          if $(document).find('.addToFavoriteListOnFP').is('div')
            $.fn.ajaxLoadCatalog()
          if $(document).find('.addToFavoriteListOnFP_NOT_ITEM').is('div')
            location.href = '/user/favorite/'
      }
  $(document).on 'click', '.preview-prod-bottom__button-compare', ()->
    $cartItemID = $(@).attr('data-cart-item')
    # $this = $(@)
    if $cartItemID
      $(document).find('#mini_compare_header .preview-prod[data-cart-item="'+$cartItemID+'"]').remove()
      $.ajax {
        type: 'POST'
        url: '/ajax/ajax_func.php'
        data: {change_compare_list: 'Y', product_id: $cartItemID, AJAX_MIN_COMPARE: 'Y'}
        success: (data) ->
          # $this.removeClass('addToCartBtn_dis')
          $.fn.setPushUp("Товар удален", "Товар был удален из списков сравнения",false,"message",false,5000)
          $(document).find('.compare-page-item[data-id="'+$cartItemID+'"] .compare__basket.hidden-remove').click()
          $.fn.updateMiniCompare(data)
      }
  $(document).on 'click', '.sci-top__count-up, .sci-top__count-down', ()->
    cartItemID = $(@).attr('data-id')
    countProd = parseInt($(@).attr('data-q'))
    if $(@).hasClass('sci-top__count-up')
      countProd = countProd+1
    else
      countProd = countProd-1

    if countProd > 0
      $(document).find('.sci-product[data-id="'+cartItemID+'"] .sci-top__count span').html(countProd)
      $.ajax {
        url: '/ajax/add_to_cart.php'
        type: 'POST'
        data: {PRODUCT_ID: cartItemID, METHOD_CART: 'CHANGE_COUNT', AJAX_CART: 'Y', COUNT: countProd}
        success: (data)->
          $.fn.updateCart(data)
          $.fn.refreshMiniCart()
      }
  $(document).on 'click', '.sci-top__remove', ()->
    $cartItemID = $(@).attr('data-id')
    # $this = $(@)
    if $cartItemID
      $(document).find('#shopcart-item1 .sci-product[data-id="'+$cartItemID+'"]').remove()
      $.ajax {
        url: '/ajax/add_to_cart.php'
        type: 'POST'
        data: {PRODUCT_ID: $cartItemID, METHOD_CART: 'delete', AJAX_CART: 'Y'}
        success: (data) ->
          $.fn.updateCart(data)
          $.fn.setPushUp("Товар удален", "Товар был удален из вашей корзины",false,"message",false,5000)
      }


  $(document).on 'click', '.square-color', (e) ->
    if !$(@).hasClass('propDisabled')
      dcolor = $(@).attr('data-color')

      $(document).find('.product-photo__left img').removeClass('active')
      $(document).find('.product-photo__left .pp__is_offer').addClass('pp__is_offer__disable')
      $(document).find('.product-photo__left .pp__is_offer[data-color="'+dcolor+'"]').removeClass('pp__is_offer__disable')

      $(document).find('.product-photo__right .pp__big_photo').removeClass('active')
      $(document).find('.product-photo__right .pp__is_offer').addClass('pp__is_offer__disable')
      $(document).find('.product-photo__right .pp__is_offer').attr 'data-fancybox', ''
      $(document).find('.product-photo__right .pp__is_offer[data-color="'+dcolor+'"]').removeClass('pp__is_offer__disable')
      $(document).find('.product-photo__right .pp__is_offer[data-color="'+dcolor+'"]').attr 'data-fancybox', 'gallery-prod'

      activePhoto = null
      if $(document).find('.product-photo__left .pp__is_offer[data-color="'+dcolor+'"]').is('img')
        activePhoto = $(document).find('.product-photo__left .pp__is_offer[data-color="'+dcolor+'"]').eq(0)
      else if $(document).find('.product-photo__left .pp__is_prod').is('img')
        activePhoto = $(document).find('.product-photo__left .pp__is_prod').eq(0)

      if activePhoto
        activePhoto.addClass('active')
        $(document).find('.product-photo__right .pp__big_photo[data-photo-id="'+activePhoto.attr('data-photo-id')+'"]').addClass('active')

  $(document).on 'click', '.product-photo__left img', ()->
    $(document).find('.product-photo__left img').removeClass('active')
    $(document).find('.product-photo__right .pp__big_photo').removeClass('active')
    $(@).addClass('active')
    $(document).find('.product-photo__right .pp__big_photo[data-photo-id="'+$(@).attr('data-photo-id')+'"]').addClass('active')

  $(document).on 'click', '.addToFavoriteList', ()->
    prodID = $(@).attr 'data-id'
    $this = $(@)

    $.ajax {
      type: 'POST',
      url: '/ajax/ajax_func.php'
      data: {change_favorite_list: 'Y', product_id: prodID, AJAX_MIN_FAVORITE: 'Y'}
      success: (data)->
        $.fn.updateMiniFavorite(data)
        if $this.hasClass('notActive')
          $this.removeClass('notActive')
          $.fn.setPushUp("Закладки", "Товар был добавлен в избраное",false,"message",false,5000)
          $(document).find('.addToFavoriteList[data-id="'+prodID+'"]').parent('label').find('input[type="checkbox"]').prop('checked', true)
        else
          $this.addClass('notActive')
          $.fn.setPushUp("Избраное", "Товар был удален из избраного",false,"message",false,5000)
          $(document).find('.addToFavoriteList[data-id="'+prodID+'"]').parent('label').find('input[type="checkbox"]').prop('checked', false)
        if $(document).find('.addToFavoriteListOnFP').is('div')
          $.fn.ajaxLoadCatalog()
        if $(document).find('.addToFavoriteListOnFP_NOT_ITEM').is('div')
          location.href = '/user/favorite/'

    }


  # $(document).on 'click', '.search-popup-row, .search-popup-row-active', ()->
  #   itemVal = $(@).find('.search-popup-el-name').text()
  #   if itemVal
  #     location.href = '/search/index.php?q='+itemVal


  $(document).on 'click', '.addToCompareList', ()->
    prodID = $(@).attr 'data-id'
    $this = $(@)

    $.ajax {
      type: 'POST',
      url: '/ajax/ajax_func.php'
      data: {change_compare_list: 'Y', product_id: prodID, AJAX_MIN_COMPARE: 'Y'}
      success: (data)->
        $.fn.updateMiniCompare(data)
        if $this.hasClass('notActive')
          $.fn.setPushUp("Сравнение", "Товар был добавлен в сравнение",false,"message",false,5000)
          $(document).find('.addToCompareList[data-id="'+prodID+'"]').removeClass('notActive')
        else
          $.fn.setPushUp("Сравнение", "Товар был удален из сравнения",false,"message",false,5000)
          $(document).find('.addToCompareList[data-id="'+prodID+'"]').addClass('notActive')

    }

#   $(document).on 'change', '#sci-contact-tab1, #sci-contact-tab2', ()->
#     typeBuyer = 'fiz'
#     if $(document).find('#sci-contact-tab2').prop('checked')
#       typeBuyer = 'ur'
#     $.fn.customOrderChangeTab 'changeBuyer', typeBuyer
#     setTimeout ()->
#       $.fn.customOrderChangeTab 'getBuyerInfo', typeBuyer
#     , 700
#
# $.fn.customOrderChangeTab = (tab, valTab)->
#   if tab == 'changeBuyer'
#     $(document).find('#bx-soa-region .bx-soa-section-title-container').click()
#     radioVal = 1
#     if valTab == 'ur'
#       radioVal = 2
#
#
#     changeState = false
#     $counter = 0
#     while changeState == false
#       $counter += 1
#       if $(document).find('#bx-soa-region input[name="PERSON_TYPE"][value="'+radioVal+'"]').parent('label').is('label')
#         changeState = true
#         $(document).find('#bx-soa-region input[name="PERSON_TYPE"][value="'+radioVal+'"]').parent('label').click()
#       if $counter > 100
#         changeState = true
#
#   else if tab == 'getBuyerInfo'
#     $(document).find('#bx-soa-properties .bx-soa-section-title-container').click()
#
#
#     changeState = false
#     $counter = 0
#     while changeState == false
#       $counter += 1
#       # changeState = true
#       if valTab == 'fiz' and $(document).find('#bx-soa-properties input[name="ORDER_PROP_1"]').is('input')
#         changeState = true
#
#         if !$(document).find('#sci-contact-content1 input[name="sci-contact__fio"]').val()
#           $(document).find('#sci-contact-content1 input[name="sci-contact__fio"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_1"]').val())
#         if !$(document).find('#sci-contact-content1 input[name="sci-contact__tel"]').val()
#           $(document).find('#sci-contact-content1 input[name="sci-contact__tel"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_2"]').val())
#         if !$(document).find('#sci-contact-content1 input[name="sci-contact__email"]').val()
#           $(document).find('#sci-contact-content1 input[name="sci-contact__email"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_3"]').val())
#
#       else if valTab == 'ur' and $(document).find('#bx-soa-properties input[name="ORDER_PROP_4"]').is('input')
#         changeState = true
#
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-name"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-name"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_4"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-legal-name"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-legal-name"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_5"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-ogrn"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-ogrn"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_6"]').val())
#
#         $(document).find('#sci-contact-content2 select[name="sci-contact__ur-legal-norm"] option[value="'+$(document).find('#bx-soa-properties input[name="ORDER_PROP_8"]').val()+'"]').prop('selected', true);
#
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-inn"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-inn"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_9"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-kpp"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-kpp"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_10"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-legal-address"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-legal-address"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_11"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__ur-fact-address"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__ur-fact-address"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_12"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__bank-name"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__bank-name"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_13"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__bank-sity"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__bank-sity"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_14"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__bank-bik"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__bank-bik"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_15"]').val())
#         if !$(document).find('#sci-contact-content2 input[name="sci-contact__bank-account"]').val()
#           $(document).find('#sci-contact-content2 input[name="sci-contact__bank-account"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_16"]').val())
#         if !$(document).find('#sci-contact-content2 textarea[name="sci-contact__contacts"]').val()
#           $(document).find('#sci-contact-content2 textarea[name="sci-contact__contacts"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_17"]').val())
#
#         if !$(document).find('#sci-contact-content2 textarea[name="sci-contact__ur-email"]').val()
#           $(document).find('#sci-contact-content2 textarea[name="sci-contact__ur-email"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_32"]').val())
#         if !$(document).find('#sci-contact-content2 textarea[name="sci-contact__ur-phone"]').val()
#           $(document).find('#sci-contact-content2 textarea[name="sci-contact__ur-phone"]').val($(document).find('#bx-soa-properties input[name="ORDER_PROP_33"]').val())
#
#
#       if $counter > 30
#         changeState = true

  $.fn.updateDateSaleOrder()
  $(document).on 'click', '#soDelivPopUp', ()->
    $(document).find('.SDEK_selectPVZ').click()
  $(document).on 'click', '.rb_so', ()->
    if $(@).attr('data-prop')
      $.fn.changeRadioButtonSaleOrder($(@).attr('data-prop'))

  $(document).find('#module_so').bind 'DOMSubtreeModified', ()->
    soModule = $(document).find('#module_so')
    if soModule.find('.wrewfwer .wrewfwer_ajax').is('span')
      soModule.find('.wrewfwer .wrewfwer_ajax').remove();
      $.fn.updateDateSaleOrder()

  $(document).on 'change', '#so_city_val', ()->
    soCityID = $(document).find('#so_city')
    soCityAltID = $(document).find('#so_city_alt')
    soCityAlt = $(document).find('#so_city_alt_val')
    soBlock = $(document).find('#so_main_block')
    soModule = $(document).find('#module_so')
    $this = $(@)
    setTimeout ()->
      # if $this.attr('data-change') == "Y"
        if soCityID.val() == soCityID.attr('data-old')
          $this.val($this.attr('data-old'))
          soCityID.val(soCityID.attr('data-old'))
        else
          soCityID.attr('data-old', $this.val())
          $this.attr('data-old', soCityID.val())
          soModule.find('[name="'+$this.attr('data-city-prop')+'"]').val(soCityID.val())
          soModule.find('[name="'+$this.attr('data-city-prop-val')+'"]').val($this.val())
          soModule.find('[name="'+$this.attr('data-city-prop-alt')+'"]').val(soCityID.val())
          soModule.find('[name="'+$this.attr('data-city-prop-val-alt')+'"]').val($this.val())
          soCityAltID.val(soCityID.val())
          soCityAlt.val($this.val())
          soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive')
          submitForm()
    , 300

  $(document).on 'change', '#so_city_alt_val', ()->
    soCityID = $(document).find('#so_city_alt')
    soCityAltID = $(document).find('#so_city')
    soCityAlt = $(document).find('#so_city_val')
    soBlock = $(document).find('#so_main_block')
    soModule = $(document).find('#module_so')
    $this = $(@)
    setTimeout ()->
      # if $this.attr('data-change') == "Y"
        if soCityID.val() == soCityID.attr('data-old')
          $this.val($this.attr('data-old'))
          soCityID.val(soCityID.attr('data-old'))
        else
          soCityID.attr('data-old', $this.val())
          $this.attr('data-old', soCityID.val())
          soModule.find('[name="'+$this.attr('data-city-prop')+'"]').val(soCityID.val())
          soModule.find('[name="'+$this.attr('data-city-prop-val')+'"]').val($this.val())
          soModule.find('[name="'+$this.attr('data-city-prop-alt')+'"]').val(soCityID.val())
          soModule.find('[name="'+$this.attr('data-city-prop-val-alt')+'"]').val($this.val())
          soCityAltID.val(soCityID.val())
          soCityAlt.val($this.val())
          soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive')
          submitForm()
    , 300

  $(document).on 'change', '[name="sci-contact__fio"],
  [name="sci-contact__tel"], [name="sci-contact__ur-name"],
  [name="sci-contact__ur-phone"], [name="so_city_val"], [name="so_city_alt_val"], [name="sci-delivery-street"],
  [name="sci-delivery-building"], [name="sci-delivery-apartment"], [name="sci-delivery-date"],
  [name="sci-delivery-time"], [name="ORDER_PROP_37"], [name="ORDER_PROP_36"]', ()->
    $.fn.updateSideInfo()

  $(document).on 'click', '#btnSubmitOrder', ()->
    soBlock = $(document).find('#so_main_block')
    soModule = $(document).find('#module_so')
    firstBlock = '#shopcart-item2 #sci-contact-content1'
    secondBlock = '#shopcart-item3 #sci-delivery-content1'
    if $(document).find('#sci-contact-tab2').prop('checked')
      firstBlock = '#shopcart-item2 #sci-contact-content2'
    if $(document).find('#sci-delivery-tab2').prop('checked')
      secondBlock = '#shopcart-item3 #sci-delivery-content2'

    inputCount = $(document).find(firstBlock+" input, "+firstBlock+" textarea, "+firstBlock+" select").length
    hasError = false
    $(document).find(firstBlock+" input, "+firstBlock+" textarea, "+firstBlock+" select").each ()->
      if $(@).attr('data-prop')
        valEl = $(@).val()
        if $(@).prop('required') and !valEl
          $(document).find('#shopcart-tab2').click()
          $(@).css {"border-color": '#ef0000'}
          $(@).on 'focus', ()->
            $(@).css {"border-color": '#C4C4C4'}
          hasError = true

        if $(@).is('select')
          soModule.find('[name="'+$(@).attr('data-prop')+'"] option[value="'+valEl+'"]').prop('selected', true)
        else
          soModule.find('[name="'+$(@).attr('data-prop')+'"]').val(valEl)
      if !--inputCount
        if !hasError
          if secondBlock
            inputCount = $(document).find(secondBlock+" input, "+secondBlock+" textarea, "+secondBlock+" select").length
            $(document).find(secondBlock+" input, "+secondBlock+" textarea, "+secondBlock+" select").each ()->
              if $(@).attr('data-prop')
                valEl = $(@).val()
                if $(@).prop('required') and !valEl
                  $(document).find('#shopcart-tab3').click()
                  $(@).css {"border-color": '#ef0000'}
                  $(@).on 'focus', ()->
                    $(@).css {"border-color": '#C4C4C4'}
                  hasError = true

                soModule.find('[name="'+$(@).attr('data-prop')+'"]').val(valEl)
              if !--inputCount
                if !hasError
                  soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive')
                  $(document).find('.layout_cart_menu').animate {width:'100%'}, 500
                  soModule.find('[name="submitbutton"]').click()




$.fn.updateSideInfo = ()->
  soBlock = $(document).find('#so_main_block')
  soModule = $(document).find('#module_so')

  uName = '[name="sci-contact__fio"]'
  uPhone = '[name="sci-contact__tel"]'
  if soModule.find('#PERSON_TYPE_2').prop('checked')
    uName = '[name="sci-contact__ur-name"]'
    uPhone = '[name="sci-contact__ur-phone"]'
  soBlock.find('.shopcart-sidebar__buyer-fio').html(soBlock.find(uName).val())
  soBlock.find('.shopcart-sidebar__buyer-tel').html(soBlock.find(uPhone).val())

  uCity = soBlock.find('[name="so_city_val"]').val()
  uAddress = ""
  if soBlock.find('[name="sci-delivery-street"]').val()
    uAddress += soBlock.find('[name="sci-delivery-street"]').val()+" "
  if soBlock.find('[name="sci-delivery-building"]').val()
    uAddress += "д. "+soBlock.find('[name="sci-delivery-building"]').val()+" "
  if soBlock.find('[name="sci-delivery-apartment"]').val()
    uAddress += "кв. "+soBlock.find('[name="sci-delivery-apartment"]').val()+" "
  uDeliveryDate = soBlock.find('[name="sci-delivery-date"]').val()
  uDeliveryTime = soBlock.find('[name="sci-delivery-time"]').val()
  deliveryPrice = '0'
  totalPrice = '0'
  soModule.find('.sale_order_full tfoot tr').each ()->
    if $(@).find('td').eq(0).find('b').is('b')
      if $(@).find('td').eq(0).find('b').html().toString() == 'Доставка:'
        deliveryPrice = $(@).find('td').eq(1).html().toString().replace('руб.', '')
      if $(@).find('td').eq(0).find('b').html().toString() == 'Итого:'
        totalPrice = $(@).find('td').eq(1).find('b').html().toString().replace('руб.', '')
  uDeliveryTime = soModule.find('[for="ID_DELIVERY_ID_6"] .so_delivery_period').html()
  if soModule.find('#ID_DELIVERY_ID_6').prop('checked')
    if soModule.find('#PERSON_TYPE_2').prop('checked')
      uAddress = soModule.find('[name="ORDER_PROP_37"]').val()
    else
      uAddress = soModule.find('[name="ORDER_PROP_36"]').val()

    uDeliveryDate = ""
    uDeliveryTime = ""

    uDeliveryTime = soModule.find('[for="ID_DELIVERY_ID_6"] .so_delivery_period').html()

    if uAddress
      soBlock.find('.pickup_address span').html(uAddress)
    soBlock.find('.pickup_summ span').html(deliveryPrice+'₽')
    soBlock.find('.pickup_date span').html(uDeliveryTime)
  if soModule.find('#ID_DELIVERY_ID_13').prop('checked')
    uCity = ''
    uAddress = $(document).find('label[for="ID_DELIVERY_ID_13"] .dsc_soa').html()
    deliveryPrice = $(document).find('label[for="ID_DELIVERY_ID_13"] .prs_soa').html().replace('руб.', '')
    uDeliveryTime = $(document).find('label[for="ID_DELIVERY_ID_13"] .so_delivery_period').html()

    soBlock.find('.sv_address').html($(document).find('label[for="ID_DELIVERY_ID_13"] .dsc_soa').html())
    soBlock.find('.sv_price span').html($(document).find('label[for="ID_DELIVERY_ID_13"] .prs_soa').html().replace('руб.', '')+'₽')
    soBlock.find('.sv_time span').html($(document).find('label[for="ID_DELIVERY_ID_13"] .so_delivery_period').html())

  soBlock.find('.pickup_summ_alt span').html(deliveryPrice+'₽')
  soBlock.find('.shopcart-sidebar__delivery-price span').html(deliveryPrice)
  soBlock.find('#total_price_cart').html(totalPrice)

  soBlock.find('.shopcart-sidebar__delivery-city').html(uCity)
  soBlock.find('.shopcart-sidebar__delivery-address').html(uAddress)
  if (!uDeliveryDate)
    soBlock.find('.shopcart-sidebar__delivery-date').hide()
  else
    soBlock.find('.shopcart-sidebar__delivery-date').show()
    soBlock.find('.shopcart-sidebar__delivery-date span').html(uDeliveryDate)
  if (!uDeliveryTime)
    soBlock.find('.shopcart-sidebar__delivery-time').hide()
  else
    soBlock.find('.shopcart-sidebar__delivery-time').show()
    soBlock.find('.shopcart-sidebar__delivery-time span').html(uDeliveryTime)
$.fn.changeRadioButtonSaleOrder = (l_name)->
  soBlock = $(document).find('#so_main_block')
  soModule = $(document).find('#module_so')

  if soModule.find('[for="'+l_name+'"]').is('label')
    soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive')
    soModule.find('[for="'+l_name+'"]').click()

$.fn.updateDateSaleOrder = ()->
  soBlock = $(document).find('#so_main_block')
  soModule = $(document).find('#module_so')

  soBlock.find('.rb_so').addClass('rb_so_disbled')
  soBlock.find('.rb_so').each ()->
    if $(@).attr('data-prop')
      if soModule.find('#'+$(@).attr('data-prop')+'').is('input')
        titleDeliv = soModule.find('label[for="'+$(@).attr('data-prop')+'"]>b').eq(0).html()
        $(@).find('span').html(titleDeliv)
        $(@).find('span.sci-payment__radio').html('')
        $(@).removeClass('rb_so__hide')
        if soModule.find('#'+$(@).attr('data-prop')+'').prop('checked')
          $(@).click()
      else
        $(@).addClass('rb_so__hide')
    $(@).removeClass('rb_so_disbled')

  soModule.find('.sale_order_full_table input[name="DELIVERY_ID"]').each ()->
    delivID = $(@).attr('id')
    if !soBlock.find('.sci-delivery-tabs .rb_so[data-prop="'+delivID+'"]').is('label')
      indLav = parseInt(soBlock.find('.sci-delivery-tabs .rb_so').length)+1
      titleDeliv = soModule.find('label[for="'+delivID+'"]>b').eq(0).html()
      soBlock.find('.sci-delivery-tabs').prepend('<label data-prop="'+delivID+'" class="sci-delivery-tab rb_so" for="sci-delivery-tab'+indLav+'"><span>'+titleDeliv+'</span></label>')
      soBlock.find('.sci-delivery-tabs').prepend('<input id="sci-delivery-tab'+indLav+'" type="radio" name="delivery-tabs" class="rb_so_proxy">')
      if $(@).prop('checked')
        soBlock.find('.rb_so[data-prop="'+delivID+'"]').click()
  soModule.find('.sale_order_full_table input[name="PAY_SYSTEM_ID"]').each ()->
    delivID = $(@).attr('id')
    if !soBlock.find('.sci-payment-tabs .rb_so[data-prop="'+delivID+'"]').is('label')
      indLav = parseInt(soBlock.find('.sci-payment-tabs .rb_so').length)+1
      titleDeliv = soModule.find('label[for="'+delivID+'"]>b').eq(0).html()
      htmlNewEl = $('<label class="sci-payment__tab rb_so" data-prop="'+delivID+'">')
      htmlNewEl.append('<input id="sci-payment-tab'+indLav+'" type="radio" name="payment-tabs" class="sci-payment__input">')
      htmlNewEl.append('<span class="sci-payment__radio"></span>')
      htmlNewEl.append('<span class="sci-payment__name">'+titleDeliv+'</span>')
      soBlock.find('.sci-payment-tabs').prepend(htmlNewEl)
      # soBlock.find('.sci-payment-tabs').prepend('<label data-prop="'+delivID+'" class="sci-delivery-tab rb_so" for="sci-delivery-tab'+indLav+'"><span>'+titleDeliv+'</span></label>')
      # soBlock.find('.sci-payment-tabs').prepend('<input id="sci-delivery-tab'+indLav+'" type="radio" name="delivery-tabs" class="rb_so_proxy">')
      if $(@).prop('checked')
        soBlock.find('.rb_so[data-prop="'+delivID+'"]').click()

  soBlock.find('input, textarea, select').each ()->
    if $(@).attr('data-change') != 'Y' and $(@).attr('data-prop')
      if !$(@).is('select')
        if soModule.find('[name="'+$(@).attr('data-prop')+'"]').is('input')
          $(@).val(soModule.find('[name="'+$(@).attr('data-prop')+'"]').val())
          $(@).attr('data-change', 'Y')
        if soModule.find('[name="'+$(@).attr('data-prop-alt')+'"]').is('input')
          $(@).val(soModule.find('[name="'+$(@).attr('data-prop-alt')+'"]').val())
          $(@).attr('data-change', 'Y')
      else
        if soModule.find('[name="'+$(@).attr('data-prop')+'"]').is('select')
          $(@).find('option[value="'+soModule.find('[name="'+$(@).attr('data-prop')+'"]').val()+'"]').prop('selected', true)
          $(@).attr('data-change', 'Y')

  soCity = soBlock.find('#so_city_val')
  soCityID = soBlock.find('#so_city')
  soCityAlt = soBlock.find('#so_city_alt_val')
  soCityAltID = soBlock.find('#so_city_alt')
  if soCity.attr('data-change') != 'Y' and soCity.attr('data-city-prop') and soCity.attr('data-city-prop-val')
    if soModule.find('[name="'+soCity.attr('data-city-prop-val')+'"]').is('input')
      soCity.val(soModule.find('[name="'+soCity.attr('data-city-prop-val')+'"]').val())
      soCityID.val(soModule.find('[name="'+soCity.attr('data-city-prop')+'"]').val())
      soCity.attr('data-old', soModule.find('[name="'+soCity.attr('data-city-prop-val')+'"]').val())
      soCityID.attr('data-old', soModule.find('[name="'+soCity.attr('data-city-prop')+'"]').val())

      soCityAlt.val(soModule.find('[name="'+soCityAlt.attr('data-city-prop-val')+'"]').val())
      soCityAltID.val(soModule.find('[name="'+soCityAlt.attr('data-city-prop')+'"]').val())
      soCityAlt.attr('data-old', soModule.find('[name="'+soCityAlt.attr('data-city-prop-val')+'"]').val())
      soCityAltID.attr('data-old', soModule.find('[name="'+soCityAlt.attr('data-city-prop')+'"]').val())

    if soModule.find('[name="'+soCity.attr('data-city-prop-val-alt')+'"]').is('input')
      soCity.val(soModule.find('[name="'+soCity.attr('data-city-prop-val-alt')+'"]').val())
      soCityID.val(soModule.find('[name="'+soCity.attr('data-city-prop-alt')+'"]').val())
      soCity.attr('data-old', soModule.find('[name="'+soCity.attr('data-city-prop-val-alt')+'"]').val())
      soCityID.attr('data-old', soModule.find('[name="'+soCity.attr('data-city-prop-alt')+'"]').val())

      soCityAlt.val(soModule.find('[name="'+soCityAlt.attr('data-city-prop-val-alt')+'"]').val())
      soCityAltID.val(soModule.find('[name="'+soCityAlt.attr('data-city-prop-alt')+'"]').val())
      soCityAlt.attr('data-old', soModule.find('[name="'+soCityAlt.attr('data-city-prop-val-alt')+'"]').val())
      soCityAltID.attr('data-old', soModule.find('[name="'+soCityAlt.attr('data-city-prop-alt')+'"]').val())

    # soCity.attr('data-change', 'Y')
  $.fn.updateSideInfo()
  soModule.find('.errortext').each ()->
    $.fn.setPushUp("Ошибка", $(@).text(),false,"message",false,5000)


  soBlock.find('.preloaderCatalog').removeClass('preloaderCatalogActive')

$.refreshCartInfo = ()->
  $.ajax {
    url: '/ajax/add_to_cart.php'
    type: 'POST'
    data: {METHOD_CART: 'refredh_cart_info', AJAX_CART_INFO: 'Y'}
    success: (data) ->
      $.fn.updateCartInfo(data)
      $.fn.updateCartHeader()

  }

$.fn.updateCartInfo = (data)->
  $ft = $('<div></div>').append(data)
  # $(document).find('#total_price_cart').html($ft.find('#cart_sum_prod').html())
  $(document).find('#cart_info_block').html($ft.html())

$.fn.updateCartHeader = ()->
  if $(document).find('.shopcart-tab[for="shopcart-tab1"]').is('label')
    prodCount = $(document).find('#cart_count_prod').html()
    cartSum = $(document).find('#cart_sum_prod').html()
    $(document).find('.shopcart-tab[for="shopcart-tab1"] span').html(prodCount+' товара, '+cartSum+' руб.')
$.fn.refreshMiniCart = ()->
  $.ajax {
    url: '/ajax/add_to_cart.php'
    type: 'POST'
    data: {METHOD_CART: 'refredh_mini_cart', AJAX_MIN_CART: 'Y'}
    success: (data) ->
      $.fn.updateMiniCart(data)
  }
$.fn.refreshCart = ()->
  $.ajax {
    url: '/ajax/add_to_cart.php'
    type: 'POST'
    data: {METHOD_CART: 'refredh_cart', AJAX_CART: 'Y'}
    success: (data) ->
      $.fn.updateCart(data)
  }
$.fn.updateMiniCompare = (data)->
  $ft = $('<div></div>').append(data)
  $(document).find('#mini_compare_header_counter').html($ft.find('#mini_compare_header_counter').html())
  $(document).find('#mini_compare_header').html($ft.find('#mini_compare_header').html())
$.fn.updateMiniFavorite = (data)->
  $ft = $('<div></div>').append(data)
  $(document).find('#mini_favorite_header_counter').html($ft.find('#mini_favorite_header_counter').html())
  $(document).find('#mini_favorite_header').html($ft.find('#mini_favorite_header').html())
$.fn.updateMiniCart = (data)->
  $ft = $('<div></div>').append(data)
  $(document).find('#mini_cart_header_counter').html($ft.find('#mini_cart_header_counter').html())
  $(document).find('#mini_cart_header').html($ft.find('#mini_cart_header').html())

$.fn.updGlobalCityInCart = (cityID)->
  soModule = $(document).find('#module_so')
  if soModule.is('div')
    soModule.find('[name="ORDER_PROP_18"]').val(cityID)
    $.fn.updateCart()

$.fn.updateCart = (data)->
  soBlock = $(document).find('#so_main_block')
  $(document).find('#shopcart-item1').html(data)
  soBlock.find('.preloaderCatalog').addClass('preloaderCatalogActive')
  $.refreshCartInfo()
  submitForm()

$.fn.offersByPropData = {}
$.fn.checkPropParams = ()->
  dataJSON = $(document).find('.offers_by_prop_json').attr('data-json')
  $.fn.offersByPropData = JSON.parse(dataJSON)
  $(document).find('.offers_prop .offer_prop_item').removeClass('propDisabled')
  $activeCount = $(document).find('.offers_prop .offer_prop_item.active').length
  $activePropObj = {}
  $(document).find('.offers_prop .offer_prop_item.active').each ()->
    propCode = $(@).attr('data-prop-code')
    itemID = $(@).attr('data-id')
    $activePropObj[propCode] = itemID
    if !--$activeCount
      $(document).find('.offers_prop .offer_prop_item').each ()->
        propCodeAlt = $(@).attr('data-prop-code')
        itemIDAlt = $(@).attr('data-id')
        filtResult = $.fn.offersByPropData.filter (item) =>
          if item.props.length <= 0
            return false
          resBool = true
          for ind of $activePropObj
            if ind != propCodeAlt
              if item.props[ind]
                if item.props[ind].id != $activePropObj[ind]
                  resBool = false
              else
                resBool = false
          if item.props[propCodeAlt]
            if item.props[propCodeAlt].id != itemIDAlt
              resBool = false
          else
            resBool = false

          return resBool

        if filtResult.length < 1
          $(@).addClass('propDisabled')
      filtResultByProp = $.fn.offersByPropData.filter (item) =>
        if item.props.length <= 0
          return false
        resBool = true
        for ind of $activePropObj
          if item.props[ind]
            if item.props[ind].id != $activePropObj[ind]
              resBool = false
          else
            resBool = false


        return resBool

      $(document).find('.addToCartBtn_mainPage').css 'display', 'inline-block'
      $(document).find('.goToFcnCart').addClass('dsb-hidden')
      if ($(document).find('.offers_prop').length == Object.keys($activePropObj).length) and filtResultByProp[0]
        newURL = $.fn.updateURLParameter(window.location.href, 'offer', filtResultByProp[0].id_offer)
        window.history.replaceState('', '', newURL)
        $(document).find('.addToCartBtn_mainPage').attr('data-id', filtResultByProp[0].id_offer)
        if $(document).find('.goToFcnCart[data-id="'+filtResultByProp[0].id_offer+'"]').is('a')
          $(document).find('.addToCartBtn_mainPage').addClass('dsb-hidden')
          $(document).find('.goToFcnCart[data-id="'+filtResultByProp[0].id_offer+'"]').removeClass('dsb-hidden')
        else
          $(document).find('.addToCartBtn_mainPage').removeClass('dsb-hidden')
        $(document).find('.BOC_btn').attr('data-id', filtResultByProp[0].id_offer)
        $(document).find('.addToCartBtn').attr('data-id', filtResultByProp[0].id_offer)
        $(document).find('.mainBlockPrice .product-sidebar__total-price-price').html(filtResultByProp[0].new_price)
        if filtResultByProp[0].difference_price
          $(document).find('.mainBlockPrice .product-sidebar__right-price').css 'display', 'block'
        else
          $(document).find('.mainBlockPrice .product-sidebar__right-price').css 'display', 'none'
        $(document).find('.article_code_field').html(filtResultByProp[0].article)
        $(document).find('.isElementName').html(filtResultByProp[0].name)
        $(document).find('.mainBlockPrice .product-sidebar__profit>span').html(filtResultByProp[0].difference_price)
        $(document).find('.mainBlockPrice .product-sidebar__old-price>span').html(filtResultByProp[0].old_price)

        $(document).find('#top_prop_model_prod span').html(filtResultByProp[0].model_top)
        if !filtResultByProp[0].model_top
          $(document).find('#top_prop_model_prod').addClass('hidden_top_prop')
        else
          $(document).find('#top_prop_model_prod').removeClass('hidden_top_prop')

        $(document).find('#top_prop_code_prod span').html(filtResultByProp[0].prod_code_top)
        if !filtResultByProp[0].prod_code_top
          $(document).find('#top_prop_code_prod').addClass('hidden_top_prop')
        else
          $(document).find('#top_prop_code_prod').removeClass('hidden_top_prop')

        $(document).find('.offersPropertiesList').html('')
        for ind of filtResultByProp[0].props
          if filtResultByProp[0].props[ind].title
            $(document).find('.offersPropertiesList').append('<p class="product-content__value">'+filtResultByProp[0].props[ind].title+'</p>')
      else
        $(document).find('.addToCartBtn_mainPage').css 'display', 'none'


$.fn.ajaxLoadCatalog = ()->
  filtParamCount = $(document).find('.cb-filter .cb-filter__param').length
  if filtParamCount > 0
    $(document).find('.cb-filter .cb-filter__clear').removeClass('dnd-hide')
  else
    $(document).find('.cb-filter .cb-filter__clear').addClass('dnd-hide')

  $(document).find('.preloaderCatalog').css({opacity: 0, display: 'block'})
  $(document).find('.preloaderCatalog').animate {opacity: 1}, 300
  urlForSend = $(document).find('.ajaxPageNav .cb-nav-pagination__item.active').attr('data-href')
  styleBlock = 'v-block'
  countOnPage = $(document).find('select[name="countOnPage"]').val()
  sort_by = $(document).find('select[name="sort_by"]').val()
  $(document).find('.cb-nav-style__block input[name="style"]').each ()->
    if $(@).prop('checked')
      styleBlock = $(@).attr('id')

  $data = {
    ajaxCal:'Y'
    styleBlock: styleBlock
    countOnPage: countOnPage
    sort_by: sort_by
  }
  $(document).find('.cb-filter .cb-filter__param input').each ()->
    $data['set_filter'] = 'Y'
    $data[$(@).attr('name')] = $(@).val()

  if urlForSend
    $.ajax {
      url: urlForSend
      type: 'GET'
      data: $data
      success: (data)->
        $(document).find('.preloaderCatalog').animate {opacity: 0}, 300, ()->
          $(@).css({opacity: 0, display: 'none'})
        $(document).find('#PROPDS_BLOCK').html(data)
        $(document).find('.catTopCount .catTopCountValue').html($(document).find('#PROPDS_BLOCK .catTopCountValue').html())
    }

$.fn.checkCartSlide = (numSlide)->
  if numSlide == 1
    return true
  else if numSlide == 2
    if $(document).find('#sci-contact-tab1').prop('checked')
      sBlock = $(document).find('#sci-contact-content1')
    else
      sBlock = $(document).find('#sci-contact-content2')

    $count = sBlock.find('input').length
    $formIsValid = true
    sBlock.find('input').each ()->
      if !$(@).val() and $(@).prop('required')
        $formIsValid = false

      if !--$count
        return $formIsValid

  else if numSlide == 3
    $formIsValid = true
    if $(document).find('#sci-delivery-tab1').prop('checked') or $(document).find('#sci-delivery-tab3').prop('checked') or $(document).find('#sci-delivery-tab4').prop('checked') or $(document).find('#sci-delivery-tab5').prop('checked') or $(document).find('#sci-delivery-tab6').prop('checked')
      if !$(document).find('#so_city_val').val()
        $formIsValid = false

      if !$(document).find('#sci-delivery-street').val()
        $formIsValid = false

      if !$(document).find('#sci-delivery-building').val()
        $formIsValid = false

      if !$(document).find('#sci-delivery-apartment').val()
        $formIsValid = false
    else
      if !$(document).find('#so_city_alt_val').val()
        $formIsValid = false

    return $formIsValid

  return false
$.fn.updateURLParameter = (url, param, paramVal) ->
  `var tmpAnchor`
  `var TheParams`
  TheAnchor = null
  newAdditionalURL = ''
  tempArray = url.split('?')
  baseURL = tempArray[0]
  additionalURL = tempArray[1]
  temp = ''
  if additionalURL
    tmpAnchor = additionalURL.split('#')
    TheParams = tmpAnchor[0]
    TheAnchor = tmpAnchor[1]
    if TheAnchor
      additionalURL = TheParams
    tempArray = additionalURL.split('&')
    i = 0
    while i < tempArray.length
      if tempArray[i].split('=')[0] != param
        newAdditionalURL += temp + tempArray[i]
        temp = '&'
      i++
  else
    tmpAnchor = baseURL.split('#')
    TheParams = tmpAnchor[0]
    TheAnchor = tmpAnchor[1]
    if TheParams
      baseURL = TheParams
  if TheAnchor
    paramVal += '#' + TheAnchor
  rows_txt = temp + '' + param + '=' + paramVal
  baseURL + '?' + newAdditionalURL + rows_txt
