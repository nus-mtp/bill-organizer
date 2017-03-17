require('./bootstrap') // bootstrap load our application wide dependencies
/**
 * app.js
 * ------
 * This is where global application code should be,
 * app.js is the 'entry point' for our webpack configuration
 *
 *
 */

$(document).ready(function(){
 $('.register.button').click(_ => {
   $('.register.modal').modal('show')
 })
 $('.login.button').click(_ => {
   $('.login.modal').modal('show')
 })
})


$('.register.form').form({
  on: 'blur',
  inline: true,
  fields: {
    name: {
      identifier: 'name',
      rules: [{
          type  : 'empty',
          prompt: 'Please enter your name'
      }]
    },
    email: {
      identifier: 'email',
      rules: [{
          type  : 'empty',
          prompt: 'Please enter your email'
      },
      {
          type  : 'email',
          prompt: 'Please enter a valid email address'
      }]
    },
    password: {
      identifier: 'password',
      rules: [{
          type  : 'minLength[6]',
          prompt: 'Your password must be at least 6 characters'
      }]
    },
    passwordconfirm: {
      identifier: 'password-confirm',
      rules: [{
          type  : 'empty',
          prompt: 'Please confirm your password'
      },
      {
          type  : 'match[password]',
          prompt: 'Your passwords do not match'
      }]
    }
  }
})


$('.login.form').form({
  on: 'blur',
  inline: true,
  fields: {
    email: {
      identifier: 'email',
      rules: [{
          type  : 'empty',
          prompt: 'Please enter your email address'
      },
      {
          type  : 'email',
          prompt: 'Please enter a valid email address'
      }]
    },
    password: {
      identifier: 'login-password',
      rules: [{
          type  : 'empty',
          prompt: 'Please enter your password'
      }]
    }
  }
})

function initHeadRoom() {
    let headerElement = $('#header')[0]
    if (!headerElement) return ;
    if (window.location.hash) {
        header.classList.add('headroom--unpinned')
    }
    let headroom = new Headroom(headerElement)
    headroom.init()
}
initHeadRoom();


// /dashboard modal controls

$(function () {
  $('.add-record.button').click(_ => {
    $('.add-record.modal').modal({
      onApprove: _ => {
        $('form#add-record').submit()
      }
    }).modal('show')
  })

  $('.add-bill-org.button').click(_ => {
    $('.ui.modal.record-issuer').modal({
      onApprove: function () {
        $('form#add-record-issuer').submit()
      }
    }).modal('show')
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
