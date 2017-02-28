require('./bootstrap') // bootstrap load our application wide dependencies
/**
 * app.js
 * ------
 * This is where global application code should be,
 * app.js is the 'entry point' for our webpack configuration
 *
 */

$(function () {
    $(".add-record.button").click(_ => {
        $('.add-record.modal').modal({
            onApprove: _ => {
                $('form#add-record').submit()
            }
        }).modal('show')
    })

    $('.delete-record.button').click((e) => {
        e.preventDefault()
        $('form#delete-record').submit()
    })

    $('.logout.button').click((e)=>{
        e.preventDefault()
        axios.post("/logout").then( _ => {
            location.reload()
        })
    })
})


