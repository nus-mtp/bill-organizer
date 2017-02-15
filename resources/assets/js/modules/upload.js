import Dropzone from 'dropzone'/**
 *
 * Logic
 * 1. upload locally
 * 2. validate filetype/basic security check
 * 3. preview uploaded file
 * 4. expansions: image upload
 * 5. ajax upload
 */

let Uploader = function (selector = '#uploader') {
  return new Uploader.init(selector)
}

Uploader.prototype = {
  initialize: function () {

  },
  preview: function () {

  },
  validate: function () {

  },
  upload: function () {

  }
}

Uploader.init = function (select) {

}

Uploader.init.prototype = Uploader.prototype

export default Uploader

