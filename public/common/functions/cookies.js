export function setCookie(name, value) {
    const today = new Date();
    const expiryDate = today.setFullYear(new Date().getFullYear() + 5);
    document.cookie = `${name}=${value}; expires=${expiryDate}; path=/`;
}

export function getCookie() {
    const cookie = document.cookie;
    return cookie === "" ? false : cookie;
}

export function deleteCookie(name) {
    document.cookie = `${name}= ; expires = Thu, 01 Jan 1970 00:00:00 GMT`;
}