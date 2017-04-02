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


