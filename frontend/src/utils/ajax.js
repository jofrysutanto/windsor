import config from './config'
import axios from 'axios'
export default {
  post (params) {
    // Use FormData instead of normal POST parameters,
    // @see https://wordpress.stackexchange.com/questions/282163/wordpress-ajax-with-axios
    let form_data = new FormData;
    for (const key in params) {
      if (params.hasOwnProperty(key)) {
        form_data.append(key, params[key])
      }
    }
    return axios.post(config.ajaxurl, form_data, {
      ...this.buildHeaders()
    })
  },
  buildHeaders () {
    return {
      headers: {
        'Content-Type': 'application/json'
      }
    }
  }
}
