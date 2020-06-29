/**
 *
 * @param {string} name - cookie name
 * @param {string} value - data to be saved to the cookie
 * @param {number} days - time before expiry
 * @param {string} path -
 */
export const setCookie = (name, value, days = 365, path = '/') => {
    const expires = new Date(Date.now() + days * 864e5).toUTCString()
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=' + path
}

export const getCookie = (name) => {
    const cookie = document.cookie.split('; ').reduce((r, v) => {
        const parts = v.split('=')
        return parts[0] === name ? decodeURIComponent(parts[1]) : r
    }, false)
    if(cookie) {
        setCookie(name, cookie, 365, '/');
        console.log('cookie reset');
        return cookie;
    } else {
        return false;
    }
}

export const deleteCookie = (name, path) => {
    setCookie(name, '', -1, path)
}