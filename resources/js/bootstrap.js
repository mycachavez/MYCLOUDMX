import axios from 'axios'

axios.defaults.withCredentials = true // IMPORTANTE: envía la cookie de sesión
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

export default axios