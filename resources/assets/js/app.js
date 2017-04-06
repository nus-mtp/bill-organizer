require('./bootstrap') // bootstrap load our application wide dependencies
/**
 * app.js
 * ------
 * This is where global application code should be,
 * app.js is the 'entry point' for our webpack configuration
 *
 *
 */
/* ====================================
=            Global inits            =
==================================== */

let initHeadRoom = function initHeadRoom () {
  let headerElement = $('#header')[0]
  if (!headerElement) return
  if (window.location.hash) {
    header.classList.add('headroom--unpinned')
  }
  let headroom = new Headroom(headerElement)
  headroom.init()
}

initHeadRoom()
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

/* ====================================
=            page scripts            =
==================================== */

/* ----------  Welcome.blade  ---------- */

// landing page register and login validations
const onLandingPageLoad = function () {
  $('.register.button').click(_ => {
    $('.register.modal').modal({
      onApprove: function () {
        $('form#register').submit()
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
        $('form#login').submit()
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
    on: 'change',
    inline: true,
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
          type: 'empty',
          prompt: 'Please enter your email address'
        },
          {
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
        }, {
          type: 'match[password]',
          prompt: 'Your password does not match'
        }]
      }
    }
  })

  $('.login.form').form({
    on: 'change',
    inline: true,
    fields: {
      email: {
        identifier: 'email',
        rules: [{
          type: 'empty',
          prompt: 'Please enter your email address'
        }, {
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
}
const onDashboardIndexPageLoad = function (window) {
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

  $('.js-btn-del-billorg').click(function (e) {
    e.preventDefault()
    $deleteBillorgForm = $('#deleteBillorgForm')
    console.log($deleteBillorgForm)
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#2ecc71',
      confirmButtonText: 'Yes, delete it!'
    }).then(function (e) {
      // console.log($deleteBillorgForm.attr('action'))
      $deleteBillorgForm.submit()
    }, function (dismiss) {
      return
    })
  })

  $('.ui.form.record-issuer').form({
    fields: {
      name: {
        identifier: 'name',
        rules: [{
          type: 'empty',
          prompt: 'Please enter record issuer name'
        }]
      }
    }
  })
}

/* ----------   RecordIssuer.blade---------- */

const onRecordsPageLoad = function (window) {
  // /dashboard modal controls

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

    // semantic ui custom form validation rule for file type
  $.fn.form.settings.rules.fileType = function () {
    fileName = document.getElementById('record').value
        // return true means validated
    return fileName.replace(/^.*\./, '') == 'pdf'
  }
  $('.ui.form.add-record').form({
    fields: {
      record: {
        identifier: 'record',
        rules: [{
          type: 'empty',
          prompt: 'Please choose a pdf file to upload'
        }, {
          type: 'fileType',
          prompt: 'Only .pdf files are accepted'
        }]
      },
      issue_date: {
        identifier: 'issue_date',
        rules: [{
          type: 'empty',
          prompt: 'Please enter the date of issue'
        }]
      },
      period: {
        identifier: 'period',
        rules: [{
          type: 'empty',
          prompt: 'Please enter the record period'
        }]
      },
      due_date: {
        identifier: 'due_date',
        rules: [{
          type: 'empty',
          prompt: 'Please enter the due date'
        }]
      },
      amount: {
        identifier: 'amount',
        rules: [{
          type: 'empty',
          prompt: 'Please enter the amount'
        }]
      }
    }
  })

  $('.logout.button').click((e) => {
    e.preventDefault()
    axios.post('/logout').then(_ => {
      location.reload()
    })
  })

  $('.js-delete-record-button').click(function (e) {
    e.preventDefault()
    $deleteRecordForm = $(this).find('form')
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#2ecc71',
      confirmButtonText: 'Yes, delete it!'
    }).then(function (e) {
      console.log($deleteRecordForm.attr('action'))
      $deleteRecordForm.submit()
    }, function (dismiss) {
      return
    })
  })

  /* ----------  stats sidebar scripts  ---------- */

  let toggleStatsSidebar = function toggleStats () {
    let stats = document.getElementById('stats')
    if (stats.style.display == 'none') {
      document.getElementById('stats').style.display = 'block'
      document.getElementById('statsbutton').className = 'ui circular tiny right floated icon button'
    } else {
      document.getElementById('stats').style.display = 'none'
      document.getElementById('statsbutton').className = 'ui circular tiny grey basic right floated icon button'
    }
  }

  $('#statsbutton').click(function () {
    toggleStatsSidebar()
  })

  let getStatsData = function getBillCount ($form, howManyMonthsAgo) {
    let requestUrl = $form.attr('action') + '/' + howManyMonthsAgo
    return axios.get(requestUrl)
  }
  let $statsForm = $('#stats-form')
  let $statsContainer = $('.js-stats-container')
  let $billCounterText = $statsContainer.find('.js-bill-count .value')
  let $billTotalText = $statsContainer.find('.js-bill-total .value')
  let myChart = null
  let setText = function (data) {
    const currencySymbol = 'S$'
    $billCounterText.text(data.billCount)
    $billTotalText.text(currencySymbol + data.total)
  }
  let initChart = function ($form, param) {
    axios.all([getStatsData($form, param)]).then(axios.spread(function (response) {
      setText(response.data)
      myChart = createChart(response.data.data)
    }))
  }

  let refreshChart = function ($form, param) {
    axios.all([getStatsData($form, param)]).then(axios.spread(function (response) {
      setText(response.data)
      updateChart(response.data.data, myChart)
    }))
  }
  initChart($statsForm, 0)

  $('#js-stats-menu').dropdown({
    onChange: function (value, text) {
      refreshChart($statsForm, value)
    }
  })

  let updateChart = function (data, myChart) {
    console.log(data)
    let dataObj = {
      labels: data.labels,
      datasets: [{
        label: '$ spent for period',
        data: data.data,
        backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
        borderColor: ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
        borderWidth: 1
      }]}
    myChart.config.data = dataObj
    myChart.update()
  }
  let createChart = function (data) {
    let ctx = document.getElementById('amountBarChart')
    myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: '$ spent for period',
          data: data.data,
          backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
          borderColor: ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    })
  }
  return myChart
}

