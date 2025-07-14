import axios from 'axios';

// Set up global axios defaults for CSRF protection
const token = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found');
}

// Also set the X-Requested-With header for Laravel to recognize AJAX requests
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default axios;
