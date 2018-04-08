jQuery(document).ready(function ($) {
  var baseUrl = $('link[rel="https://api.w.org/"]').attr('href')
  $('.widget_geocoded_posts_widget ul').each(function (key, value) {
    var ul = $(this)
    var count = parseInt(ul.attr('data-count'))
    var author = (ul.attr('data-author') === '1')
    var url = baseUrl + 'geocoded-posts/v1/basic'
    $.getJSON(url, function (data, status, jqXHR) {
      $.each(data, function (key, post) {
        if (key >= count) return false
        var content = '<li><a href="' + post.link + '">' + post.title + '</a>'
        if (author) { content += ' - ' + post.author }
        content += '</li>'
        ul.append(content)
      })
    })
  })
})