/* ----------   editrecord.blade---------- */

const onEditPageLoad = function (window) {
    // for detecting active menu item in edit record
  $('.select').click(function () {
    $('.select').removeClass('active')
    $('.select').removeClass('doing')
    $(this).addClass('active doing')
    clearError()
  })

    // in the case that there is alrdy a template
  if ($('#issue_date_page').val()) {
    $('#issuedateicon').removeClass('grey edit')
    $('#issuedateicon').addClass('green check circle outline')
  }
  if ($('#period_page').val()) {
    $('#rperiodicon').removeClass('grey edit')
    $('#rperiodicon').addClass('green check circle outline')
  }
  if ($('#due_date_page').val()) {
    $('#duedateicon').removeClass('grey edit')
    $('#duedateicon').addClass('green check circle outline')
  }
  if ($('#amount_page').val()) {
    $('#amtdueicon').removeClass('grey edit')
    $('#amtdueicon').addClass('green check circle outline')
  }

  $('.ui.positive.ocr.button').click(function (e) {
        // e.preventDefault(); // not sure if need this
    var hasError = false
    var error = '<ul>'
    if (!$('#issue_date_page').val()) {
      error += '<li>Please select the issue date'
      hasError = true
    }
    if (!$('#period_page').val()) {
      error += '<li>Please select the record period'
      hasError = true
    }
    if (!$('#due_date_page').val()) {
      error += '<li>Please select the due date'
      hasError = true
    }
    if (!$('#amount_page').val()) {
      error += '<li>Please select the amount due'
      hasError = true
    }
    if (hasError) {
      displayError(error)
      return false
    }
  })

    /* $('#coords-form').form({
    fields: {
      issue_date_page: {
        identifier: 'issue_date_page',
        rules: [{
          type: 'empty',
          prompt: 'Please select the date of issue'
        }]
      },
      period_page: {
        identifier: 'period_page',
        rules: [{
          type: 'empty',
          prompt: 'Please select the record period'
        }]
      },
      due_date_page: {
        identifier: 'due_date_page',
        rules: [{
          type: 'empty',
          prompt: 'Please select the due date'
        }]
      },
      amount_page: {
        identifier: 'amount_page',
        rules: [{
          type: 'empty',
          prompt: 'Please select the amount'
        }]
      }
    }
  }) */
}

/* ===============================
=            Export list            =
=============================== */

var exportModules = (function (window) {
  window.onDashboardIndexPageLoad = onDashboardIndexPageLoad
  window.onRecordsPageLoad = onRecordsPageLoad
  window.onLandingPageLoad = onLandingPageLoad
  window.onEditPageLoad = onEditPageLoad
})(window)
