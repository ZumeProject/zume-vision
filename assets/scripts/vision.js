/**
 * REST API
 */
window.API = {

  update_progress: (key, state) => makeRequest('POST', 'progress/update', { key: key, state: state }),

}
function makeRequest (type, url, data, base = 'zume/v4/') {
  const options = {
    type: type,
    contentType: 'application/json; charset=utf-8',
    dataType: 'json',
    url: url.startsWith('http') ? url : `${zumeTraining.root}${base}${url}`,
    beforeSend: xhr => {
      xhr.setRequestHeader('X-WP-Nonce', zumeTraining.nonce);
    }
  }

  if (data) {
    options.data = JSON.stringify(data)
  }

  return jQuery.ajax(options)
}
function handleAjaxError (err) {
  if (_.get(err, "statusText") !== "abortPromise" && err.responseText){
    console.trace("error")
    console.log(err)
    // jQuery("#errors").append(err.responseText)
  }
}
jQuery(document).ajaxComplete((event, xhr, settings) => {
  if (_.get(xhr, 'responseJSON.data.status') === 401) {
    window.location.replace(zumeTraining.site_urls.login);
  }
}).ajaxError((event, xhr) => {
  handleAjaxError(xhr)
})
