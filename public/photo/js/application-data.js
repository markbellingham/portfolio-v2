const objParams = { town: '', fave_count: '', comment_count: '', comments: '', created:'', comment: '', directory: '',
    photo: '', secret: '', photo_id: '' };
export const photos = [];
export const userFaves = JSON.parse(localStorage.getItem('faves')) || [];
export const userId = 1;