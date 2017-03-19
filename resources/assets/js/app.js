require('./bootstrap') // bootstrap load our application wide dependencies
/**
 * app.js
 * ------
 * This is where global application code should be,
 * app.js is the 'entry point' for our webpack configuration
 *
 *
 */

// landing page register and login validations
$('.register.button').click(_ => {
  $('.register.modal').modal({
    onApprove: function () {
      $('.ui.form').submit()
      $('.register.modal').modal('refresh')
      return false
    },
    onSuccess: function () {
      $('form#register').submit()
      $('.modal').modal('hide')
    }
  }).modal('show')
})

$('.login.button').click(_ => {
  $('.login.modal').modal({
    onApprove: function () {
      $('.ui.form').submit()
      $('.login.modal').modal('refresh')
      return false
    },
    onSuccess: function () {
      $('form#login').submit()
      $('.modal').modal('hide')
    }
  }).modal('show')
})

$('.register.form').form({
  fields: {
    name: {
      identifier: 'name',
      rules: [{
        type: 'empty',
        prompt: 'Please enter your name'
      }]
    },
    email: {
      identifier: 'email',
      rules: [{
        type: 'email',
        prompt: 'Please enter a valid email address'
      }]
    },
    password: {
      identifier: 'password',
      rules: [{
        type: 'minLength[6]',
        prompt: 'Your password must be at least 6 characters'
      }]
    },
    passwordconfirm: {
      identifier: 'password-confirm',
      rules: [{
        type: 'empty',
        prompt: 'Please confirm your password'
      },
      {
        type: 'match[password]',
        prompt: 'Your password does not match'
      }]
    }
  }
})

$('.login.form').form({
  fields: {
    email: {
      identifier: 'email',
      rules: [{
        type: 'empty',
        prompt: 'Please enter your email address'
      },
      {
        type: 'email',
        prompt: 'Please enter a valid email address'
      }]
    },
    password: {
      identifier: 'login_password',
      rules: [{
        type: 'empty',
        prompt: 'Please enter your password'
      }]
    }
  }
})

function initHeadRoom () {
  let headerElement = $('#header')[0]
  if (!headerElement) return
  if (window.location.hash) {
    header.classList.add('headroom--unpinned')
  }
  let headroom = new Headroom(headerElement)
  headroom.init()
}
initHeadRoom()

// /dashboard modal controls

$(function () {
  $('.add-record.button').click(_ => {
    $('.add-record.modal').modal({
      onApprove: function () {
        $('.ui.form').submit()
        return false
      },
      onSuccess: function () {
        $('form#add-record').submit()
        $('.modal').modal('hide')
      }
    }).modal('show')
  })

  $('.add-bill-org.button').click(_ => {
    $('.ui.modal.record-issuer').modal({
      onApprove: function () {
        $('.ui.form').submit()
          // need to return false to not close modal
          // in case input failed the validation test
        return false
      },
      onSuccess: function () {
        $('form#add-record-issuer').submit()
        $('.modal').modal('hide')
      }
    }).modal('show')
  })

  $('.del-bill-org.button').click(_ => {
    event.stopPropagation();
    event.preventDefault();
    //$('.ui.modal.record-issuer-del-cfm').modal('show')
  })

  $('.ui.form.record-issuer')
        .form({
          fields: {
            name: {
              identifier: 'name',
              rules: [
                {
                  type: 'empty',
                  prompt: 'Please enter record issuer name'
                }
              ]
            }
          }
        })

// semantic ui custom form validation rule for file type
  $.fn.form.settings.rules.fileType = function () {
    fileName = document.getElementById('record').value
    // return true means validated
    return fileName.replace(/^.*\./, '') == 'pdf'
  }

  $('.ui.form.add-record')
    .form({
      fields: {
        record: {
          identifier: 'record',
          rules: [
            {
              type: 'empty',
              prompt: 'Please choose a pdf file to upload'
            },
            {
              type: 'fileType',
              prompt: 'Only .pdf files are accepted'
            }
          ]
        },
        issue_date: {
          identifier: 'issue_date',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the date of issue'
            }
          ]
        },
        period: {
          identifier: 'period',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the record period'
            }
          ]
        },
        due_date: {
          identifier: 'due_date',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the due date'
            }
          ]
        },
        amount: {
          identifier: 'amount',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the amount'
            }
          ]
        }
      }
    })

  $('.ui.form.edit-record')
    .form({
      inline: true,
      on: 'blur',
      fields: {
        issuedate: {
          identifier: 'issue',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the date of issue'
            }
          ]
        },
        recordperiod: {
          identifier: 'period',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the record period'
            }
          ]
        },
        duedate: {
          identifier: 'duedate',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the due date'
            }
          ]
        },
        amtdue: {
          identifier: 'amtdue',
          rules: [
            {
              type: 'empty',
              prompt: 'Please enter the amount'
            }
          ]
        }
      }
    })

  $('.delete-record.button').click((e) => {
    e.preventDefault()
    $('form#delete-record').submit()
  })

  $('.logout.button').click((e) => {
    e.preventDefault()
    axios.post('/logout').then(_ => {
      location.reload()
    })
  })
})

// calendar settings
$('.ui.calendar').calendar({
  type: 'date',
  formatter: {
    date: function (date, settings) {
      if (!date) return ''
      var day = date.getDate()
      var month = date.getMonth() + 1
      var year = date.getFullYear()
      return day + '/' + month + '/' + year
    }
  }
})

$('.ui.calendar-month').calendar({
  type: 'month',
  formatter: {
    date: function (date, settings) {
      if (!date) return ''
      var month = settings.text.monthsShort[date.getMonth()]
      var year = date.getFullYear()
      return month + '/' + year
    }
  }
})

/* =====================================
=   Dashboard - Stats sidebar         =
===================================== */
// populate default (current month dropdown
let getStatsData = function getBillCount ($form, howManyMonthAgo) {
  let requestUrl = $form.attr('action') + '/' + howManyMonthAgo
  return axios.get(requestUrl)
}

let $statsForm = $('#stats-form')
let $statsContainer = $('.js-stats-container')
let $billCounterText = $statsContainer.find('.js-bill-count .value')
let $billAmountText = $statsContainer.find('.js-bill-amount .value')

let setText = function (data) {
  const currencySymbol = 'S$'
  $billCounterText.text(data.billCount)
  $billAmountText.text(currencySymbol + data.amount)
}

let sendRequests = function ($form, param) {
  axios.all([getStatsData($form, param)])
        .then(axios.spread(function (response) {
          setText(response.data)
        }))
}
sendRequests($statsForm, 0)
$('#js-stats-menu').dropdown({
  onChange: function (value, text) {
    console.log(value)
    sendRequests($statsForm, value)
  }
})

/* ================================================
=            Dashboard - Record Table            =
================================================ */
/*$('#js-delete-record-button').click(function (e) {
  e.preventDefault()
  swal({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(function () {
    $('form#delete-record').submit()
    swal(
    'Deleted!',
    'Your file has been deleted.',
    'success'
  )
  }, function (dismiss) {
    return
  })
  // $("#delete-record").submit()
})*/

