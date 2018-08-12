/**
 * redirect to view
 * @param url
 * @param back
 */
const redirect = function (url, back = true) {
    if (back)
        window.location = url
    else location.replace(url)
};